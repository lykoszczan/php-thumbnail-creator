<?php

/**
 * Class PathHelper
 */
class PathHelper
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function escapeFilePath(string $path): string
    {
        return (string)preg_replace('/[^A-Za-z0-9_\-]/', '_', $path);
    }
}