<?php


abstract class FileUploaderAbstract
{
    /**
     * @return array
     * @throws Exception
     */
    protected function getConfig(): array
    {
        $reflect = new ReflectionClass($this);
        $className = $reflect->getShortName();

        $config = file_get_contents(__DIR__ . '/../configs/' . $className . '.json');
        if (!$config) {
            throw new Exception('invalid config file for class ' . $className);
        }

        return json_decode($config, true);
    }

    /**
     * @param string $fileContent
     * @param string $fileName
     * @param string $directory
     *
     * @return bool
     */
    public abstract function saveFile(string $fileContent, string $fileName, string $directory): bool;

    /**
     * @param string $path
     *
     * @return string|string[]|null
     */
    public static function escapeFilePath(string $path)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '_', $path);
    }

    /**
     * @return array
     */
    public static function getPossibleFileUploadMethods(): array
    {
        $methods = [];
        foreach (glob(__DIR__ . "/Handlers/*.php") as $fileName) {
            $methodName = str_replace('FileUploader', '', pathinfo($fileName)['filename']);
            $methods[] = $methodName;
        }

        return $methods;
    }
}