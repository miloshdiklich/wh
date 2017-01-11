<?php


namespace App\Repositories;


use App\Interfaces\WarehouseRepositoryInterface;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = app('db');
    }

    /**
     * Return all records
     * 
     * @return mixed
     */
    public function getAll()
    {
        $result = $this->db->table('warehouses')
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

        echo json_encode($whs);
    }

    /**
     * Return single record
     * 
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $result = $this->db->table('warehouses')
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
        $location = $this->db->table('locations')
            ->insertGetId([
                'latitude' => $data['location-latitude'],
                'longitude' => $data['location-longitude'],
                'location_name' => $data['location-name']
            ]);

        $whId = $this->db->table('warehouses')
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
        $location = $this->db->table('locations')
            ->insertGetId([
                'latitude' => $data['location-latitude'],
                'longitude' => $data['location-longitude'],
                'location_name' => $data['location-name']
            ]);

        $wh = $this->db->table('warehouses')
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
        return $this->db->table("warehouses")
            ->where('id', $id)
            ->delete();
    }
}