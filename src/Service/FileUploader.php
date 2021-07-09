<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class FileUploader
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function upload($uploadDir, $file, $filename)
    {
        try {
            $file->move($uploadDir, $filename);
        } catch (FileException $e){
            $this->logger->error('Failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}