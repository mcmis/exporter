<?php

namespace MCMIS\Exporter\Extenders;

use MCMIS\Contracts\Exporter;
use MCMIS\Contracts\ExporterExtenders\ReportExporterExtender as ExporterExtender;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

class Report implements ExporterExtender
{

    protected $exporter;

    protected $chart = false;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function export($data)
    {
        $stateHidden = $this->exporter->getSheetHiddenState();

        Event::listen('exporter:OnCreating', function ($obj, $file) use ($stateHidden) {
            $obj->getSheetByName('Statistics')->setSheetState($stateHidden);
            return $obj;
        });

        $this->exporter->create(
            'Report-' . Carbon::now()->format('mdy-hmi') . 'R' . rand(0, 99),
            'xlsx',
            'Complaints Report',
            array_merge([
                'Statistics' => function ($sheet) use ($data) {
                    $sheet->row(1, $data['header']);
                    $sheet->rows($data['contents']);
                }
            ], ($this->isChartEnabled() ? ['Graphical Report' => function ($sheet) {
                $data_sheet = 'Statistics';
                $sheet->addChart($this->exporter->generateChart(
                    'Complain Categories report',
                    $this->getChartYLables($data_sheet),
                    [$this->exporter->prepareChartDataSeriesValue('String', $data_sheet . '!$A$2:$A$41', NULL, 40)],
                    $this->getChartData($data_sheet)
                )->setTopLeftPosition('A1')->setBottomRightPosition('T30'));
            }] : []))
        );
    }

    protected function getChartYLables($sheet)
    {
        $output = [];
        foreach ([$sheet . '!$B$1', $sheet . '!$C$1', $sheet . '!$D$1', $sheet . '!$E$1', $sheet . '!$F$1',
                     $sheet . '!$G$1', $sheet . '!$H$1', $sheet . '!$I$1', $sheet . '!$J$1', $sheet . '!$K$1'] as $col) {
            $output[] = $this->exporter->prepareChartDataSeriesValue('String', $col, NULL, 1);
        }
        return $output;
    }

    protected function getChartData($sheet)
    {
        $output = [];
        foreach ([$sheet . '!$B$2:$B$41', $sheet . '!$C$2:$C$41', $sheet . '!$D$2:$D$41', $sheet . '!$E$2:$E$41', $sheet . '!$F$2:$F$41',
                     $sheet . '!$G$2:$G$41', $sheet . '!$H$2:$H$41', $sheet . '!$I$2:$I$41', $sheet . '!$J$2:$J$41', $sheet . '!$K$2:$K$41'] as $col) {
            $output[] = $this->exporter->prepareChartDataSeriesValue('Number', $col, NULL, 40);
        }
        return $output;
    }

    public function enableChart()
    {
        $this->chart = true;
        return $this;
    }

    public function disableChart()
    {
        $this->chart = false;
        return $this;
    }

    public function isChartEnabled()
    {
        return $this->chart;
    }

}