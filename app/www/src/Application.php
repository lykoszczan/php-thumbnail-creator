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

    private ?FileUploaderAbstract $handler = null;

    private string $action;

    private FormView $formView;

    private array $uploadedFile;

    private string $directory;

    public function __construct(string $action)
    {
        $this->action = $action;
        $this->formView = new FormView();
    }

    public function getHandler(): FileUploaderAbstract
    {
        return $this->handler;
    }

    public function setHandler(string $serviceName): void
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

    private function isSaveAction(): bool
    {
        return $this->action === 'save';
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = PathHelper::escapeFilePath($directory);
    }

    public function setFile(array $file): void
    {
        $this->uploadedFile = $file;
    }

    public function render(): void
    {
        if ($this->isSaveAction()) {
            $this->renderSaveView();
        }

        $this->renderMainView();
    }

    private function renderSaveView(): void
    {
        try {
            $this->validateFile();
            $fileContent = file_get_contents($this->uploadedFile['tmp_name']);
            $thumbnail = ImageHelper::createThumbnail($fileContent);

            if (!$thumbnail) {
                throw new RuntimeException('cannot create thumbnail');
            }

            if (!$this->save($thumbnail)) {
                throw new RuntimeException('save failed');
            }

            die($this->formView->getFormViewAfterUpload($thumbnail));
        } catch (Exception $exception) {
            $this->handleError($exception);
        }
    }

    /**
     * @throws Exception
     */
    private function validateFile(): void
    {
        try {
            if ($this->uploadedFile['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException('file upload error');
            }

            if ($this->uploadedFile['size'] > self::MAX_FILE_SIZE) {
                throw new RuntimeException('file is too large');
            }

            //validate is image
            if (!is_array(getimagesize($this->uploadedFile['tmp_name']))) {
                throw new InvalidArgumentException('file is not an image');
            }

            $fileInfo = pathinfo($this->uploadedFile['name']);
            $this->uploadedFile['name'] = PathHelper::escapeFilePath($fileInfo['filename']) . '.' . $fileInfo['extension'];
        } catch (Exception $exception) {
            $this->handleError($exception);
        }
    }

    private function handleError(Exception $exception): void
    {
        $this->formView->setError($exception->getMessage());
        $this->renderMainView();
    }

    private function renderMainView(): void
    {
        die($this->formView->getFormView(FileUploaderAbstract::getPossibleFileUploadMethods()));
    }
    
    private function save(string $thumbnail): bool
    {
        if (!isset($this->handler)) {
            throw new InvalidArgumentException('handler can not be null');
        }

        return $this->handler->saveFile($thumbnail, $this->uploadedFile['name'], $this->directory);
    }
}
