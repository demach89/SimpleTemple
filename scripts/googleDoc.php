<?php

/**
 * Скрипт подготовки документа по условиям:
 * - предопределённым (иной скрипт)
 * - со входящим запросом
 */

require_once __DIR__ . "/../headers.php";

use Core\Validators\AuthValidator;
use Core\TemplateCreator;

try {
    $input = [
        'portfolio' => $input['portfolio']  ?? $_GET['portfolio']   ?? "",
        'docType'   => $input['docType']    ?? $_GET['docType']     ?? "",
        'target'    => $input['target']     ?? $_GET['target']      ?? "",
        'provider'  => $input['provider']   ?? $_GET['provider']    ?? "Google",
    ];

    AuthValidator::check($input);

    $input['portfolio'] = PORTFOLIOS[ $input['portfolio'] ];

    $docPath = (new TemplateCreator(
        $input['portfolio'],
        $input['docType'],
        $input['target'],
        $input['provider'],
    ))->create()->getDocPath();

    return $docPath;
} catch (\Throwable $e) {
    error_log($e->getMessage());
    die('Неустранимая ошибка');
}
