<?php


namespace App;


class Warehouse extends ModelAbstract
{
    private $warehouse;

    public function __construct()
    {
        $this->warehouse = app('db');
    }

    public function getTable()
    {
        return $this->warehouse->table('warehouses');
    }
}