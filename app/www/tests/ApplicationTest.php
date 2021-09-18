<?php

use Handlers\FileUploaderAwsS3;
use Handlers\FileUploaderDrive;
use Handlers\FileUploaderDropbox;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    private const SAVE_ACTION = 'save';

    public function testSendFileInvalidService(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $app = new Application(self::SAVE_ACTION);

        $app->setHandler('service_doesnt_exists');
    }

    public function testSetHandler(): void
    {
        $app = new Application(self::SAVE_ACTION);

        $app->setHandler('AwsS3');
        $this->assertInstanceOf(FileUploaderAwsS3::class, $app->getHandler());

        $app->setHandler('Drive');
        $this->assertInstanceOf(FileUploaderDrive::class, $app->getHandler());

        $app->setHandler('Dropbox');
        $this->assertInstanceOf(FileUploaderDropbox::class, $app->getHandler());
    }
}