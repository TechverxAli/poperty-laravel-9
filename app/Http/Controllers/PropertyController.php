<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyRequest;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    /**
     * @param PropertyService $propertyService
     */

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->propertyService->getList();
    }

    /**
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * @param PropertyRequest $request
     * @return JsonResponse
     */
    public function store(PropertyRequest $request): JsonResponse
    {
        return $this->propertyService->store($request->all());
    }

    /**
     * @param $id
     * @return void
     */
    public function show($id)
    {
        return $this->propertyService->getSelectedDataById($id);
    }

    /**
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param PropertyRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(PropertyRequest $request, $id): JsonResponse
    {
        return $this->propertyService->update($id, $request->all());
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return $this->propertyService->destroy($id);
    }
}
