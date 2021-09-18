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
    abstract public function saveFile(string $fileContent, string $fileName, string $directory): bool;

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