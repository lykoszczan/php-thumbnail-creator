<?php

require_once __DIR__ . '/vendor/autoload.php';

$form = new FormView();

if (isset($_GET['action']) && $_GET['action'] == 'save') {
    $file = $_FILES['image'];

    try {
        $app = new Application();
        $app->validateFile($file);

        $fileContent = file_get_contents($file['tmp_name']);
        $thumbnail = ImageHelper::createThumbnail($fileContent);

        $result = false;
        if ($thumbnail) {
            $app->setHandler($_POST['service']);
            $result = $app->save($thumbnail, $_FILES['image'], $_POST['directory']);

            if ($result) {
                die($form->getFormViewAfterUpload($thumbnail));
            } else {
                print($form->getErrorHtml('save failed'));
            }

        } else {
            print($form->getErrorHtml('cannot create thumbnail'));
        }
    } catch (Exception $exception) {
        print($form->getErrorHtml($exception->getMessage()));
    }
}

die($form->getFormView(FileUploaderAbstract::getPossibleFileUploadMethods()));