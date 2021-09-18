<?php


/**
 * Class Application
 */
class Application
{
    /**
     * max file size - 2mb
     */
    private const MAX_FILE_SIZE = '2000000';

    /**
     * @var null|FileUploaderAbstract
     */
    private ?FileUploaderAbstract $handler = null;

    /**
     * @var string
     */
    private string $action;

    /**
     * @var FormView
     */
    private FormView $formView;

    /**
     * @var array
     */
    private array $uploadedFile;

    /**
     * @var string
     */
    private string $directory;

    /**
     * Application constructor.
     * @param string $action
     */
    public function __construct(string $action)
    {
        $this->action = $action;
        $this->formView = new FormView();
    }

    /**
     * @return FileUploaderAbstract
     */
    public function getHandler(): FileUploaderAbstract
    {
        return $this->handler;
    }

    /**
     * @param string $serviceName
     */
    public function setHandler(string $serviceName)
    {
        if (!$this->isSaveAction()) {
            return;
        }

        $targetClass = '\\Handlers\\FileUploader' . ucfirst($serviceName);

        if (!class_exists($targetClass)) {
            throw new InvalidArgumentException('handler ' . $serviceName . ' does not exists');
        }

        $this->handler = new $targetClass();

        if (!is_a($this->handler, FileUploaderAbstract::class)) {
            $this->handler = null;
            throw new InvalidArgumentException('handler ' . $serviceName . ' does not exists');
        }
    }

    /**
     * @return bool
     */
    private function isSaveAction(): bool
    {
        return $this->action === 'save';
    }

    /**
     * @param string $directory
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param array $file
     */
    public function setFile(array $file)
    {
        $this->uploadedFile = $file;
    }

    /**
     * @return void
     */
    public function render(): void
    {
        if ($this->isSaveAction()) {
            $this->renderSaveView();
        }

        $this->renderMainView();
    }

    /**
     * @return void
     */
    private function renderSaveView(): void
    {
        try {
            $this->validateFile();
            $fileContent = file_get_contents($this->uploadedFile['tmp_name']);
            $thumbnail = ImageHelper::createThumbnail($fileContent);

            if (!$thumbnail) {
                throw new Exception('cannot create thumbnail');
            }

            if (!$this->save($thumbnail)) {
                throw new Exception('save failed');
            }

            die($this->formView->getFormViewAfterUpload($thumbnail));
        } catch (Exception $exception) {
            $this->handleError($exception);
        }
    }

    /**
     * @throws Exception
     */
    private function validateFile()
    {
        try {
            if ($this->uploadedFile['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('file upload error');
            }

            if ($this->uploadedFile['size'] > self::MAX_FILE_SIZE) {
                throw new Exception('file is too large');
            }

            //validate is image
            if (!is_array(getimagesize($this->uploadedFile['tmp_name']))) {
                throw new Exception('file is not an image');
            }

            $fileInfo = pathinfo($this->uploadedFile['name']);
            $this->uploadedFile['name'] = PathHelper::escapeFilePath($fileInfo['filename']) . '.' . $fileInfo['extension'];
        } catch (Exception $exception) {
            $this->handleError($exception);
        }
    }

    /**
     * @param Exception $exception
     */
    private function handleError(Exception $exception): void
    {
        $this->formView->setError($exception->getMessage());
        $this->renderMainView();
    }

    /**
     * @return void
     */
    private function renderMainView(): void
    {
       die($this->formView->getFormView(FileUploaderAbstract::getPossibleFileUploadMethods()));
    }

    /**
     * @param string $thumbnail
     *
     * @return bool
     * @throws Exception
     */
    private function save(string $thumbnail): bool
    {
        if (!isset($this->handler)) {
            throw new Exception('handler can not be null');
        }
        $this->directory = PathHelper::escapeFilePath($this->directory);

        return $this->handler->saveFile($thumbnail, $this->uploadedFile['name'], $this->directory);
    }
}