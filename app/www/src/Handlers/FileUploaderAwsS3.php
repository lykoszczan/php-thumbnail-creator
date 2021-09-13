<?php

namespace Handlers;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use FileUploaderAbstract;
use http\Exception\InvalidArgumentException;

class FileUploaderAwsS3 extends FileUploaderAbstract
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        if (empty($directory)) {
            throw new \InvalidArgumentException('Bucket name cannot be empty');
        }

        $config = $this->getConfig();
        $s3Client = new S3Client([
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

        $s3Client->putObject([
            'Bucket' => $directory,
            'Key' => $fileName,
            'Body' => $fileContent,
        ]);

        return true;
    }
}