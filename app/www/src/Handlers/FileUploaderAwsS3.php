<?php

namespace Handlers;

use Aws\S3\S3Client;
use Exception;
use FileUploaderAbstract;
use InvalidArgumentException;

class FileUploaderAwsS3 extends FileUploaderAbstract
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        if (empty($directory)) {
            throw new InvalidArgumentException('Bucket name cannot be empty');
        }

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

        $client->putObject([
            'Bucket' => $directory,
            'Key' => $fileName,
            'Body' => $fileContent,
        ]);

        return true;
    }
}