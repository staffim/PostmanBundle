<?php

namespace Postman\PostmanBundle;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class Mail
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
    private $body;

    /**
     * @var \Postman\PostmanBundle\Attachment[]
     */
    private $attachments;

    /**
     * @param string $from
     * @param string $to
     * @param string string $subject
     * @param string string $body
     * @param string string $replyAddress
     * @param \Postman\PostmanBundle\Attachment[] $attachments
     */
    public function __construct($from, $to, $subject = '', $body = '', array $attachments = array())
    {
        $this->from        = $from;
        $this->to          = $to;
        $this->subject     = $subject;
        $this->body        = $body;
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo($withoutDomain = false)
    {
        if ($withoutDomain) {
            return array_shift(explode('@', $this->to));
        } else {
            return $this->to;
        }
    }

    /**
     * Subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return \Postman\PostmanBundle\Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
