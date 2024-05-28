<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $input = [
        'portfolio' => $_GET['portfolio']   ?? "",
        'docType'   => $_GET['docType']     ?? "",
        'target'    => $_GET['target']      ?? "",
        'provider'  => $_GET['provider']    ?? "Google",
    ];

    $docPath = include(__DIR__ . "/scripts/googleDoc.php");

    header("Location: https://example.com/docs/{$docPath}");
}
