<?php

/**
 * Скрипт подготовки документа типа "Иск"
 * - создает документ из шаблона
 * - возвращает полный путь документа
 */

$input = [
    'portfolio' => "viva1",
    'docType'   => "isk",
    'target'    => "Z010944176002",
    'provider'  => "Google",
];

$docPath = include("./googleDoc.php");

echo $docPath;
