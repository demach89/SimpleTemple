<?php

namespace Core\Templates\Word;
use Core\Templates\Template;
use PhpOffice\PhpWord\TemplateProcessor;


/**
 * Word-шаблоны документов
 * Class Word
 * @package Core\Word
 */
abstract class WordTemplate extends Template
{
    protected TemplateProcessor $templateProcessor;

    /** Инициализация специфики шаблона */
    abstract protected function init(array $data, string $portfolioName) : void;


    /**
     * Создание документа по шаблону
     * @return void
     */
    protected function createDoc() : void
    {
        $this->checkInit();

        try {
            $this->templateProcessor = new TemplateProcessor($this->templatePath);

            $this->setTemplateValues();

            $this->templateProcessor->saveAs($this->docPath);

        } catch (\Throwable $e) { die("Ошибка шаблона: {$e->getMessage()}\n"); }
    }

    /**
     * Создание PDF-документа по шаблону
     * ВАЖНО: шрифт шаблона должен быть Calibri
     * @return void
     */
    protected function createPDF() : void
    {
        try {
            $this->createDoc();

            \PhpOffice\PhpWord\Settings::setPdfRendererPath(__DIR__ . '/../../../../vendor/dompdf');   // ./PDFWord/TCPDF-main
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');                                  // TCPDF

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($this->docPath);
            $phpWord->setDefaultFontName('DejaVu Sans');

            $phpWord->save($this->pdfPath, 'PDF');

        } catch (\Throwable $e) { die("Ошибка шаблона: {$e->getMessage()}\n"); }
    }

    /**
     * Заполнение меток шаблона
     * @return void
     */
    protected function setTemplateValues() : void
    {
        $fields = array_keys($this->data);

        foreach ($fields as $field) {
            $this->templateProcessor->setValue("\${{$field}}", $this->data[$field]);
        }
    }
}