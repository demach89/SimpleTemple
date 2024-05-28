<?php

require_once __DIR__ . "/../headers.php";

use Core\Validators\AuthValidator;
use Core\TemplateCreator;


try {
    $input = [
        'portfolio' => $_GET['portfolio']   ?? "viva1",
        'docType'   => $_GET['docType']     ?? "vzisk",
        'target'    => $_GET['target']      ?? "Z010944176002",
        'provider'  => $_GET['provider']    ?? "Google",
    ];

    AuthValidator::check($input);

    $input['portfolio'] = PORTFOLIOS[ $input['portfolio'] ];

    echo (new TemplateCreator(
        $input['portfolio'],
        $input['docType'],
        $input['target'],
        $input['provider'],
    ))->create()->getDocPath();

} catch (\Throwable $e) {
    error_log($e->getMessage());
    die($e->getMessage());
}



