<?php

namespace Core\Providers;

/**
 * Поставщик данных - Google
 * Class Google
 * @package Core\Providers
 */
class GoogleProvider extends Provider
{
    protected string $app;
    protected string $reqType;
    protected string $reqTarget;

    public function __construct(string $app, string $reqTarget = 'all')
    {
        $this->app       = $app;
        $this->reqTarget = $reqTarget;
    }

    public function getSPData() : array
    {
        $this->reqType = 'GoogleSud';

        return $this->getData()[0];
    }

    public function getIskData() : array
    {
        $this->reqType = 'GoogleIsk';

        return $this->getData()[0];
    }

    public function getVziskData() : array
    {
        $this->reqType = 'GoogleVzisk';

        return $this->getData()[0];
    }

    public function getSberData() : array
    {
        $this->reqType = 'GoogleSber';

        return $this->getData();
    }

    public function getPostData() : array
    {
        $this->reqType = 'GooglePost';

        return $this->getData();
    }

    /**
     * Запросить данные у источника
     * @return array
     */
    protected function getData() : array
    {
        return (new GoogleRequest(
            $this->app,
            $this->reqType,
            $this->reqTarget)
        )->getData();
    }
}