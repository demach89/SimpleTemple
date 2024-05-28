<?php

namespace Core\Validators;

require_once __DIR__ . "/../env.php";

final class AuthValidator extends Validator
{
    public static function check(array $input) : void
    {
        try {
            self::portfolio($input);
            self::docType($input);
            self::target($input);
        } catch (\Throwable $e) {
            die($e->getMessage());
        }
    }
}