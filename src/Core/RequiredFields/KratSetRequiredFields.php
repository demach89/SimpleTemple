<?php

namespace Core\RequiredFields;


readonly class KratSetRequiredFields extends Required
{
    public function __construct(
            public string   $calcDate,
            public string   $putDate,
            public float    $loan,
            public float    $dayRate,
            public float    $paysOP,
            public float    $paysPeny,
            public float    $endOD,
            public float    $endOP,
            public float    $endPeny,
            public float    $endPerc,
            public float    $cessionODRest,
            public float    $cessionOPRest,
            public float    $cessionPenyRest,
            public string   $cessionDate,
            public int      $daysPastAfterCessionToCalcDate,
    ) {}
}

