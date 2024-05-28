<?php

namespace Core\Documents\Text;
use Core\Documents\Document;
use Core\Templates\Text\TextTemplate;
use Core\Helpers\InputFilter;

/**
 * Базовый класс Text-документа
 * Class RegistryDocument
 * @package Core\Documents
 */
abstract class TextDocument extends Document
{
    use InputFilter;

    /** @var TextTemplate Word-документ */
    protected TextTemplate $template;


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