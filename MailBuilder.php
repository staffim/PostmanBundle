<?php

namespace Postman\PostmanBundle;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class MailBuilder
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var \Postman\PostmanBundle\Attachment[]
     */
    private $attachments = array();

    /**
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public static function create()
    {
        return new self;
    }

    public function getMail()
    {
        return new Mail(
            $this->from,
            $this->to,
            $this->subject,
            $this->body,
            $this->attachments
        );
    }

    /**
     * @param string $from
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string $to
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $subject
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $body
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function addText($text)
    {
        $this->body = ($this->body ? $this->body."\n" : '').$text;

        return $this;
    }

    /**
     * @param string $body
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param \Postman\PostmanBundle\Attachment $attachment
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param array $attachments
     * @return \Postman\PostmanBundle\MailBuilder
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }
}
