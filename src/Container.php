<?php

namespace MCMIS\Exporter;

use MCMIS\Exporter\Collections\Chart;
use MCMIS\Contracts\Exporter;
use Illuminate\Support\Facades\Event;

class Container implements Exporter
{

    protected $columns_index = [];

    protected $extenders = [
        'complain' => 'MCMIS\Exporter\Extenders\Complain',
        'report' => 'MCMIS\Exporter\Extenders\Report',
    ];

    protected $chart;

    /**
     * Container constructor.
     *
     * @param bool $big_sheet
     */
    function __construct($big_sheet = false, Chart $chart)
    {
        $this->setColumnsIndex();
        if($big_sheet)
            $this->extendSheetAsBig();

        $this->chart = $chart;
    }

    /**
     * Create export file
     *
     * @param string $name
     * @param string $extension
     * @param string $title
     * @param array $sheets
     * @param string $response
     * @return void
     */
    public function create($name, $extension, $title, $sheets = [], $response = 'download')
    {
        //TODO: Event::fire('exporter:'.$name.'.OnCreating', []);
        $file = sys()->make('excel')->create($name, function ($file) use ($title, $sheets) {
            $file->setTitle($title)
                ->setCreator('Farhan Wazir')
                ->setCompany('Creative Ideator')
                ->setDescription('File generated for use of exporting data.');
            foreach ($sheets as $title => $closure){
                $file->sheet($title, function($sheet) use ($closure){
                    $closure($sheet);
                });
            }
        });
        //TODO: Event::fire('exporter:'.$name.'.OnCreated', [$file]);
        $file->$response($extension);
    }

    protected function setColumnsIndex(){
        foreach (range('A', 'Z') as $letter){
            $this->columns_index[] = $letter;
        }
    }

    protected function extendColumnsIndex($straight){
        foreach ($straight as $alpha){
            foreach (range('A', 'Z') as $letter){
                $this->columns_index[] = $alpha.$letter;
            }
        }
    }

    public function getColumnsIndexing(){
        return $this->columns_index;
    }

    public function extendSheetAsBig(){
        $this->extendColumnsIndex($this->columns_index);

        return $this;
    }

    public function getSheetHiddenState(){
        return \PHPExcel_Worksheet::SHEETSTATE_HIDDEN;
    }

    public function set($extender){
        if (!isset($this->extenders[$extender])) throw new \ErrorException("Unknown extender set \"{$extender}\" in exporter.");
        else $extender = $this->extenders[$extender];
        return new $extender($this);
    }

    public function __call($name, $params)
    {
        if (in_array($name, get_class_methods($this->chart))) {
            return call_user_func_array([$this->chart, $name], $params);
        }

        throw new \BadMethodCallException('Method '. $name .' not found');
    }

}