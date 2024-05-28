<?php

namespace Core\Documents\Excel\Types;
use Core\Documents\Excel\ExcelDocument;
use Core\Templates\Excel\Types\PostExcelTemplate;
use Core\DataSets\DataSet;

/**
 * Класс документа Почтового реестра
 * Class PostExcelDocument
 * @package Core\Documents\Excel
 */
class PostExcelDocument extends ExcelDocument
{
    /** Инициализация документа */
    protected function initDocument() : void
    {
        $this->template = new PostExcelTemplate();
    }

    /**
     * Получение данных провайдера
     * @throws \Exception
     */
    protected function getProviderData() : array
    {
        try {
            $providerData = $this->provider->getPostData();
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
            'fio'			=>'string',
            'sudName'		=>'string',
            'sudAddress'	=>'string',
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
        $dataSets = $this->groupByAdresat($dataSets);
        $dataSets = $this->chunkGroupsBySize($dataSets, 6);

        return $this->createTemplateEntities($dataSets);
    }

    /**
     * Группировать по судам
     * @param array $dataSets
     * @return array
     */
    protected function groupByAdresat(array $dataSets) : array
    {
        $suds = array_unique(array_column(
            $dataSets,
            'sudName'
        ));

        $adresatGroupedDataSets = [];

        foreach ($suds as $sud) {
            $adresatGroupedDataSets[] = array_values(
                array_filter(
                    $dataSets,
                    fn($post) => $post['sudName'] === $sud
                )
            );
        }

        return $adresatGroupedDataSets;
    }

    /**
     * Разбить группированные по адресату наборы на части заданного размера
     * @param array $adresatGroupedDataSets
     * @param int $size
     * @return array
     */
    protected function chunkGroupsBySize(array $adresatGroupedDataSets, int $size) : array
    {
        $chunkedDataSets = [];

        foreach ($adresatGroupedDataSets as $adresatGroup) {
            if (count($adresatGroup) > $size) {
                $adresatGroup = array_chunk($adresatGroup, $size);

                $chunkedDataSets = [...$chunkedDataSets, ...$adresatGroup];
            } else {
                $chunkedDataSets[] = $adresatGroup;
            }
        }

        return $chunkedDataSets;
    }

    /**
     * Привести сгруппированные элементы к строковому виду для реестра
     * @param array $adresatGroupedDataSets
     * @return array
     */
    protected function createTemplateEntities(array $adresatGroupedDataSets) : array
    {
        $entitiesDataSets = [];

        foreach ($adresatGroupedDataSets as $adresatGroup) {
            $entitiesDataSets[] = [
                'numbers' => implode(
                    "\n",
                    array_column($adresatGroup, 'number')
                ),
                'fios' => implode(
                    "\n",
                    array_column($adresatGroup, 'fio')
                ),
                'adresat'       => $adresatGroup[0]['sudName'],
                'addressline'   => $adresatGroup[0]['sudAddress'],
                'mass'          => ROUND(count($adresatGroup) * 0.11,2),
                "ordernum"      => "",
                "mailtype"      => "3",
                "indexfrom"     => "650066",
                "noreturn"      => 0,
                "envelopetype"  => "C4",
                "paymentmethod" => "O",
            ];
        }

        return $entitiesDataSets;
    }

}