<?php

namespace Handlers;

use FileUploaderAbstract;

class FileUploaderDrive extends FileUploaderAbstract
{
    /**
     * @return string
     */
    private function getUserFilesDirectory(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/userFiles/';
    }

    /**
     * @param string $path
     */
    private function createDirectoryIfNotExists(string $path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }

    /**
     * @inheritDoc
     */
    public function saveFile(string $fileContent, string $fileName, string $directory): bool
    {
        $pathToSave = $this->getUserFilesDirectory();

        $this->createDirectoryIfNotExists($pathToSave);
        if ($directory) {
            $pathToSave .= DIRECTORY_SEPARATOR . $directory;
            $this->createDirectoryIfNotExists($pathToSave);
        }

        return file_put_contents($pathToSave . DIRECTORY_SEPARATOR . $fileName, $fileContent);
    }
}