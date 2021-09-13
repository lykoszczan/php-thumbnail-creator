<?php

use Handlers\FileUploaderDrive;

class FileUploaderDriveTest extends PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../';
    }

    /**
     * Upload file do hard drive test
     */
    public function testSendFile()
    {
        $fileContent = file_get_contents(__DIR__ . '/fixtures/pic1.jpg');

        $fileUploader = new FileUploaderDrive();
        $result = $fileUploader->saveFile($fileContent, 'test.jpg', 'testFolder');

        $this->assertEquals(true, $result);
        $this->assertEquals(true, file_exists($_SERVER['DOCUMENT_ROOT'] . '/userFiles/testFolder/test.jpg'));

        unlink($_SERVER['DOCUMENT_ROOT'] . '/userFiles/testFolder/test.jpg');

        $result = $fileUploader->saveFile($fileContent, 'test.jpg', '');

        $this->assertEquals(true, $result);
        $this->assertEquals(true, file_exists($_SERVER['DOCUMENT_ROOT'] . '/userFiles/test.jpg'));
        unlink($_SERVER['DOCUMENT_ROOT'] . '/userFiles/test.jpg');
    }
}