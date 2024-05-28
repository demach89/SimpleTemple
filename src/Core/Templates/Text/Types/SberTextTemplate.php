<?php

namespace Core\Templates\Text\Types;
use Core\Templates\Text\TextTemplate;

/**
 * Шаблон "Сбер-реестр"
 * Class SberTextTemplate
 * @package Core\Text\Types
 */
class SberTextTemplate extends TextTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->docType = 'sber';
    }

    /**
     * Инициализация специфики шаблона
     * @param array $data
     * @param string $portfolioName
     * @return void
     */
    protected function init(array $data, string $portfolioName) : void
    {
        $this->data = $data;

        $this->docPath .= "/{$this->docType}.txt";

        $this->isInit = true;
    }
}