<?php

namespace Core\Templates;

abstract class Template
{
    protected array  $data;
    protected string $docType;
    protected string $rootPath;
    protected string $docPath;
    protected string $pdfPath;
    protected string $templatePath;

    protected bool $isInit = false;


    abstract protected function createDoc() : void;

    public function __construct()
    {
        $this->rootPath     = __DIR__ . '/../../../';
        $this->templatePath = $this->rootPath . TEMPLATES_DIR;
        $this->docPath      = $this->rootPath . ONLINE_DOCS_DIR;
        $this->pdfPath      = $this->docPath;
    }

    public function create(array $data, string $portfolioName) : string
    {
        $this->init($data, $portfolioName);

        $this->removePrevious($this->docPath);
        $this->removePrevious($this->pdfPath);

        $this->createDoc();

        return $this->docPath;
    }

    protected function removePrevious(string $path) : void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    protected function checkInit() : void
    {
        try {
            $check = 1/$this->isInit;
        } catch (\Throwable $e) {
            die(static::class . "|" . __METHOD__ . "|" . "Отсутствует инициализация экземпляра\n");
        }
    }

}