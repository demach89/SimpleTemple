<?php

namespace Core\Toolkits;
use function morphos\Russian\inflectName;

require_once __DIR__ . "/../env.php";

trait Toolkit
{
    /**
     * Определить размер госпошлины по сумме остатков ОД, ОП, ШП
     * @param float $endAll
     * @return float
     */
    public function calcGP(float $endAll): float
    {
        $gos = 0.0;

        if ($endAll <= 10000) {
            $gos = 200;
        } elseif ($endAll <= 20000) {
            $gos = ($endAll * 4 / 100) / 2;
        } elseif ($endAll <= 100000) {
            $gos = (800 + (($endAll - 20000) * 3 / 100)) / 2;
        } elseif ($endAll <= 200000) {
            $gos = (3200 + (($endAll - 100000) * 2 / 100)) / 2;
        } elseif ($endAll <= 1000000) {
            $gos = (5200 + (($endAll - 200000) * 1 / 100)) / 2;
        } else {
            $gos = (13200 + (($endAll - 1000000) * 0.5 / 100)) / 2;
        }

        if ($gos > 60000) {
            $gos = 60000;
        } elseif ($gos < 200) {
            $gos = 200;
        }

        return ROUND($gos, 2);
    }

    /**
     * Склонение слов, фраз в нужном падеже
     * @param string $str
     * @param string $deCase [именительный, родительный, дательный, винительный, творительный, предложный]
     * @return string
     * @throws \Exception
     */
    public function declension(string $str, string $deCase): string
    {
        try {
            $strPadezh = inflectName($str, $deCase);

            return $strPadezh ?: $str;
        } catch (\Throwable $e) {
            exit();
        }
    }

    /**
     * Разница дат в формате ДД.ММ.УУУУ
     * @param string $date1
     * @param string $date2
     * @return int
     */
    public function daysDiff(string $date1, string $date2): int
    {
        $targetDate1 = strtotime($date1);
        $targetDate2 = strtotime($date2);

        $diff = ABS($targetDate1 - $targetDate2);

        return ($diff / 60 / 60 / 24 + 1);
    }

    /**
     * Создание ассоциативного массива из набора значений, ключей
     * @param array $raw
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public function arrayExtractRawToAssoc(array $raw, array $fields): array
    {
        if (count($raw) !== count($fields)) {
            throw new \Exception("Извлечение массива в ассоциативный не удалось - размерность не совпадает\n");
        }

        $assoc = [];

        foreach ($raw as $num => $val) {
            $field = $fields[$num];

            $assoc[$field] = $val;
        }

        return $assoc;
    }
}