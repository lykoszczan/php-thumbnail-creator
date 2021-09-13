<?php


use Handlers\FileUploaderDropbox;

class FileUploaderDropboxTest extends PHPUnit\Framework\TestCase
{
    /**
     * Upload file to Dropbox test
     *
     * @throws Exception
     */
    public function testSendFile()
    {
        $file = file_get_contents(__DIR__ . '/fixtures/pic3.png');

        $uploader = new FileUploaderDropbox();
        $result = $uploader->saveFile($file, 'pic3.png', 'folder');

        $this->assertEquals(true, $result);
    }
}