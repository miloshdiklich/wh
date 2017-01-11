<?php


namespace App;


class Location extends ModelAbstract
{
    private $location;

    public function __construct()
    {
        $this->location = app('db');
    }

    public function getTable()
    {
        return $this->location->table('locations');
    }
}