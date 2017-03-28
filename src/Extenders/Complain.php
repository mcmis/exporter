<?php

namespace MCMIS\Exporter\Extenders;

use MCMIS\Contracts\Exporter;
use MCMIS\Contracts\ExporterExtender;

class Complain implements ExporterExtender
{

    protected $exporter;

    protected $chart = false;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function export($data)
    {
        //
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