<?php

namespace App\Services;

use App\Models\MaintainReport;
use App\Models\OperatingReport;
use App\Models\StructureReport;
use Carbon\Carbon;

class ReportService
{
    public function getDataOverviewReport()
    {
        $arrStructureReport = StructureReport::where('month', Carbon::now()->month - 1)->where('year', Carbon::now()->year)->get();

        $inuse  = $arrStructureReport->where('type', 1)->first();
        $unused = $arrStructureReport->where('type', 2)->first();
        $broken = $arrStructureReport->where('type', 3)->first();
        $lost   = $arrStructureReport->where('type', 4)->first();

        return [
            'total' => [
                'total_money'   => $inuse->total_money + $unused->total_money + $broken->total_money + $lost->total_money,
                'amount_assets' => $inuse->quantity + $unused->quantity + $broken->quantity + $lost->quantity,
            ],
            'inuse' => [
                'total_money'   => $inuse->total_money,
                'amount_assets' => $inuse->quantity,
            ],
            'unused' => [
                'total_money'   => $unused->total_money,
                'amount_assets' => $unused->quantity,
            ],
            'broken' => [
                'total_money'   => $broken->total_money,
                'amount_assets' => $broken->quantity,
            ],
            'lost' => [
                'total_money'   => $lost->total_money,
                'amount_assets' => $lost->quantity,
            ],
            'other' => [
                'total_money'   => 0,
                'amount_assets' => 0,
            ],
        ];
    }

    public function getDataValueReport()
    {
        $arrStructureReport = StructureReport::selectRaw('month, year, SUM(total_money) as total_money, SUM(quantity) as quantity')
            ->where('year', Carbon::now()->year)->groupBy('month', 'year')->get();

        $arrLabels      = [];
        $arrValues      = [];
        $arrValuesRound = [];

        foreach ($arrStructureReport as $item) {
            $arrLabels[]      = 'Tháng ' . $item->month;
            $arrValues[]      = $item->total_money;
            $arrValuesRound[] = round($item->total_money / $this->getUnitNumber($item->total_money), 1);
        }

        $arrDifference = [];

        $unitNumber = $this->getUnitNumber(max($arrValues));
        $unit       = $this->getUnit($unitNumber);

        for ($i = 0; $i < count($arrValues); ++$i) {
            if ($i != count($arrValues) - 1) {
                $diff = $arrValues[$i + 1] - $arrValues[$i];

                if ($diff < 0) {
                    $trend = 1;
                } elseif (0 == $diff) {
                    $trend = 2;
                } else {
                    $trend = 3;
                }

                $arrDifference[] = [
                    'change' => 0 != $diff ? (($diff > 0 ? 'Tăng ' : 'Giảm ') . round(abs($diff) / $this->getUnitNumber(abs($diff)), 1) . ' ' . $this->getUnit(abs($diff))) : 'Giữ nguyên',
                    'trend'  => $trend,
                ];
            }
        }

        return [
            'data' => [
                'labels'        => $arrLabels,
                'value_origin'  => $arrValues,
                'values'        => $arrValuesRound,
                'arrDifference' => $arrDifference,
            ],
            'unit'       => $unit,
            'unitNumber' => $unitNumber,
        ];
    }

    private function getUnitNumber(int $number)
    {
        if ($number >= 1000000000) {
            return 1000000000;
        }
        if ($number >= 1000000) {
            return 1000000;
        } else {
            return 1000;
        }
    }

    private function getUnit(int $number)
    {
        if ($number >= 1000000000) {
            return 'tỷ';
        }
        if ($number >= 1000000) {
            return 'triệu';
        } else {
            return 'nghìn';
        }
    }

    public function getDataOperatingReport()
    {
        $repairCosts        = OperatingReport::where('type', 1)->pluck('total_money');
        $maintainCosts      = OperatingReport::where('type', 2)->pluck('total_money');
        $arrOperatingReport = StructureReport::selectRaw('month, year, SUM(total_money) as total_money')
            ->where('year', Carbon::now()->year)->groupBy('month', 'year')->get();

        $arrLabels = [];

        foreach ($arrOperatingReport as $value) {
            $arrLabels[] = 'Tháng ' . $value->month;
        }

        $operatingCosts = [];

        foreach ($repairCosts as $key => $cost) {
            $operatingCosts[] = $cost + $maintainCosts[$key];
        }

        $unitNumber = $this->getUnitNumber(max($operatingCosts));
        $unit       = $this->getUnit(max($operatingCosts));

        $operatingCostsDisplay = [];
        $repairCostsDisplay    = [];
        $maintainCostsDisplay  = [];

        foreach ($operatingCosts as $key => $cost) {
            $operatingCostsDisplay[] = $cost / $unitNumber;
            $repairCostsDisplay[]    = $repairCosts[$key] / $unitNumber;
            $maintainCostsDisplay[]  = $maintainCosts[$key] / $unitNumber;
        }

        return [
            'data' => [
                'labels'        => $arrLabels,
                'values'        => $operatingCostsDisplay,
                'repairCosts'   => $repairCostsDisplay,
                'maintainCosts' => $maintainCostsDisplay,

            ],
            'unit' => $unit,
        ];
    }

    public function getDataStructureReport()
    {
        $arrStructureReport = StructureReport::where('month', Carbon::now()->month - 1)->where('year', Carbon::now()->year)->get();

        $arrValues = [];

        foreach ($arrStructureReport as $item) {
            $arrValues[] = $item->quantity;
        }

        $arrValues[] = 0;

        return [
            'values' => $arrValues,
        ];
    }

    public function getDataUseReport()
    {
        $arrInuse = StructureReport::where('type', 1)->where('year', Carbon::now()->year)->pluck('quantity');

        $arrTotalStructureReport = StructureReport::selectRaw('month, year, SUM(total_money) as total_money, SUM(quantity) as quantity')
            ->where('year', Carbon::now()->year)->groupBy('month', 'year')->get();

        $arrLabels = [];

        foreach ($arrTotalStructureReport as $value) {
            $arrLabels[] = 'Tháng '.$value->month;
        }

        return [
            'arrLabels' => $arrLabels,
            'arrInuse'  => $arrInuse,
            'arrTotal'  => $arrTotalStructureReport->pluck('quantity'),
        ];
    }

    public function getDataMaintainReport()
    {
        $arrMaintainReport = MaintainReport::where('year', Carbon::now()->year)->get();

        return [
            'arrLabels'     => $arrMaintainReport->pluck('plan_name'),
            'arrQuantity'   => $arrMaintainReport->pluck('quantity'),
            'arrTotalMoney' => $arrMaintainReport->pluck('total_money'),
        ];
    }
}
