<?php


use PHPUnit\Framework\TestCase;

class FileUploaderAbstractTest extends TestCase
{
    public function testGetPossibleFileUploadMethods()
    {
        $expected = [
            'Drive',
            'AwsS3',
            'Dropbox'
        ];
        $methods = FileUploaderAbstract::getPossibleFileUploadMethods();

        array_multisort($expected);
        array_multisort($methods);

        $this->assertEquals(true, serialize($expected) === serialize($methods));
    }
}