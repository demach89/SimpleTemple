<?php

namespace Core\Templates\Word\Types;
use Core\Templates\Word\WordTemplate;

/**
 * Word-шаблон документа "ЗАЯВЛЕНИЕ о возбуждении исполнительного производства "
 * Class VZISK
 * @package Core\Word\Types
 */
class VziskWordTemplate extends WordTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->docType = 'vzisk';
    }

    /**
     * Инициализация специфики шаблона
     * @param array{} $data
     * @param string $portfolioName
     * @return void
     */
    protected function init(array $data, string $portfolioName) : void
    {
        try {
            $this->data = $data;

            $this->templatePath .= "/{$this->docType}_{$portfolioName}.docx";

            $this->docPath .= "/{$data['number']}_{$data['fio']}_{$data['dogNumber']}_{$this->docType}.docx";
            $this->pdfPath .= "/{$data['number']}_{$data['fio']}_{$data['dogNumber']}_{$this->docType}.pdf";

            $this->setNumericDataFormat();

            $this->isInit = true;
        } catch (\Throwable $e) {
            die("Ошибка инициализации документа: {$e->getMessage()}\n");
        }
    }

    /**
     * Определить форматирование чисел с плавающей точкой для документа
     */
    protected function setNumericDataFormat() : void
    {
        $this->data['restAllGP'] = number_format($this->data['restAllGP'], 2);
        $this->data['restBySud'] = number_format($this->data['restBySud'], 2);
    }
}