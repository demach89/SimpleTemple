<?php

namespace Core\DataSets;

use Core\RequiredFields\KratSetRequiredFields;

/**
 * Набор данных по кратности
 * Class KratSet
 * @package Core\DataSets
 */
class KratSet
{
    protected float  $kratMultiplier;
    protected float  $kratSum;
    protected string $kratAboutText;
    protected string $kratAboutPutDateText;
    protected string $kratRestrictText;
    protected string $kratEndText;
    protected string $kratCalcPageText;
    protected string $percCalcText;

    protected array $dataSetPart;


    public function __construct(KratSetRequiredFields $requiredFields)
    {
        $this->dataSetPart = $requiredFields->get();

        $this->kratMultiplier       = $this->kratMultiplierDefine();
        $this->kratSum              = $this->kratSumDefine();
        $this->kratAboutText        = $this->kratAboutTextDefine();
        $this->kratAboutPutDateText = $this->kratAboutPutDateTextDefine();
        $this->kratRestrictText     = $this->kratRestrictTextDefine();
        $this->kratEndText          = $this->kratEndTextDefine();
        $this->percCalcText         = $this->percCalcTextDefine();
        $this->kratCalcPageText     = $this->kratCalcPageTextDefine();
    }

    /**
     * Получить набор кратности
     * @return array
     */
    public function get() : array
    {
        return [
            'kratMultiplier'        => $this->kratMultiplier,
            'kratSum'               => $this->kratSum,
            'kratAboutText'         => $this->kratAboutText,
            'kratAboutPutDateText'  => $this->kratAboutPutDateText,
            'kratRestrictText'      => $this->kratRestrictText,
            'kratEndText'           => $this->kratEndText,
            'kratCalcPageText'      => $this->kratCalcPageText,
        ];
    }

    /**
     * Определение множителя кратности
     * @return float
     */
    protected function kratMultiplierDefine() : float
    {
        $putDate = $this->dataSetPart['putDate'];

        if (strtotime($putDate) <= strtotime("29.03.2016")) {
            $kratMultiplier = NO_KRAT_ABLE;
        } elseif (strtotime($putDate) <= strtotime("31.12.2016")){
            $kratMultiplier = 4;
        } elseif (strtotime($putDate) <= strtotime("27.01.2019")) {
            $kratMultiplier = 3;
        } elseif (strtotime($putDate) <= strtotime("30.06.2019")) {
            $kratMultiplier = 2.5;
        } elseif (strtotime($putDate) <= strtotime("31.12.2019")) {
            $kratMultiplier = 2;
        } else {
            $kratMultiplier = 1.5;
        }

        return $kratMultiplier;
    }

    /**
     * Определение предельной суммы % для взыскания
     * @return float
     */
    protected function kratSumDefine() : float
    {
        $loan = $this->dataSetPart['loan'];

        return ROUND($loan * $this->kratMultiplier, 2);
    }

    /**
     * Текстовка кратности по закону
     * @return string
     */
    protected function kratAboutTextDefine() : string
    {
        switch ($this->kratMultiplier) {
            case 4: $kratAboutText = "В соответствии с п. 9 ч. 1 ст.12 Федерального закона от 02.07.2010 № 151-ФЗ «О микрофинансовой деятельности и микрофинансовых организациях» (ред. от 29.03.2016) ".
                "микрофинансовая организация не в праве начислять заемщику - физическому лицу проценты и иные платежи по договору потребительского займа, срок возврата потребительского займа ".
                "по которому не превышает одного года, за исключением неустойки (штрафа, пени) и платежей за услуги, оказываемые заемщику за отдельную плату, в случае, если сумма начисленных ".
                "по договору процентов и иных платежей достигнет четырехкратного размера суммы займа.";
                break;
            case 3: $kratAboutText = "В соответствии с п. 9 ч. 1 ст.12 Федерального закона от 02.07.2010 № 151-ФЗ «О микрофинансовой деятельности и микрофинансовых организациях» (в ред. Федерального закона от 03.07.2016 № 230-ФЗ) ".
                "микрофинансовая организация не вправе начислять заемщику - физическому лицу проценты по договору потребительского займа, срок возврата потребительского займа по которому не превышает одного года, ".
                "за исключением неустойки (штрафа, пени) и платежей за услуги, оказываемые заемщику за отдельную плату, в случае, если сумма начисленных по договору процентов достигнет трехкратного размера суммы займа.";
                break;
            case 2.5: $kratAboutText = "В соответствии с п. 1 ч. 4 ст. 3 Федерального закона от 27.12.2018 № 554-ФЗ «О внесении изменений в Федеральный закон «О потребительском кредите (займе)» и Федеральный закон ".
                "«О микрофинансовой деятельности и микрофинансовых организациях» по договору потребительского кредита (займа), срок возврата потребительского кредита (займа) по которому на момент ".
                "его заключения не превышает одного года, не допускается начисление процентов, неустойки (штрафа, пени), иных мер ответственности по договору потребительского кредита (займа), ".
                "а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату по договору потребительского кредита (займа), после того, как сумма начисленных процентов, ".
                "неустойки (штрафа, пени), иных мер ответственности по договору потребительского кредита (займа), а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату ".
                "по договору потребительского кредита (займа), достигнет двух с половиной размеров суммы предоставленного потребительского кредита (займа).";
                break;
            case 2: $kratAboutText = "В соответствии с п. 1 ч. 5 ст. 3 Федерального закона от 27.12.2018 № 554-ФЗ «О внесении изменений в Федеральный закон «О потребительском кредите (займе)» и Федеральный закон ".
                "«О микрофинансовой деятельности и микрофинансовых организациях» по договору потребительского кредита (займа), срок возврата потребительского кредита (займа) по которому на момент ".
                "его заключения не превышает одного года, не допускается начисление процентов, неустойки (штрафа, пени), иных мер ответственности по договору потребительского кредита (займа), ".
                "а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату по договору потребительского кредита (займа), после того, как сумма начисленных процентов, ".
                "неустойки (штрафа, пени), иных мер ответственности по договору потребительского кредита (займа), а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату ".
                "по договору потребительского кредита (займа), достигнет двукратного размера суммы предоставленного потребительского кредита (займа).";
                break;
            case 1.5: $kratAboutText = "В соответствии с п. 24 ст. 5 Федерального закона от 21.12.2013 № 353-ФЗ «О потребительском кредите (займе)» (с изм. и доп., вступ. в силу с 01.01.2020) по договору ".
                "потребительского кредита (займа), срок возврата потребительского кредита (займа) по которому на момент его заключения не превышает одного года, не допускается начисление процентов, ".
                "неустойки (штрафа, пени), иных мер ответственности по договору потребительского кредита (займа), а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату ".
                "по договору потребительского кредита (займа), после того, как сумма начисленных процентов, неустойки (штрафа, пени), иных мер ответственности по договору ".
                "потребительского кредита (займа), а также платежей за услуги, оказываемые кредитором заемщику за отдельную плату по договору потребительского кредита (займа) ".
                "(далее - фиксируемая сумма платежей), достигнет полуторакратного размера суммы предоставленного потребительского кредита (займа).";
                break;
            default: $kratAboutText = "";
        }

        return $kratAboutText;
    }

