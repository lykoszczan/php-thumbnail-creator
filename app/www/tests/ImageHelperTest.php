<?php


class ImageHelperTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test of generating thumbnail
     */
    public function testCreateThumbnail(): void
    {
        $thumbnail = null;

        foreach (glob(__DIR__ . "/fixtures/*") as $filepath) {

            $file = file_get_contents($filepath);
            $thumbnail = ImageHelper::createThumbnail($file);

            [$width, $height] = getimagesizefromstring($thumbnail);
            $maxSize = max($width, $height);

            $this->assertEquals(true, $maxSize === 150);
        }

        $thumbnailFromThumbnail = ImageHelper::createThumbnail($thumbnail);
        [$widthThumbnail, $heightThumbnail] = getimagesizefromstring($thumbnail);
        [$width, $height] = getimagesizefromstring($thumbnailFromThumbnail);

        //check thumbnail from thumbnail is equal to its original dimensions
        $this->assertEquals($widthThumbnail, $width);
        $this->assertEquals($heightThumbnail, $height);
    }
}