<?php

namespace Postman\PostmanBundle\Parser;

use Postman\PostmanBundle\Attachment;
use Postman\PostmanBundle\MailBuilder;
use EmailReplyParser\EmailReplyParser;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 * @author Semen Barabash <semen.barabash@gmail.com>
 */
class Parser implements ParserInterface
{
    /**
     * @var \Postman\PostmanBundle\MailBuilder
     */
    protected $mailBuilder;

    protected function parseMultipart(\ezcMailMultipart $part)
    {
        if ($part instanceof \ezcMailMultipartAlternative) {
            $plainPart = null;
            // By priority: text/plain, text/html, other.
            $parts = $part->getParts();

            foreach ($part->getParts() as $subPart) {
                if ($subPart instanceof \ezcMailText) {
                    $plainPart = $subPart;
                    if ($subPart->subType == 'plain') {
                        break;
                    }
                }
            }
            if ($plainPart) {
                $this->parsePart($plainPart);
            } else {
                $this->parsePart($parts[0]);
            }
        } elseif ($part instanceof \ezcMailMultipartMixed) {
            foreach ($part->getParts() as $subPart) {
                $this->parsePart($subPart);
            }
        } elseif ($part instanceof \ezcMailMultipartRelated) {
            // Currently we skip multipart/related
        }
    }

    protected function parsePart($part)
    {
        if ($part instanceof \ezcMailMultipart) {
            $this->parseMultipart($part);
        } elseif ($part instanceof \ezcMailFile) {
            $this->parseFilePart($part);
        } elseif ($part instanceof \ezcMailText) {
            $this->parseTextPart($part);
        }
    }

    protected function parseFilePart(\ezcMailFile $part)
    {
        $attachment = new Attachment($part->fileName, $part->mimeType, $part->size, $part->dispositionType);

        $this->mailBuilder->addAttachment($attachment);
    }

    protected function parseTextPart(\ezcMailText $part)
    {
        $plainText = $part->text;
        if ($part->subType != 'plain') {
            $plainText = $this->cleanHtmlBody($plainText);
        }

        $visibleFragments = array_filter(EmailReplyParser::read($plainText), function($fragment) {
            return !($fragment->isHidden() || $fragment->isQuoted());
        });
        $text = rtrim(implode("\n", $visibleFragments));

        $this->mailBuilder->addText($text);
    }

    /**
     * Remove tags and replace some tags with new-line
     *
     * @param string $body
     * @return string
     */
    protected function cleanHtmlBody($body)
    {
        $body = preg_replace('~<br[^>]*>~', "\n", $body);
        $body = preg_replace('~<ul[^>]*>~', "\n", $body);
        $body = preg_replace('~<div[^>]*>~', "\n", $body);
        $body = preg_replace('~<li[^>]*>~', '* ', $body);

        $body = preg_replace('~(?:\n\s?\n)+~', "\n", $body);

        $body = stripslashes(html_entity_decode($body));

        $body = preg_replace('~(<[^@>]+>)~', '', $body);

        return $body;
    }

    /**
     * @param string $mail Raw mail string.
     *
     * @return \Postman\PostmanBundle\Mail
     *
     * @throws \InvalidArgumentException
     */
    public function parse($mail)
    {
        $parser = new \ezcMailParser();

        $mails = $parser->parseMail(new \ezcMailVariableSet($mail));

        $mail = array_shift($mails);

        $this->mailBuilder = MailBuilder::create()
            ->setSubject($mail->subject)
            ->setTo($mail->to[0]->email)
            ->setFrom($mail->from->email);

        $this->parsePart($mail->body);

        return $this->mailBuilder->getMail();
    }
}
