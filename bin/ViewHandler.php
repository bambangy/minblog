<?php

namespace bin;

use Exception;

class ViewHandler
{
    protected $viewsPath;
    protected $layout;
    protected $pageTitle;

    public function __construct($viewsPath = 'views/')
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render($view, $data = [])
    {
        $viewFile = $this->viewsPath . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: $viewFile");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If layout is set, render it with the content
        if ($this->layout) {
            include __DIR__ . '/../' . $this->layout;
        } else {
            echo $content;
        }
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }
}
