<?php

namespace app\components;


class Template {

    const DEFAULT_LAYOUT = 'default';
    const TEMPLATE_FILES_EXTENTION = '.php';

    protected $templatesFolder;
    protected $layoutsFolder;
    protected $layoutName;
    protected $layoutContent;

    public $templateData;

    public function __construct() {
        $this->templatesFolder = __DIR__ . '/../views/';
        $this->layoutsFolder = __DIR__ . '/../views/layouts/';
    }

    public function setData(array $data) {
        $this->templateData = $data;
        return $this;
    }

    public function setLayout($layoutName) {
        $pathToLayoutFile = $this->layoutsFolder . $layoutName . self::TEMPLATE_FILES_EXTENTION;

        if (file_exists($pathToLayoutFile)) {
            $this->layoutName = $layoutName;
        }
    }

    public function getLayout() {
        return $this->layoutName ? $this->layoutName : self::DEFAULT_LAYOUT;
    }

    public function render($templatePath) {
        $pathToTemplateFile = $this->templatesFolder . $templatePath . self::TEMPLATE_FILES_EXTENTION;
        $this->getLayoutContent($pathToTemplateFile);
        $this->renderLayout();
    }

    protected function getLayoutContent($pathToTemplateFile) {
        if (file_exists($pathToTemplateFile)) {
            ob_start();
            include_once($pathToTemplateFile);
            $this->layoutContent = ob_get_contents();
            ob_end_clean();
        } else {
            throw new \Exception("Rendered file not exist: $pathToTemplateFile");
        }

    }

    protected function renderLayout() {
        $pathToLayout = $this->layoutsFolder . $this->getLayout() . self::TEMPLATE_FILES_EXTENTION;
        if (file_exists($pathToLayout)) {
            ob_start();
            include_once($pathToLayout);
            $content = ob_get_contents();
            ob_end_clean();

            echo $content;
        } else {
            throw new \Exception("Rendered layout file not exist: $pathToLayout");
        }

    }

}