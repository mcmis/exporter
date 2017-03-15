<?php

namespace MCMIS\Exporter\Collections;


class Chart
{

    public function generateChart($title, $y_labels, $x_labels, $data, $type = 'barchart', $grouping = 'standard', $legend_position = 'left'){
        $yLables = [];
        foreach ($y_labels as $label){
            if($label instanceof \PHPExcel_Chart_DataSeriesValues){
                $yLables[] = $label;
            }
            else{
                $yLables[] = $this->prepareChartDataSeriesValue($label['dataType'], $label['name'], $label['format'], $label['counter']);
            }
        }

        $xLables = [];
        foreach ($x_labels as $label){
            if($label instanceof \PHPExcel_Chart_DataSeriesValues){
                $xLables[] = $label;
            }
            else{
                $xLables[] = $this->prepareChartDataSeriesValue($label['dataType'], $label['name'], $label['format'], $label['counter']);
            }
        }

        $dSeries = [];
        foreach ($data as $row){
            if($row instanceof \PHPExcel_Chart_DataSeriesValues){
                $dSeries[] = $row;
            }
            else{
                $dSeries[] = $this->prepareChartDataSeriesValue($row['dataType'], $row['name'], $row['format'], $row['counter']);
            }
        }

        $dataSeries = new \PHPExcel_Chart_DataSeries(
            $this->getChartType($type),
            $this->getChartTypeGrouping($grouping),
            range(0, count($dSeries)-1),
            $yLables,
            $xLables,
            $dSeries
        );

        $plot_area = new \PHPExcel_Chart_PlotArea(NULL, array($dataSeries));
        $legend = new \PHPExcel_Chart_Legend($this->getChartLegendPosition($legend_position), NULL, false);

        return new \PHPExcel_Chart(
            'chart'.rand(1, 999),
            new \PHPExcel_Chart_Title($title),
            $legend,
            $plot_area,
            true,
            0,
            NULL,
            NULL
        );
    }

    public function prepareChartDataSeriesValue($dataType, $name, $format, $counter){
        return new \PHPExcel_Chart_DataSeriesValues($dataType, $name, $format, $counter);
    }

    public function getChartType($type){
        switch ($type){
            default:
                $output = $type;
                break;
            case 'barchart':
                $output = \PHPExcel_Chart_DataSeries::TYPE_BARCHART;
                break;
            case 'linechart':
                $output = \PHPExcel_Chart_DataSeries::TYPE_LINECHART;
                break;
        }

        return $output;
    }

    public function getChartTypeGrouping($group){
        switch ($group){
            default:
                $output = \PHPExcel_Chart_DataSeries::GROUPING_STANDARD;
                break;
            case 'stacked':
                $output = \PHPExcel_Chart_DataSeries::GROUPING_STACKED;
                break;
            case 'clustered':
                $output = \PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED;
                break;
            case 'percent_stacked':
                $output = \PHPExcel_Chart_DataSeries::GROUPING_PERCENT_STACKED;
                break;
        }

        return $output;
    }

    public function getChartLegendPosition($position){
        switch ($position){
            default:
                $output = \PHPExcel_Chart_Legend::POSITION_LEFT;
                break;
            case 'right':
                $output = \PHPExcel_Chart_Legend::POSITION_RIGHT;
                break;
            case 'top':
                $output = \PHPExcel_Chart_Legend::POSITION_TOP;
                break;
            case 'bottom':
                $output = \PHPExcel_Chart_Legend::POSITION_BOTTOM;
                break;
            case 'topright':
                $output = \PHPExcel_Chart_Legend::POSITION_TOPRIGHT;
                break;
        }

        return $output;
    }

}