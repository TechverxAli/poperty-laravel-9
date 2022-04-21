<?php

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends BaseRepo
{
    public function __construct()
    {
        parent::__construct(Property::class);
    }

    public function getSelectedData()
    {
        return $this->model->with(['users:id as user_id,first_name,last_name', 'address:id as address_id,property_id,house_name_number,postcode'])->get();
    }

    public function getSelectedDataById($id)
    {
        return $this->model->where('id', $id)->with(['users:id as user_id,first_name,last_name', 'address:id as address_id,property_id,house_name_number,postcode'])->first();
    }
}
