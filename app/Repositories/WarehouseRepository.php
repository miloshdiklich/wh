<?php


namespace App\Repositories;


use App\Location;
use App\Warehouse;
use App\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Support\Facades\App;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    /**
     * @var App/Warehouse
     */
    private $warehouse;

    /**
     * @var App/Location
     */
    private $location;

    public function __construct(Warehouse $warehouse, Location $location)
    {
        $this->location = $location;
        $this->warehouse = $warehouse;
    }

    /**
     * Return all records
     * 
     * @return mixed
     */
    public function getAll()
    {
        $result = $this->warehouse->getTable()
            ->join('locations', 'warehouses.location', '=', 'locations.id')
            ->join('managers', 'warehouses.managerId', '=', 'managers.id')
            ->select('warehouses.id', 'warehouses.wh_name', 'locations.latitude', 'locations.longitude', 'locations.location_name', 'managers.manager_name')
            ->get();

        $whs = [];

        foreach ($result as $res)
        {
            $whs[] = array(
                'name' => $res->wh_name,
                'location' => [
                    'name' => $res->location_name,
                    'latitude' => $res->latitude,
                    'longitude' => $res->longitude
                ],
                'manager' => $res->manager_name
            );
        }

        return $whs;
    }

    /**
     * Return single record
     * 
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $result = $this->warehouse->getTable()
            ->join('locations', 'warehouses.location', '=', 'locations.id')
            ->join('managers', 'warehouses.managerId', '=', 'managers.id')
            ->select('warehouses.id', 'warehouses.wh_name', 'locations.latitude', 'locations.longitude', 'locations.location_name', 'managers.manager_name')
            ->where('warehouses.id', $id)
            ->first();

        if(!$result) return false;

        return [
            'name' => $result->wh_name,
            'location' => [
                'name' => $result->location_name,
                'latitude' => $result->latitude,
                'longitude' => $result->longitude
            ],
            'manager' => $result->manager_name
        ];
    }

    /**
     * Insert new record
     * 
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $location = $this->location->getTable()
            ->insertGetId([
                'latitude' => $data['location-latitude'],
                'longitude' => $data['location-longitude'],
                'location_name' => $data['location-name']
            ]);

        $whId = $this->warehouse->getTable()
            ->insertGetId([
                'wh_name' => $data['wh-name'],
                'location' => $location,
                'managerId' => $data['managerId']
            ]);

        return $this->getById($whId);
    }

    /**
     * Update record by id
     * 
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        // This will create new location. In this case validation should be performed.
        // Depending on use case perhaps existing location would be selected.
        $location = $this->location->getTable()
            ->insertGetId([
                'latitude' => $data['location-latitude'],
                'longitude' => $data['location-longitude'],
                'location_name' => $data['location-name']
            ]);

        $wh = $this->warehouse->getTable()
            ->where('id', $id)
            ->update([
                'wh_name' => $data['wh-name'],
                'location' => $location,
                'managerId' => $data['managerId']
            ]);

        return $wh;
    }

    /**
     * Delete record by id
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->warehouse->getTable()
            ->where('id', $id)
            ->delete();
    }
}