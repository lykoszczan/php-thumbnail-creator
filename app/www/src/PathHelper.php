<?php

/**
 * Class PathHelper
 */
class PathHelper
{
    /**
     * @param string $path
     *
     * @return string|string[]|null
     */
    public static function escapeFilePath(string $path)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '_', $path);
    }
}