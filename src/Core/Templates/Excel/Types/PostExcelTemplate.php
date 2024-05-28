<?php

namespace Core\Templates\Excel\Types;
use Core\Templates\Excel\ExcelTemplate;

/**
 * Шаблон документа "Почтовый реестр"
 * Class PostExcelTemplate
 * @package Core\ExcelTemplate\Types
 */
class PostExcelTemplate extends ExcelTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->docType = 'post';
    }

    /**
     * Инициализация специфики шаблона
     * @param array $data
     * @param string $portfolioName
     * @return void
     */
    protected function init(array $data, string $portfolioName) : void
    {
        parent::init($data, $portfolioName);

        $this->templatePath .= "/{$this->docType}.xls";
        $this->docPath .= "/{$this->docType}.xls";

        $this->isInit = true;
    }
}