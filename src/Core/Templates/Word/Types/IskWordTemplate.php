<?php

namespace Core\Templates\Word\Types;
use Core\Validators\Validator;
use Core\Templates\Word\WordTemplate;

/**
 * Word-шаблон документа "Иск"
 * Class SP
 * @package Core\Word\Types
 */
class IskWordTemplate extends WordTemplate
{
    public function __construct()
    {
        parent::__construct();

        $this->docType = 'isk';
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

            $this->templatePath .= "/{$this->docType}_{$data['putType']}_{$portfolioName}.docx";

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
        $this->data['loan']         = number_format($this->data['loan'], 2);
        $this->data['psk']          = number_format($this->data['psk'], 3);
        $this->data['paysAll']      = number_format($this->data['paysAll'], 2);
        $this->data['paysOD']       = number_format($this->data['paysOD'], 2);
        $this->data['paysOP']       = number_format($this->data['paysOP'], 2);
        $this->data['paysPeny']     = number_format($this->data['paysPeny'], 2);
        $this->data['endAll']       = number_format($this->data['endAll'], 2);
        $this->data['endOD']        = number_format($this->data['endOD'], 2);
        $this->data['endODwoProsr'] = number_format($this->data['endODwoProsr'], 2);
        $this->data['endODProsr']   = number_format($this->data['endODProsr'], 2);
        $this->data['endOP']        = number_format($this->data['endOP'], 2);
        $this->data['endPeny']      = number_format($this->data['endPeny'], 2);
        $this->data['kratSum']      = number_format($this->data['kratSum'], 2);
        $this->data['paysPerc']     = number_format($this->data['paysPerc'], 2);
        $this->data['endPerc']      = number_format($this->data['endPerc'], 2);
        $this->data['gos']          = number_format($this->data['gos'], 2);
        $this->data['iskCost']      = number_format($this->data['iskCost'], 2);
    }
}
