<?php

namespace Core\Documents\Text\Types;
use Core\Documents\Text\TextDocument;
use Core\Templates\Text\Types\SberTextTemplate;
use Core\DataSets\DataSet;

/**
 * Класс документа Сбер-выгрузки
 * Class SberRegistry
 * @package Core\Entity\Documents\Types
 */
class SberTextDocument extends TextDocument
{
    /** Инициализация документа */
    protected function initDocument() : void
    {
        $this->template = new SberTextTemplate();
    }

    /**
     * Получение данных провайдера
     * @throws \Exception
     */
    protected function getProviderData() : array
    {
        try {
            $providerData = $this->provider->getSberData();
        } catch (\Throwable $e) {
            throw new \Exception(static::class . "|" . __METHOD__ . "|" . "Ошибка получения данных от провайдера: " . $e->getMessage() . "\n");
        }

        return $providerData;
    }

    /**
     * Проверка данных на полноту
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function checkRequiredFields(array $data) : void
    {
        $this->checkRequiredFieldsArraySet($data);
    }

    /**
     * Поля документа
     * @return string[]
     */
    protected function RequiredFields() : array
    {
        return [
            'number'		=>'string',
            'dogNumber'		=>'string',
            'fio'			=>'string',
            'restAllGos'	=>'number',
        ];
    }

    protected function prepareDocumentDataSet(array $data) : DataSet
    {
        try {
            $needlesDataSet = $this->RequiredDataSets($data, $this->target, 'number');

            $templateData = $this->createDocumentData($needlesDataSet);

            $dataSet = $this->prepareBasicDataSet($templateData);

        } catch (\Throwable $e) {
            throw new \Exception("\n" . static::class . "|" . __METHOD__ . "|" . "Ошибка установки данных\n" . $e->getMessage());
        }

        return $dataSet;
    }

    protected function createDocumentData(array $dataSets) : array
    {
        $templateDataSets = [];

        foreach ($dataSets as $dataSet) {
            $templateDataSets[] = [
                "number"    => $dataSet['dogNumber'],
                "fio"       => $dataSet['fio'],
                "address"   => "",
                "info"      => $dataSet['fio'],
                "restAllGos"=> number_format($dataSet['restAllGos'], 2, '.', ''),
            ];
        }

        return $templateDataSets;
    }
}