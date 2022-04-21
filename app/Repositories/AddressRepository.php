<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository extends BaseRepo
{
    public function __construct()
    {
        parent::__construct(Address::class);
    }
}