    /**
     * Текстовка-преамбула к "кратности по закону"
     * @return string
     */
    protected function kratAboutPutDateTextDefine() : string
    {
        $putDate = $this->dataSetPart['putDate'];
        $kratMultiplier = $this->kratMultiplier;

        if ($kratMultiplier == NO_KRAT_ABLE) {
            $kratAboutPutDateText = "";
        } else {
            $kratAboutPutDateText = "Договор займа был заключен $putDate г., таким образом подлежит применению норма о $kratMultiplier-х кратном ограничении размера процентов за пользование суммой займа.";
        }

        return $kratAboutPutDateText;
    }

    /**
     * Текстова ограничения кратности
     * @return string
     */
    protected function kratRestrictTextDefine() : string
    {
        $kratMultiplier = $this->kratMultiplier;
        $kratSum        = $this->kratSum;

        $loan     = $this->dataSetPart['loan'];
        $paysOP   = $this->dataSetPart['paysOP'];
        $paysPeny = $this->dataSetPart['paysPeny'];
        $endOP    = $this->dataSetPart['endOP'];
        $endPeny  = $this->dataSetPart['endPeny'];

        $kratSumStr = number_format($kratSum,2);
        $loanStr    = number_format($loan,2);

        /**
         * Учёт ОП+Пени в ограничениях для займов до х3 (после 27.01.2019)
         */
        if ($kratMultiplier < 3) {
            $paysPercStr  = number_format($paysOP + $paysPeny,2);
            $limitPercStr = number_format($kratSum - ($paysOP + $paysPeny),2);

            $kratRestrictText = "В этой связи ООО «ЭкоФинансы» снижает размер подлежащих взысканию с Должника процентов до $kratMultiplier-х кратного, т.е. до $kratSumStr руб., ".
                "исходя из следующего расчета: ($loanStr*$kratMultiplier), где $loanStr - сумма займа, $kratMultiplier-х кратного размера долга ".
                "(т.е. $kratSumStr руб. - это максимально возможный размер процентов по Договору): $kratSumStr руб. (максимальная сумма процентов по Договору) ".
                "- $paysPercStr руб. (сумма оплаченных процентов по Договору) = $limitPercStr руб. (остаток процентов).";
        }
        /**
         * Учёт только ОП в ограничениях для займов с 3/4-х (29.03.2016 - 27.01.2019)
         */
        elseif ($kratMultiplier <= 4){
            $paysPercStr  = number_format($paysOP,2);
            $limitPercStr = number_format($kratSum - $paysOP,2);

            $kratRestrictText = "В этой связи ООО «ЭкоФинансы» снижает размер подлежащих взысканию с Должника процентов до $kratMultiplier-х кратного, т.е. до $kratSumStr  руб., ".
                "исходя из следующего расчета: ($loanStr*$kratMultiplier), где $loanStr - сумма займа, $kratMultiplier-х кратного размера долга ".
                "(т.е. $kratSumStr руб. - это максимально возможный размер процентов по Договору): $kratSumStr руб. (максимальная сумма процентов по Договору) ".
                "- $paysPercStr руб. (сумма оплаченных процентов по Договору и штрафных процентов (неустойки)) = $limitPercStr руб. (остаток процентов).";
        }
        /** Отсутствие ограничений (до 29.03.2016)  */
        else {
            $kratRestrictText = "";
        }

        return $kratRestrictText;
    }

