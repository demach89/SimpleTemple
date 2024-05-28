<?php

namespace Core\Providers;

/**
 * Класс получения данных провайдером Google
 */
class GoogleRequest
{
    public function __construct(
        protected string $app,
        protected string $reqType,
        protected string $reqTarget='all'
    ) {}

    /** Инициализация приложения */
    public function getData() : array
    {
        try {
            $dataRaw = $this->prepareRequest();
        } catch (\Throwable $e) {
            echo $e->getMessage();
            exit(0);
        }

        if (!array_key_exists('result', $dataRaw)) {
            die("No result data");
        }

        return $dataRaw['result'];
    }

    /**
     * Получить Google-данные
     * @return array
     * @throws \JsonException|\Exception
     */
    private function prepareRequest() : array
    {
        try {
            $response = $this->sendRequest();
        } catch (\Throwable $e) {
            throw new \Exception(__CLASS__ . "|" . __FUNCTION__  . "|Ошибка получения данных|{$e->getMessage()}");
        }

        if (!$response) {
            throw new \Exception(__CLASS__ . "|" . __FUNCTION__  . "|Нет данных");
        }

        return $response;
    }

    /**
     * Запрос данных от Google
     * @return array
     * @throws \JsonException
     */
    private function sendRequest() : array
    {
        $requestData = [
            'googleReqType'     => $this->reqType,
            'googleReqTarget'   => $this->reqTarget,
        ];

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'content' => json_encode($requestData, JSON_THROW_ON_ERROR),
                'header'  => implode("\r\n", $headers),
            ]
        ];

        $context = stream_context_create( $options );
        $result = file_get_contents( $this->app, false, $context );

        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }
}