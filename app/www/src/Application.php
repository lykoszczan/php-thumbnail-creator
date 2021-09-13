<?php


class Application
{
    /**
     * max file size - 2mb
     */
    private const MAX_FILE_SIZE = '2000000';

    /**
     * @var null|FileUploaderAbstract
     */
    private $handler = null;

    /**
     * @param string $serviceName
     */
    public function setHandler(string $serviceName)
    {
        $targetClass = '\\Handlers\\FileUploader' . ucfirst($serviceName);

        if (!class_exists($targetClass)) {
            throw new \InvalidArgumentException('handler ' . $serviceName . ' does not exists');
        }

        $this->handler = new $targetClass();

        if (!is_a($this->handler, FileUploaderAbstract::class)) {
            $this->handler = null;
            throw new \InvalidArgumentException('handler ' . $serviceName . ' does not exists');
        }
    }

    /**
     * @return FileUploaderAbstract
     */
    public function getHandler(): FileUploaderAbstract
    {
        return $this->handler;
    }

    /**
     * @param array $file
     * @throws Exception
     */
    public function validateFile(array &$file)
    {
        if ($file['error']) {
            throw new Exception('file upload error');
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new Exception('file is too large');
        }

        //validate is image
        if (!is_array(getimagesize($file['tmp_name']))) {
            throw new Exception('file is not an image');
        }

        $fileInfo = pathinfo($file['name']);
        $file['name'] = FileUploaderAbstract::escapeFilePath($fileInfo['filename']) . '.' . $fileInfo['extension'];
    }

    /**
     * @param string $thumbnail
     * @param array $file
     * @param string $directory
     *
     * @return bool
     * @throws Exception
     */
    public function save(string $thumbnail, array $file, string $directory): bool
    {
        if (!isset($this->handler)) {
            throw new Exception('handler can not be null');
        }
        $directory = FileUploaderAbstract::escapeFilePath($directory);

        return $this->handler->saveFile($thumbnail, $file['name'], $directory);
    }
}