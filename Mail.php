<?php

namespace Postman\PostmanBundle;

/**
 * Abstraction from parsing libraries (Zeta Components Mail, Swift,..).
 *
 * @author Alexey Shockov <alexey@shockov.com>
 */
// TODO Raw mail as string...
class Mail
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $replyAddress;

    /**
     * @var \Postman\PostmanBundle\Attachment[]
     */
    private $attachments;

    /**
     * @param string $sender
     * @param string $recipient
     * @param string string $subject
     * @param string string $body
     * @param string string $replyAddress
     * @param \Postman\PostmanBundle\Attachment[] $attachments
     */
    public function __construct($sender, $recipient, $subject = '', $body = '', $replyAddress = '', array $attachments = array())
    {
        $this->sender       = $sender;
        $this->recipient    = $recipient;
        $this->subject      = $subject;
        $this->body         = $body;
        $this->replyAddress = $replyAddress;
        $this->attachments  = $attachments;
    }

    /**
     * Reply-To.
     *
     * @return string
     */
    public function getReplyAddress()
    {
        return $this->replyAddress;
    }

    /**
     * From.
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * To. Without domain part.
     *
     * @return string
     */
    public function getRecipientName()
    {
        return preg_replace('/^(.*)\@/i', '$1', $this->recipient);
    }

    /**
     * To.
     *
     * @return string
     */
    // TODO Many recipients.
    public function getRecipient()
    {
        return $this->recipient;
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
