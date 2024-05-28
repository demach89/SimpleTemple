<?php

namespace Core\RequiredFields;

readonly abstract class Required
{
    public function get(): array
    {
        return get_object_vars($this);
    }
}