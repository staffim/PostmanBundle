<?php

namespace Postman\PostmanBundle;

/**
 * @author Semen Barabash <semen.barabash@gmail.com>
 */
class Attachment
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
     * @param string $fileName
     * @param string $mimeType
     * @param int $size
     * @param string $dispositionType
     */
    public function __construct($fileName, $mimeType, $size, $dispositionType)
    {
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->dispositionType = $dispositionType;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getDispositionType()
    {
        return $this->dispositionType;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