    /**
     * Текстовка-концовка
     * @return string
     */
    protected function kratEndTextDefine() : string
    {
        $kratMultiplier = $this->kratMultiplier;

        $endPerc    = $this->dataSetPart['endPerc'];
        $endPercStr = number_format($endPerc, 2);

        if ($kratMultiplier == NO_KRAT_ABLE) {
            $kratEndText =  "";
        } else {
            $kratEndText = "Таким образом, итоговая сумма начисленных процентов за пользование займом и штрафных процентов (неустойки) составляет $endPercStr руб., что соответствует применяемой кратности.";
        }

        return $kratEndText;
    }

    /**
     * Текстовка по расшифровке начислений ОП/Пени после цессии
     * @return string
     */
    public function percCalcTextDefine() : string
    {
        $endOD          = $this->dataSetPart['endOD'];
        $endOP          = $this->dataSetPart['endOP'];
        $endPeny        = $this->dataSetPart['endPeny'];
        $endPerc        = $this->dataSetPart['endPerc'];
        $dayRate        = $this->dataSetPart['dayRate'];

        $cessionODRest   = $this->dataSetPart['cessionODRest'];
        $cessionOPRest   = $this->dataSetPart['cessionOPRest'];
        $cessionPenyRest = $this->dataSetPart['cessionPenyRest'];
        $afterCessionOPRest   = $endOP - $cessionOPRest;
        $afterCessionPenyRest = $endPeny - $cessionPenyRest;

        $endODStr           = number_format($endOD, 2);
        $endOPStr           = number_format($endOP, 2);
        $endPenyStr         = number_format($endPeny, 2);
        $endPercStr         = number_format($endPerc, 2);
        $dayRateStr         = number_format($dayRate, 3);

        $cessionODRestStr    = number_format($cessionODRest, 2);
        $cessionOPRestStr    = number_format($cessionOPRest, 2);
        $cessionPenyRestStr  = number_format($cessionPenyRest, 2);
        $afterCessionOPRestStr  = number_format($afterCessionOPRest, 2);
        $afterCessionPenyRestStr  = number_format($afterCessionPenyRest, 2);

        $cessionDate = $this->dataSetPart['cessionDate'];
        $calcDate   = $this->dataSetPart['calcDate'];
        $daysPastAfterCessionToCalcDate = $this->dataSetPart['daysPastAfterCessionToCalcDate'];

        $percCalcText =
            "Размер задолженности по процентам составляет $afterCessionOPRestStr руб., исходя из следующего расчета: "  . " </w:t><w:br/><w:t> " .
            "$cessionODRestStr (остаток основного долга) * $daysPastAfterCessionToCalcDate (кол-во дней просрочки с $cessionDate г. по $calcDate г.) * $dayRateStr (процентная ставка по договору в день) / 100 = " .
            "$afterCessionOPRestStr руб." . " </w:t><w:br/><w:br/><w:t> " .
            "Размер задолженности по неустойке составляет $afterCessionPenyRestStr руб., исходя из следующего расчета:" . " </w:t><w:br/><w:t> " .
            "$cessionODRestStr (остаток основного долга) * $daysPastAfterCessionToCalcDate (кол-во дней просрочки с $cessionDate г. по $calcDate г.) * 0,05 (процентная ставка по договору в день) / 100 = ".
            "$afterCessionPenyRestStr руб."
        ;

        //$percCalcText ex output:
        //Размер задолженности по процентам составляет 163,163.40 руб., исходя из следующего расчета:
        //8,364.00 (остаток основного долга) * 51 (кол-во дней просрочки с 11.04.2023 г. по 31.05.2023 г.) * 0,65 (процентная ставка по договору в день) /100 = 2,772.66 руб.
        //Размер задолженности по неустойке составляет 163,163.40 руб., исходя из следующего расчета:
        //8,364.00 (остаток основного долга) * 51 (кол-во дней просрочки с 11.04.2023 г. по 31.05.2023 г.) * 0,05 (процентная ставка по договору в день) /100 = 213.28 руб.

        return $percCalcText;
    }

    /**
     * Текстовка страницы расчётов
     * @return string
     */
    protected function kratCalcPageTextDefine() : string
    {
        $kratMultiplier = $this->kratMultiplier;

        if ($kratMultiplier > 4) {
            $kratCalcPageText = $this->percCalcText;
        } else {
            $kratCalcPageText = $this->kratAboutText . " </w:t><w:br/><w:br/><w:t> " . $this->kratRestrictText . " </w:t><w:br/><w:br/><w:t> " . $this->percCalcText . " </w:t><w:br/><w:br/><w:t> " . $this->kratEndText;
        }

        return $kratCalcPageText;
    }
}