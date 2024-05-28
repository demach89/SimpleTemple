<?php

namespace Core\DataSets;

use Core\RequiredFields\KratSetRequiredFields;
use Core\Toolkits\Toolkit;

/**
 * Общий набор данных
 * Class DataSets
 * @package Core\DataSets
 */
class DataSet
{
    use Toolkit;

    protected array $dataSet;
    protected array $portfolio;

    /**
     * DataSets constructor.
     * @param array $dataSet
     * @param array $portfolio
     */
    public function __construct(array $dataSet, array $portfolio)
    {
        $this->dataSet   = $dataSet;
        $this->portfolio = $portfolio;
    }

    /**
     * Получить массив данных объекта
     * @return array
     */
    public function get() : array
    {
        return $this->dataSet;
    }

    public function addCessionSet($cessionDate) : self
    {
        $this->dataSet['cessionDate'] = $cessionDate;

        $this->dataSet['daysPastAfterCession'] = $this->daysPastAfterCessionToCalcDate($cessionDate);

        return $this;
    }

    /**
     * Добавить дополнительные элементы в общий массив данных
     */
    public function addPercSumSet() : self
    {
        $this->dataSet['paysPerc'] =  $this->dataSet['paysOP'] + $this->dataSet['paysPeny'];
        $this->dataSet['endPerc']  = $this->dataSet['endOP'] + $this->dataSet['endPeny'];

        return $this;
    }

    /**
     * Включить расчёт гос.пошлины в общий массив данных
     */
    public function addGosSet(string $tribunalType = 'sud') : self
    {
        $this->dataSet['gos'] = ($tribunalType === 'sud') ?
            $this->calcGP($this->dataSet['endAll']) :
            $this->calcGP($this->dataSet['endAll']) * 2;

        $this->dataSet['endAllGos'] = $this->dataSet['endAll'] + $this->dataSet['gos'];

        return $this;
    }

    /**
     * Добавить данные о кратности в общий массив данных
     * @return $this
     */
    public function addKratSet(string $cessionDate) : self
    {
        $kratRequiredFields = new KratSetRequiredFields(
                   calcDate: $this->dataSet['calcDate'],
                    putDate: $this->dataSet['putDate'],
                       loan: $this->dataSet['loan'],
                    dayRate: $this->dataSet['dayRate'],
                     paysOP: $this->dataSet['paysOP'],
                   paysPeny: $this->dataSet['paysPeny'],
                      endOD: $this->dataSet['endOD'],
                      endOP: $this->dataSet['endOP'],
                    endPeny: $this->dataSet['endPeny'],
                    endPerc: $this->dataSet['endPerc'],
              cessionODRest: $this->dataSet['cessionODRest'],
              cessionOPRest: $this->dataSet['cessionOPRest'],
            cessionPenyRest: $this->dataSet['cessionPenyRest'],
                cessionDate: $cessionDate,
            daysPastAfterCessionToCalcDate: $this->daysPastAfterCessionToCalcDate($cessionDate),
        );

        $kratSet = (new KratSet($kratRequiredFields))->get();

        $this->dataSet = array_merge($this->dataSet, $kratSet);

        return $this;
    }

    /**
     * Добавить данные о юзере в общий массив данных
     * @return $this
     */
    public function addUserSet() : self
    {
        $userSet = (new UserSet($this->dataSet['userName']))->get();

        $this->dataSet = array_merge($this->dataSet, $userSet);

        return $this;
    }

    /**
     * Добавить склонение ФИО
     * @return $this
     * @throws \Exception
     */
    public function addClientNameDeCase() : self
    {
        $this->dataSet['fioTvaritPadezh'] = $this->declension($this->dataSet['fio'], 'творительный');
        $this->dataSet['fioRoditPadezh']  = $this->declension($this->dataSet['fio'], 'родительный');

        return $this;
    }

    /**
     * Пост-корректировка сумм (по согласованию)
     * Убрать срочный долг из заявления (2023-08-18, для живых аннуитетов)
     * @return DataSet
     */
    public function correctProsrCostFields() : self
    {
        $this->dataSet['endOD']   = $this->dataSet['endODProsr'];
        $this->dataSet['endAll'] -= $this->dataSet['endODwoProsr'];
        $this->dataSet['endODwoProsr'] = 0;

        return $this;
    }

    /**
     * Добавить конечную стоимость иска
     * @return $this
     */
    public function addIskCost() : self
    {
        $this->dataSet['iskCost'] =  $this->dataSet['endAll'];

        return $this;
    }

    /**
     * Добавить наименование суда без приставки
     * @return $this
     */
    public function addSudShortName() : self
    {
        $this->dataSet['sudNameShort'] = str_replace('Судебный участок ', '', $this->dataSet['sudName']);

        return $this;
    }

    /**
     * Определить количество дней с цессии по текущую дату
     * @param $cessionDate
     * @return int
     */
    protected function daysPastAfterCessionToNow($cessionDate) : int
    {
        $now = strtotime(date('d.m.Y'));

        return $this->daysDiff($cessionDate, $now);
    }


    protected function daysPastAfterCessionToCalcDate(string $cessionDate) : int
    {
        $calcDate = $this->dataSet['calcDate'];

        return $this->daysDiff($cessionDate, $calcDate);
    }
}