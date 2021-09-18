<?php

namespace Handlers;

use Exception;
use FileUploaderAbstract;
use RuntimeException;

/**
 * Class FileUploaderDropbox
 * @package Handlers
 */
class FileUploaderDropbox extends FileUploaderAbstract
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        $config = $this->getConfig();

        $putData = fopen('php://temp/', 'wb');
        if (!$putData) {
            throw new RuntimeException('could not open temp memory data');
        }
        fwrite($putData, $fileContent);
        fseek($putData, 0);

        $payload = [
            "path" => "/" . $fileName,
            "mode" => "add",
            "autorename" => true,
            "mute" => false,
            "strict_conflict" => false
        ];

        if ($directory) {
            $payload['path'] = "/" . $directory . '/' . $fileName;
        }

        $headers = [
            'Authorization: Bearer ' . $config['token'],
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode($payload, JSON_THROW_ON_ERROR)
        ];

        $curl = curl_init('https://content.dropboxapi.com/2/files/upload');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_PUT, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        /** @noinspection CurlSslServerSpoofingInspection */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_INFILE, $putData);
        curl_setopt($curl, CURLOPT_INFILESIZE, strlen($fileContent));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        fclose($putData);

        return $http_code === 200;
    }
}
