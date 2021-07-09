<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileJson
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function create($uploadDir, $templateUuid, $config)
    {
        try {
            $path = $uploadDir  . $templateUuid;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($uploadDir . $templateUuid . '.json', json_encode($config));
        } catch (FileException $e){
            $this->logger->error('Failed to create file json: ' . $e->getMessage());
            throw new FileException('Failed to create file json');
        }
    }
}