<?php

namespace Core;

use Core\Documents\Document;
use Core\Documents\Excel\Types\PostExcelDocument;
use Core\Documents\Text\Types\SberTextDocument;
use Core\Documents\Word\Types\IskWordDocument;
use Core\Documents\Word\Types\SPWordDocument;
use Core\Documents\Word\Types\VziskWordDocument;

class TemplateCreator
{
    protected Document $doc;
    protected string $docClass;

    public function __construct(
        protected array  $portfolio,
        protected string $docType,
        protected string $target,
        protected string $provider = 'Google',
    ) {
        try {
            $this->docClass = match ($this->docType) {
                'sp'    => SPWordDocument::class,
                'isk'   => IskWordDocument::class,
                'vzisk' => VziskWordDocument::class,
                'sber'  => SberTextDocument::class,
                'post'  => PostExcelDocument::class,
                default => throw new \Exception('Неверный тип документа'),
            };
        } catch (\Throwable $e) {
            die("Ошибка инициализации параметров|{$e->getMessage()}");
        }
    }

    /**
     * Создать документ по шаблону
     * @return $this
     * @throws \Exception
     */
    public function create() : self
    {
        try {
            $this->doc = (new $this->docClass(
                $this->portfolio,
                $this->provider,
                $this->target,
            ))->create();
        } catch (\Throwable $e) {
            die("Ошибка создания документа|{$e->getMessage()}");
        }

        return $this;
    }

    /**
     * Получить путь созданного документа
     * @return string
     * @throws \Exception
     */
    public function getDocPath() : string
    {
        if (
            !(isset($this->doc)) || !file_exists($this->doc->getDocPath())
        ) {
            die("При передаче документа возникла ошибка, попробуйте снова");
        }

        return $this->doc->getDocPath();
    }
}
