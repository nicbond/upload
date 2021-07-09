<?php

namespace App\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\UnableToWriteFile;
use Psr\Log\LoggerInterface;

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
        } catch (UnableToWriteFile $e) {
            $this->logger->error('Failed to upload file: ' . $e->getMessage());
            throw new UnableToWriteFile('Failed to upload file', Response::HTTP_BAD_REQUEST);
        }
    }
}
