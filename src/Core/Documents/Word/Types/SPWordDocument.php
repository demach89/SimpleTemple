<?php

namespace Core\Documents\Word\Types;
use Core\Documents\Word\WordDocument;
use Core\Templates\Word\Types\SPWordTemplate;
use Core\DataSets\DataSet;

/**
 * Класс документа "Судебный приказ"
 * Class SPWordDocument
 * @package Core\Entity\Documents\Types
 */
class SPWordDocument extends WordDocument
{
    /** Инициализация документа */
    protected function initDocument() : void
    {
        $this->word = new SPWordTemplate();
    }

    /**
     * Получение данных провайдера
     * @throws \Exception
     */
    protected function getProviderData() : array
    {
        try {
            $providerData = $this->provider->getSPData();
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
        $this->checkPutType($data['putType']);
    }

    /**
     * Поля документа
     * @return string[]
     */
    protected function RequiredFields() : array
    {
        return [
            'number'			=>'string',
            'fio'				=>'string',
            'birthDate'			=>'string',
            'birthPlace'		=>'string',
            'addressReg'		=>'string',
            'docNum'			=>'string',
            'docContent'		=>'string',
            'docBeginDate'		=>'string',
            'dogNumber'			=>'string',
            'putDate'			=>'string',
            'closeDate'			=>'string',
            'loan'				=>'float',
            'psk'				=>'float',
            'daysCount'			=>'int',
            'prosrDate'			=>'string',
            'prosrCount'		=>'int',
            'calcDate'			=>'string',
            'paysOD'			=>'float',
            'paysOP'			=>'float',
            'paysPeny'			=>'float',
            'paysAll'			=>'float',
            'endODwoProsr'		=>'float',
            'endODProsr'		=>'float',
            'endOD'				=>'float',
            'endOP'				=>'float',
            'endPeny'			=>'float',
            'endAll'			=>'float',
            'sudName'			=>'string',
            'sudNameRP'			=>'string',
            'sudAddress'		=>'string',
            'userName'			=>'string',
            'dayRate'			=>'float',
            'restAllGos'		=>'float',
            'cessionODRest'		=>'float',
            'cessionOPRest'		=>'float',
            'cessionPenyRest'	=>'float',
            'gos'				=>'float',
        ];
    }

    protected function prepareDocumentDataSet(array $data) : DataSet
    {
        try {
            $dataSet = $this->prepareBasicDataSet($data);

            $dataSet = $dataSet
                ->correctProsrCostFields()
                ->addPercSumSet()
                ->addGosSet($this->portfolio['cessionDate'])
                ->addKratSet($this->portfolio['cessionDate'])
                ->addCessionSet($this->portfolio['cessionDate'])
                ->addUserSet()
                ->addClientNameDeCase()
            ;
        } catch (\Throwable $e) {
            throw new \Exception("\n" . static::class . "|" . __METHOD__ . "|" . "Ошибка установки данных\n" . $e->getMessage());
        }

        return $dataSet;
    }
}