<?php

/**
 * Очистить каталог временных файлов
 * @return void
 */

require_once __DIR__ . "/../src/Core/env.php";

$tmpDir = __DIR__ . "/../" . ONLINE_DOCS_DIR;

if (file_exists($tmpDir)) {
    foreach (glob($tmpDir . '/*') as $file) {
        if (!stripos($file, 'gitignore')) {
            unlink($file);
        }
    }
}