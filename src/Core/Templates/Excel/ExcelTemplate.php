<?php

namespace Core\Templates\Excel;
use Core\Templates\Template;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Writer\Xls as XlsWriter;

/**
 * ExcelTemplate-шаблоны документов
 * Class ExcelTemplate
 * @package Core\ExcelTemplate
 */
abstract class ExcelTemplate extends Template
{
    protected Spreadsheet $templateProcessor;
    protected array  $colsLetters;

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

            $this->colsLetters  = $this->defineColsLetters($this->data[0]);

        } catch (\Throwable $e) {
            die("Ошибка инициализации документа: {$e->getMessage()}\n");
        }
    }

    /**
     * Создание документа по шаблону
     * @return void
     */
    protected function createDoc() : void
    {
        $this->checkInit();

        try {
            $this->templateProcessor = new Spreadsheet();

            $this->setTemplateValues();

            (new XlsWriter($this->templateProcessor))->save($this->docPath);

        } catch (\Throwable $e) { die("Ошибка шаблона: {$e->getMessage()}\n"); }
    }

    /**
     * Определить названия столбцов для заданного набора заголовков
     * @param array $columns
     * @return array
     */
    protected function defineColsLetters(array $columns) : array
    {
        $letters = [];

        foreach (array_keys($columns) as $num => $val) {
            $letters[] = chr((int)$num + 321);
        }

        return $letters;
    }

    /**
     *  Создать документ
     */
    protected function setTemplateValues() : void
    {
        try {
            $reader = new XlsReader();
            $this->templateProcessor = $reader->load($this->templatePath);

            $sheet = $this->templateProcessor->getActiveSheet();

            $rowsCounter = 2;
            $colsCounter = 0;

            foreach ($this->data as $dataSet) {
                foreach ($dataSet as $field) {
                    $sheet->setCellValue($this->colsLetters[$colsCounter] . ($rowsCounter), $field);
                    $colsCounter++;
                }
                $rowsCounter++;
                $colsCounter = 0;
            }

            $this->setDefaultFormat();

        } catch (\Throwable $e) {
            error_log($e->getMessage());
            echo "Ошибка создания почтового реестра!" . PHP_EOL;
            exit();
        }
    }

    /** Задать дефолтные параметры листа */
    protected function setDefaultFormat() : void
    {
        $rowsCount = count($this->data)+1;

        $this->setWrapText($rowsCount);
        $this->setRowHeight($rowsCount);
        $this->setAlignment($rowsCount);
        //$this->setBorder($rowsCount);
    }
    /** Установить возможность переноса текста */
    protected function setWrapText(int $rowsCount) : void
    {
        $dataRowsRange = 'A2:'.end($this->colsLetters).$rowsCount;

        $this->templateProcessor->getActiveSheet()->getStyle($dataRowsRange)->getAlignment()->setWrapText(true);
    }

    /** Установить автовысоту строк */
    protected function setRowHeight(int $rowsCount) : void
    {
        for ($rowNum=1; $rowNum<=$rowsCount; $rowNum++){
            $this->templateProcessor->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(-1);
        }
    }

    /** Установить выравнивание текста */
    protected function setAlignment(int $rowsCount) : void
    {
        $dataRowsRange = 'A2:'.end($this->colsLetters).$rowsCount;

        $this->templateProcessor->getActiveSheet()->getStyle($dataRowsRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $this->templateProcessor->getActiveSheet()->getStyle($dataRowsRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    }

    /** Установить горизонтальные межстрочные границы */
    protected function setBorder(int $rowsCount) : void
    {
        $dataRowsRange = 'A2:'.end($this->colsLetters).$rowsCount;

        $this->templateProcessor->getActiveSheet()->getStyle($dataRowsRange)->getBorders()->getHorizontal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}