<?php

namespace Postman\PostmanBundle\Parser;

use Postman\PostmanBundle\Attachment;
use Postman\PostmanBundle\Mail;
use EmailReplyParser\EmailReplyParser;

/**
 * @author Alexey Shockov <alexey@shockov.com>
 * @author Semen Barabash <semen.barabash@gmail.com>
 */
class Parser implements ParserInterface
{
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

        // TODO Parse text/html to text...
        $plainPart = null;
        $attachments = array();
        if ($mail->body instanceof \ezcMailMultipart) {
            foreach ($mail->body->getParts() as $part) {
                if ($part instanceof \ezcMailText && 'plain' == $part->subType) {
                    $plainPart = $part;
                } elseif ($part instanceof \ezcMailFile) {
                    $attachments[] = new Attachment(
                        $part->fileName, $part->mimeType, $part->size, $part->dispositionType
                    );
                }
            }
        } else {
            $plainPart = $mail->body;
        }

        if (empty($mail->from) || empty($mail->to)) {
            throw new \InvalidArgumentException('Unable to parse message.');
        }

        $visibleFragments = EmailReplyParser::read($plainPart->text);

        $visibleFragments = array_filter($visibleFragments, function($fragment) {
            return !($fragment->isHidden() || $fragment->isQuoted());
        });

        $text = implode("\n", $visibleFragments);

        $text = rtrim($text);

        return new Mail(
            $mail->from,
            $mail->to[0],
            $mail->subject,
            $text,
            $mail->from->email,
            $attachments
        );
    }
}
