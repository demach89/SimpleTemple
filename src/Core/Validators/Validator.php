<?php

namespace Core\Validators;

require_once __DIR__ . "/../env.php";

abstract class Validator
{
    /**
     * Проверить наличие типа выдачи и его легитимность
     * @param string $provider
     * @throws \Exception
     */
    protected static function provider(string $provider) : void
    {
       if (!in_array($provider, PROVIDERS, false)) {
           throw new \Exception("Некорретный тип провайдера\n");
       }
    }

    /**
     * Проверить наличие портфеля и его легитимность
     * @param array $input
     * @throws
     */
    protected static function portfolio(array $input) : void
    {
        if (
            !array_key_exists('portfolio', $input) ||
            !array_key_exists($input['portfolio'], PORTFOLIOS)
        ) {
            throw new \Exception("Некорретное имя портфеля\n");
        }
    }

    /**
     * Проверить наличие идентификатора
     * @param array $input
     * @throws
     */
    protected static function target(array $input) : void
    {
        if (
            !array_key_exists('target', $input)
        ) {
            throw new \Exception("Некорретный идентификатор\n");
        }
    }

    /**
     * Проверить наличие типа документа и его легитимность
     * @param array $input
     * @throws
     */
    protected static function docType(array $input) : void
    {
        if (
            !array_key_exists('docType', $input) ||
            !in_array($input['docType'], DOC_TYPES, false)
        ) {
            throw new \Exception("Некорретный тип документа\n");
        }
    }
}