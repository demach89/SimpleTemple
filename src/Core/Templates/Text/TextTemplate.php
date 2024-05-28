<?php

namespace Core\Templates\Text;
use Core\Templates\Template;

/**
 * Текстовые шаблоны документов
 * Class TextTemplate
 * @package Core\Text
 */
abstract class TextTemplate extends Template
{
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
            $this->setTemplateValues();
        } catch (\Throwable $e) { die("Ошибка шаблона: {$e->getMessage()}\n"); }
    }

    /**
     * Выгрузить реестр в TXT-файл
     */
    protected function setTemplateValues() : void
    {
        $fp = fopen($this->docPath, 'w');

        foreach ($this->data as $fields) {
            fwrite($fp, implode(';', $fields)."\n");
        }

        fclose($fp);

        $this->UTF8toANSI();
    }

    /**
     * Конвертация TXT-файла реестра из UTF-8 (по умолчанию) в ANSI
     */
    protected function UTF8toANSI() : void
    {
        if (file_exists($this->docPath)) {
            $content = file_get_contents($this->docPath);
            $content = iconv("UTF-8", "CP1251//TRANSLIT", $content);
            file_put_contents($this->docPath, $content);
        } else {
            echo "UTF8toANSI: Исходный файл не существует."; exit();
        }
    }
}