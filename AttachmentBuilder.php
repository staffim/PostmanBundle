<?php

namespace Postman\PostmanBundle;

/**
 * @author Semen Barabash <semen.barabash@gmail.com>
 */
class AttachmentBuilder
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $dispositionType;

    /**
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public static function create()
    {
        return new self;
    }

    /**
     * @param string $dispositionType
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public function setDispositionType($dispositionType)
    {
        $this->dispositionType = $dispositionType;

        return $this;
    }

    /**
     * @param string $fileName
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public function setFileName($fileName)
    {
        // FIXME Try to find way to do this work in zeta components.
        preg_match('~=\?[-\w]+?\?B\?(?P<fileName>.+)\?=~', $fileName, $matches);
        if (array_key_exists('fileName', $matches) && $decodedFileName = base64_decode($matches['fileName'], true)) {
            $fileName = $decodedFileName;
        }

        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param string $mimeType
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @param int $size
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return \Postman\PostmanBundle\Attachment
     */
    public function getAttachment()
    {
        return new Attachment(
            $this->fileName,
            $this->mimeType,
            $this->size,
            $this->dispositionType
        );
    }
}
