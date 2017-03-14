<?php

namespace MCMIS\Exporter;


use MCMIS\Exporter\Traits\ChartTrait;

class Container
{

    use ChartTrait;

    protected $columns_index = [];

    function __construct($big_sheet = false)
    {
        $this->setColumnsIndex();
        if($big_sheet)
            $this->extendSheetAsBig();
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
        app()->make('excel')->create($name, function($file) use ($title, $sheets){
            $file->setTitle($title)
                ->setCreator('Farhan Wazir')
                ->setCompany('Creative Ideator')
                ->setDescription('File generated for use of exporting data.');
            foreach ($sheets as $title => $closure){
                $file->sheet($title, function($sheet) use ($closure){
                    $closure($sheet);
                });
            }
        })->$response($extension);
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

    public function getColumnsIndex(){
        return $this->columns_index;
    }

    public function extendSheetAsBig(){
        $this->extendColumnsIndex($this->columns_index);

        return $this;
    }

    public function getSheetHiddenState(){
        return static::SHEETSTATE_HIDDEN;
    }

}