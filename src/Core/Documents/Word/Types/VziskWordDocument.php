<?php

namespace Core\Documents\Word\Types;
use Core\Documents\Word\WordDocument;
use Core\Templates\Word\Types\VziskWordTemplate;
use Core\DataSets\DataSet;

/**
 * Класс документа "Взыск"
 * Class VziskWordDocument
 * @package Core\Entity\Documents\Types
 */
class VziskWordDocument extends WordDocument
{
    /** Инициализация документа */
    protected function initDocument() : void
    {
        $this->word = new VziskWordTemplate();
    }

    /**
     * Получение данных провайдера
     * @throws \Exception
     */
    protected function getProviderData() : array
    {
        try {
            $providerData = $this->provider->getVziskData();
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
        $this->checkRequiredFieldsSingleSet($data);
    }

    /**
     * Поля документа
     * @return string[]
     */
    protected function RequiredFields() : array
    {
        return [
            'number'			=> 'number',
            'fio'				=> 'string',
            'birthDate'			=> 'string',
            'addressReg'		=> 'string',
            'dogNumber'			=> 'string',
            'decisionDate'		=> 'string',
            'decisionNumber'	=> 'string',
            'sudName'			=> 'string',
            'restAllGP'			=> 'number',
            'restBySud'			=> 'number',
        ];
    }

    protected function prepareDocumentDataSet(array $data) : DataSet
    {
        try {
            $dataSet = $this->prepareBasicDataSet($data);

            $dataSet = $dataSet
                ->addClientNameDeCase()
                ->addSudShortName()
            ;
        } catch (\Throwable $e) {
            throw new \Exception("\n" . static::class . "|" . __METHOD__ . "|" . "Ошибка установки данных\n" . $e->getMessage());
        }

        return $dataSet;
    }
}