<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use App\Repositories\PropertyRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class PropertyService
{

    use ApiResponseTrait;

    protected PropertyRepository $PropertyRepo;

    protected AddressRepository $AddressRepository;

    public function __construct()
    {
        $this->PropertyRepo = new PropertyRepository();
        $this->AddressRepository = new AddressRepository();
    }

    /**
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        try {
            $record = $this->PropertyRepo->getSelectedData();
            return $this->response($record);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(message: "Something went wrong! please try again later", code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getSelectedDataById($id): JsonResponse
    {
        try {
            $record = $this->PropertyRepo->getSelectedDataById($id);
            return $this->response($record);
        } catch (\Exception $e) {
            return $this->response(message: "Something went wrong! please try again later", code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store($request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $pivotData = [];
            $property = $this->PropertyRepo->create([]); //No extra data is required to create property
            $this->AddressRepository->create(
                [
                    'house_name_number' => $request['house_name_number'],
                    'postcode' => $request['postcode'],
                    'property_id' => $property->id,
                ]
            );
            foreach ($request['owners'] as $owner) {
                $pivotData[$owner['user_id']] = ['main_owner' => $owner['main_owner']];
            };
            $property->users()->sync($pivotData);
            DB::commit();
            return $this->response(data: $property, message: "Property Added Successfully", code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->response(message: "Unable to add property", status: false, code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @param $request
     * @return JsonResponse
     */
    public function update($id, $request): JsonResponse
    {
        $pivotData = [];
        try {
            DB::beginTransaction();
            $property = $this->PropertyRepo->findById($id);
            if (!$property) {
                return $this->response(message: "Property Not Found", status: false, code: Response::HTTP_NOT_FOUND);
            }
            $this->AddressRepository->update(
                $id,
                [
                    'house_name_number' => $request['house_name_number'],
                    'postcode' => $request['postcode'],
                ]
            );
            foreach ($request['owners'] as $owner) {
                $pivotData[$owner['user_id']] = ['main_owner' => $owner['main_owner']];
            };
            $property->users()->sync($pivotData);
            DB::commit();
            return $this->response(message: "Property Updated Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(message: "Unable to update property", status: false, code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $property = $this->PropertyRepo->findById($id);
            if (!$property) {
                return $this->response(message: "Property Not Found", status: false, code: Response::HTTP_NOT_FOUND);
            }
            $property->users()->detach();
            $this->AddressRepository->deleteByClause(['property_id' => $id]);
            $this->PropertyRepo->destroy($id);
            DB::commit();
            return $this->response(message: "Property Deleted Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(message: "Unable to delete property", status: false, code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
