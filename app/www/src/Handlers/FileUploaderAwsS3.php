<?php

namespace Handlers;

use Aws\S3\S3Client;
use Exception;
use FileUploaderAbstract;
use InvalidArgumentException;

/**
 * Class FileUploaderAwsS3
 * @package Handlers
 */
class FileUploaderAwsS3 extends FileUploaderAbstract
{
    /**
     * @return S3Client
     * @throws Exception
     */
    private function getClient(): S3Client
    {
        static $client;
        if (!isset($client)) {
            $config = $this->getConfig();
            $client = new S3Client([
                'version' => 'latest',
                'region' => $config['region'],
                'http' => [
                    'verify' => false
                ],
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);
        }

        return $client;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        if (empty($directory)) {
            throw new InvalidArgumentException('Bucket name cannot be empty');
        }

        $this->getClient()->putObject([
            'Bucket' => $directory,
            'Key' => $fileName,
            'Body' => $fileContent,
        ]);

        return true;
    }
}