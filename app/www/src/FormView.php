<?php

/**
 * Class FormView
 */
class FormView
{
    /**
     * @var string
     */
    private string $error = '';

    /**
     * @return string
     */
    private function getErrorHtml(): string
    {
        if (empty($this->error)) {
            return '';
        }

        return '<span style="background-color: red; padding: 2px;">' . $this->error . '</span><br><br>';
    }

    /**
     * @param array $uploadMethods
     *
     * @return string
     */
    public function getFormView(array $uploadMethods): string
    {
        return '<!DOCTYPE html>
                <html>
                <body>
                ' . $this->getErrorHtml() . '                
                <form action="/index.php?action=save" method="post" enctype="multipart/form-data">
                    <label for="image">Choose a file:</label>
                    <input type="file"
                           id="gfx" name="image"
                           accept="image/png, image/jpeg">
                    <br><br>
                    <label for="service">Choose a service:</label>
                ' . $this->getSelectUploadMethodsHtml($uploadMethods) . '
                    <br><br>
                    <label for="directory">Directory name:</label>
                    <input type="text" name ="directory">
                    <input type="submit" value="Submit">
                </form>
                
                </body>
                </html>';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getUploadMethodHtml(string $name): string
    {
        return '<option value="' . $name . '">' . $name . '</option>';
    }

    /**
     * @param array $uploadMethods
     *
     * @return string
     */
    private function getSelectUploadMethodsHtml(array $uploadMethods): string
    {
        $html = '<select name="service" id="service">';

        foreach ($uploadMethods as $method) {
            $html .= $this->getUploadMethodHtml($method);
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * @param string $thumbnail
     *
     * @return string
     */
    public function getFormViewAfterUpload(string $thumbnail): string
    {
        $imgSrc = "<img src='data:image/png;base64," . base64_encode($thumbnail) . "'>";

        return '<!DOCTYPE html>
                <html>
                <body>
                
                <h2>Image saved</h2>
                
                <div>' . $imgSrc . '</div>
                
                <a href="/">Back to form</a>
                </body>
                </html>
                ';
    }

    /**
     * @param string $errorMessage
     */
    public function setError(string $errorMessage): void
    {
        $this->error = $errorMessage;
    }
}