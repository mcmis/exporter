<?php

namespace MCMIS\Exporter\Extenders;

use MCMIS\Contracts\Exporter;

class Complaints
{

    protected $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

}