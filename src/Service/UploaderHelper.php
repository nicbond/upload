<?php

namespace App\Service;

use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;

class UploaderHelper
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function uploads($location = '', $imageDestination = '')
    {
        $stream = fopen($location, 'r+');
        $filename = basename($location);

        try {
            $this->filesystem->writeStream($imageDestination . $filename, $stream);
            fclose($stream);
        } catch (IOException $e) {
            $this->logger->error('Failed to upload file: ' . $e->getMessage());
            throw new IOException('Failed to upload file');
        }
    }
}
