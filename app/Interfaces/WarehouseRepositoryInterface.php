<?php

namespace App\Interfaces;

interface WarehouseRepositoryInterface
{
    public function getAll();
    public function getById($id);
}