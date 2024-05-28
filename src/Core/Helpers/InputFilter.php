<?php

namespace Core\Helpers;

/**
 * Хелпер для выборки запрошенных позиций договоров из общего массива
 * Запрошенные позиции поступают в виде строки вида: "1,3-5,12,3"
 * Запрошенные позиции представляются в виде диапазонов
 * Общий массив фильтруется по диапазонам
 * При отсутствии запрошенных позиций результат равен исходному
 */
trait InputFilter
{
    protected array $ranges=[];

    /**
     * Выбрать запрошенные элементы набора
     * @param array $dataSets
     * @param string $inputLine
     * @param string $inputField
     * @return array
     */
    public function RequiredDataSets(array $dataSets, string $inputLine="", string $inputField='number') : array
    {
        $this->createNumberRanges($inputLine);

        return match (count($this->ranges)) {
            0 => $dataSets,
            default => $this->filter($dataSets, $inputField),
        };
    }

            /**
             * Создать числовые диапазоны из запрошенных позиций
             * @param string $inputLine
             * @return void
             */
            protected function createNumberRanges(string $inputLine="") : void
            {
                if ($inputLine === "") {
                    return;
                }

                $inputLineParts = explode(
                    ',',
                    str_replace(' ', '', $inputLine)
                );

                foreach ($inputLineParts as $linePart) {
                    $this->checkForRange($linePart) ;
                    $this->addRange($linePart);
                }
            }

                    /**
                     * Проверить на наличие диапазона
                     * @param string $range
                     * @return void
                     */
                    protected function checkForRange(string $range) : void
                    {
                        if (!(preg_match('/^[1-9]\d*-[1-9]\d*$/u', $range) || preg_match('/^[1-9]\d*$/u', $range)) ){
                            die("Ошибка диапазона");
                        }
                    }

                    /**
                     * Создать диапазон
                     * @param string $range
                     */
                    protected function addRange(string $range) : void
                    {
                        $rangeParts = explode('-', $range);

                        $this->ranges[] =  match (count($rangeParts)) {
                            1 => [
                                'begin' => (int)$rangeParts[0],
                                'end'   => (int)$rangeParts[0],
                            ],
                            2 => [
                                'begin' => (int)$rangeParts[0],
                                'end'   => (int)$rangeParts[1],
                            ],
                            default => throw new \Error("Ошибка создания диапазона")
                        };
                    }


            /**
             * Фильтровать запрошенные элементы набора по диапазонам
             * @param array $dataSets
             * @param string $inputField
             * @return array
             */
            protected function filter(array $dataSets, string $inputField) : array
            {
                $filtered = [];

                foreach ($dataSets as $dataSet) {
                    foreach ($this->ranges as $range) {
                        if ($this->inRange($range, $dataSet[$inputField])) {
                            $filtered[] = $dataSet;
                            break;
                        }
                    }
                }

                return $filtered;
            }

                    /**
                     * Принадлежность позиции диапазону
                     * @param array $range
                     * @param int $element
                     * @return bool
                     */
                    protected function inRange(array $range, int $element) : bool
                    {
                        return (($element >= $range['begin']) && ($element <= $range['end']));
                    }
}