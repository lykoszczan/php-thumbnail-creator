<?php

namespace Handlers;

use Exception;
use FileUploaderAbstract;

class FileUploaderDropbox extends FileUploaderAbstract
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        $config = $this->getConfig();

        $putData = fopen('php://temp/', 'w');
        if (!$putData) {
            throw new Exception('could not open temp memory data');
        }
        fwrite($putData, $fileContent);
        fseek($putData, 0);

        $payload = [
            "path" => "/" . $directory . '/' . $fileName,
            "mode" => "add",
            "autorename" => true,
            "mute" => false,
            "strict_conflict" => false
        ];

        $headers = [
            'Authorization: Bearer ' . $config['token'],
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' . json_encode($payload)
        ];

        $curl = curl_init('https://content.dropboxapi.com/2/files/upload');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_PUT, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_INFILE, $putData);
        curl_setopt($curl, CURLOPT_INFILESIZE, strlen($fileContent));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        fclose($putData);

        return $http_code == 200;
    }
}