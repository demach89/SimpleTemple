<?php

namespace Core\Documents;

use Core\DataSets\DataSet;
use Core\Providers\GoogleProvider;
use Core\Providers\Provider;

/**
 * Базовый класс документа
 * Class Documents
 * @package Core\Documents
 */
abstract class Document
{
    /** @var string Цель документа */
    protected string $target;

    /** @var array Данные портфеля */
    protected array  $portfolio;

    /** @var Provider Провайдер */
    protected Provider $provider;

    /** @var DataSet Набор данных документа */
    protected DataSet $dataSet;

    /** @var string Относительный путь конечного документа */
    protected string $docPath;

    /** Инициализация документа */
    abstract protected function initDocument() : void;

    abstract protected function getProviderData() : array;

    /** Поля документа @return array */
    abstract protected function RequiredFields() : array;

    /** Проверить наличие требуемых документом полей */
    abstract protected function checkRequiredFields(array $data) : void;

    /** Подготовить данные для шаблона потомком согласно своему типу */
    abstract protected function prepareDocumentDataSet(array $data) : DataSet;

    /** Создать документ потомком согласно своему типу */
    abstract protected function createDocument(array $data) : string;

    public function __construct(array $portfolio, string $provider, string $target='all')
    {
        $this->portfolio    = $portfolio;
        $this->target       = $target;
        $this->provider     = $this->initProvider($provider);
    }

    /** Инициализация провайдера */
    private function initProvider(string $provider) : Provider
    {
        return match ($provider) {
            'Google' => new GoogleProvider($this->portfolio['googleApp'], $this->target),
        };
    }

    /**
     * Создание документа
     * @return Document
     */
    public function create() : self
    {
        try {
            $providerData = $this->getProviderData();

            $this->checkRequiredFields($providerData);

            $data = $this->prepareDocumentDataSet($providerData)->get();

            $this->docPath =  $this->createDocument($data);

        } catch (\Throwable $e) { die($e->getMessage()); }

        return $this;
    }

    /**
     * Проверка данных на полноту
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function checkRequiredFieldsSingleSet(array $data) : void
    {
        try {
            foreach ($this->RequiredFields() as $field => $type) {
                if (!isset($data[$field])) {
                    throw new \Exception("отсутствует поле {$field}");
                }
            }
        } catch (\Throwable $e) {
            throw new \Exception(static::class . "|" . __METHOD__ . "|" . "Неполная инициализация: \n" . $e->getMessage());
        }
    }

    /**
     * Проверить типа выдачи на легитимность
     * @param string $putType
     * @throws \Exception
     */
    protected function checkPutType(string $putType) : void
    {
        if (
            !in_array($putType, PUT_TYPES)
        ) {
            throw new \Exception("Некорретный тип выдачи\n");
        }
    }

    /**
     * Проверка наборов данных на полноту
     * @param array $dataSets
     * @return void
     * @throws \Exception
     */
    protected function checkRequiredFieldsArraySet(array $dataSets) : void
    {
        try {
            foreach ($dataSets as $dataSet) {
                $this->checkRequiredFieldsSingleSet($dataSet);
            }
        } catch (\Throwable $e) {
            throw new \Exception(static::class . "|" . __METHOD__ . "|" . "Неполная инициализация: \n" . $e->getMessage());
        }
    }

    /**
     * Подготовка данных
     * @param array $providerData
     * @return DataSet
     * @throws \Exception
     */
    protected function prepareBasicDataSet(array $providerData) : DataSet
    {
        try {
            $dataSet = new DataSet($providerData, $this->portfolio);
        } catch (\Throwable $e) {
            throw new \Exception(static::class . "|" . __METHOD__ . "|" . "Ошибка установки данных\n" . $e->getMessage());
        }

        return $dataSet;
    }

    /** Получить относительный путь конечного документа @return string */
    public function getDocPath() : string
    {
        try {
            $check = 1/(isset($this->docPath));
        } catch (\Throwable $e) {
            die(static::class . "|" . __METHOD__ . "|" . "Отсутствует инициализация экземпляра\n");
        }

        return $this->docPath;
    }
}