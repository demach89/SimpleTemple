<?php

namespace Core\Documents\Excel;
use Core\Documents\Document;
use Core\Helpers\InputFilter;
use Core\Templates\Excel\ExcelTemplate;

/**
 * Базовый класс Excel-документа
 * Class RegistryDocument
 * @package Core\Documents
 */
abstract class ExcelDocument extends Document
{
    use InputFilter;

    protected ExcelTemplate $template;


    public function __construct(array $portfolio, string $provider, string $target='all')
    {
        parent::__construct($portfolio, $provider, $target);

        $this->initDocument();
    }

    /**
     * Создать документ соответствующей классу специфики
     * @param array $data
     * @return string
     */
    protected function createDocument(array $data): string
    {
        try {
            $docPath = $this->template
                ->create(
                    $data,
                    $this->portfolio['name']
                );
        } catch (\Throwable $e) {
            throw new \Exception("\n" . static::class . "|" . __METHOD__ . "|" . "Ошибка создания шаблона\n" . $e->getMessage());
        }

        return $docPath;
    }
}