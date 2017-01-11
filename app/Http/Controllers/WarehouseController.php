<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Interfaces\WarehouseRepositoryInterface;

class WarehouseController extends ApiController
{
    private $warehouse;

    public function __construct(WarehouseRepositoryInterface $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * Return all records
     *
     * @return array
     */
    public function index()
    {
        return $this->warehouse->getAll();
    }

    /**
     * Return single record by id
     *
     * @param $id
     * @return json|mixed
     */
    public function show($id)
    {
        $wh = $this->warehouse->getById($id);

        return $wh ? $this->respond($wh) : $this->respondNotFound();
    }

    /**
     * Create new record
     *
     * @param Request $request
     * @return json|mixed
     */
    public function store(Request $request)
    {
        $data = [
            'wh-name' => $request->input('wh-name'),
            'location-latitude' => $request->input('location-latitude'),
            'location-longitude' => $request->input('location-longitude'),
            'location-name' => $request->input('location-name'),
            'managerId' => $request->input('managerId')
        ];

        $wh = $this->warehouse->create($data);

        return $wh ? $this->respond($wh) : $this->respondInternalError();
    }

    /**
     * Update record by id
     *
     * @param $id
     * @param Request $request
     * @return json|mixed
     */
    public function update($id, Request $request)
    {
        $data = [
            'wh-name' => $request->input('wh-name'),
            'location-latitude' => $request->input('location-latitude'),
            'location-longitude' => $request->input('location-longitude'),
            'location-name' => $request->input('location-name'),
            'managerId' => $request->input('managerId')
        ];

        $wh = $this->warehouse->update($id, $data);

        return $wh ? $this->respond($this->warehouse->getById($id)) : $this->respondInternalError();
    }

    /**
     * Delete record by id
     *
     * @param $id
     * @return json|mixed
     */
    public function destroy($id)
    {
        $wh = $this->warehouse->delete($id);

        return $wh ? $this->respondSuccess('Warehouse deleted') : $this->respondInternalError();
    }

}