<?php

namespace Core\Documents\Word;
use Core\Documents\Document;
use Core\Templates\Word\WordTemplate;

/**
 * Базовый класс Word-документа
 * Class WordDocument
 * @package Core\Documents
 */
abstract class WordDocument extends Document
{
    /** @var WordTemplate Word-документ */
    protected WordTemplate $word;


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
            $docPath = $this->word
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