<?php

namespace Core\Documents\Word\Types;
use Core\Documents\Word\WordDocument;
use Core\Templates\Word\Types\IskWordTemplate;
use Core\DataSets\DataSet;

/**
 * Класс документа "Иск"
 * Class IskWordDocument
 * @package Core\Entity\Documents\Types
 */
class IskWordDocument extends WordDocument
{
    /** Инициализация документа */
    protected function initDocument() : void
    {
        $this->word = new IskWordTemplate();
    }

    /**
     * Получение данных провайдера
     * @throws \Exception
     */
    protected function getProviderData() : array
    {
        try {
            $providerData = $this->provider->getIskData();
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
            'number'			=> 'number',
            'fio'				=> 'string',
            'dogNumber'			=> 'string',
            'region'			=> 'string',
            'putType'			=> 'string',
            'putDate'			=> 'string',
            'closeDate'			=> 'string',
            'loan'				=> 'number',
            'psk'				=> 'number',
            'daysCount'			=> 'number',
            'paysOD'			=> 'number',
            'paysOP'			=> 'number',
            'paysPeny'			=> 'number',
            'paysAll'			=> 'number',
            'endODwoProsr'		=> 'number',
            'endODProsr'		=> 'number',
            'endOD'				=> 'number',
            'endOP'				=> 'number',
            'endPeny'			=> 'number',
            'endAll'			=> 'number',
            'sudNamePrikazRP'	=> 'string',
            'prikazCancelDate'	=> 'string',
            'calcDate'			=> 'string',
            'iskDate'			=> 'string',
            'userName'			=> 'string',
            'sudNameIsk'		=> 'string',
            'sudNameIskRP'		=> 'string',
            'sudAddressIsk'		=> 'string',
            'sudSiteIsk'		=> 'string',
            'deliveryType'		=> 'string',
            'SHPI'				=> 'string',
            'gos'				=> 'number',
            'status'			=> 'string',
            'statusDate'		=> 'string',
            'tribunalDate'		=> 'string',
            'tribunalNumber'	=> 'string',
            'birthDate'			=> 'string',
            'birthPlace'		=> 'string',
            'addressReg'		=> 'string',
            'docNum'			=> 'string',
            'docContent'		=> 'string',
            'docBeginDate'		=> 'string',
            'prosrDate'			=> 'string',
            'prosrCount'		=> 'number',
            'dayRate'			=> 'number',
            'cessionODRest'		=> 'number',
            'cessionOPRest'		=> 'number',
            'cessionPenyRest'	=> 'number',
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
                ->addIskCost()
            ;
        } catch (\Throwable $e) {
            throw new \Exception("\n" . static::class . "|" . __METHOD__ . "|" . "Ошибка установки данных\n" . $e->getMessage());
        }

        return $dataSet;
    }
}