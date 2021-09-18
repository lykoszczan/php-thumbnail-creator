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
    public function testFileWasUploadedToAmazonS3(): void
    {
        $fileContent = file_get_contents(__DIR__ . '/fixtures/pic3.png');

        $fileUploader = new FileUploaderAwsS3();
        $result = $fileUploader->saveFile($fileContent, 'test', 'testbucket-mlykowski');

        $this->assertEquals(true, $result);
    }

    /**
     * Upload file to amazon s3 test - not bucket existing bucket test
     *
     * @throws Exception
     */
    public function testExceptionWasThroughWhenBucketDoesntExists(): void
    {
        $fileContent = file_get_contents(__DIR__ . '/fixtures/pic3.png');

        $fileUploader = new FileUploaderAwsS3();

        $this->expectException(Exception::class);
        $result = $fileUploader->saveFile($fileContent, 'test', 'no_bucket');
    }
}