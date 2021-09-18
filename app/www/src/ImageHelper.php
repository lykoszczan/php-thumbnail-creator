<?php

/**
 * Class ImageHelper
 */
class ImageHelper
{
    /**
     * @param string $fileContent
     * @param int    $maxSize
     *
     * @return string
     */
    public static function createThumbnail(string $fileContent, int $maxSize = 150): string
    {
        $imageResource = imagecreatefromstring($fileContent);

        $width = imagesx($imageResource);
        $height = imagesy($imageResource);

        if (max($width, $height) > $maxSize) {
            if ($width >= $height) {
                $newImage = imagescale($imageResource, $maxSize);
            } else {
                $newWidth = $width * $maxSize / $height;
                $newImage = imagescale($imageResource, $newWidth, $maxSize);
            }

            ob_start();
            imagepng($newImage);
            $contents = ob_get_contents();
            ob_end_clean();

            imagedestroy($newImage);
        } else {
            $contents = $fileContent;
        }

        imagedestroy($imageResource);

        return $contents;
    }
}