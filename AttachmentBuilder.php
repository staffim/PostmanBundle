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
    private $path;

    /**
     * Original filename.
     *
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
        $fileNameParts = array_map(array($this, 'decodeFilenamePart'), preg_split('~\n+~', $fileName));
        $this->fileName = implode('', $fileNameParts);

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
     * @param string $path
     * @return \Postman\PostmanBundle\AttachmentBuilder
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return \Postman\PostmanBundle\Attachment
     */
    public function getAttachment()
    {
        return new Attachment(
            $this->path,
            $this->fileName,
            $this->mimeType,
            $this->size,
            $this->dispositionType
        );
    }

    /**
     * @param string $part Encoded part of filename.
     * @return string Decoded part of filename.
     */
    private function decodeFilenamePart($part)
    {
        preg_match('~=(?:\?|_)(?P<encoding>[-\w]+?)(?:\?|_)(?P<encodingType>B|Q)(?:\?|_)(?P<fileName>.+)(?:\?|_)=~i', $part, $matches);
        if (array_key_exists('fileName', $matches)) {
            $encodingType = $matches['encodingType'];
            $fileName = $matches['fileName'];
            if (strcasecmp($encodingType, 'B') === 0) {
                if ($decodedPart = base64_decode($fileName, true)) {
                    // Ensure in utf-8.
                    $encoding = $matches['encoding'];
                    if (strcasecmp($encoding, 'utf-8') !== 0) {
                        $decodedPart = iconv($encoding, 'utf-8', $decodedPart);
                    }

                    $part = $decodedPart;
                }
            } else {
                $part = quoted_printable_decode(str_replace("_", " ", $fileName));
            }
        }

        return $part;
    }
}
