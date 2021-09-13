<?php


use Handlers\FileUploaderAwsS3;
use PHPUnit\Framework\TestCase;

class FileUploaderAwsS3Test extends TestCase
{
    /**
     * Upload file to amazon s3 test
     *
     * @throws Exception
     */
    public function testSendFile()
    {
        $fileContent = file_get_contents(__DIR__ . '/fixtures/pic3.png');

        $fileUploader = new FileUploaderAwsS3();
        $result = $fileUploader->saveFile($fileContent, 'test', 'testbucket-mlykowski');

        $this->assertEquals(true, $result);
    }

    //@TODO - bucket doesn't exists test
}