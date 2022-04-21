<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['house_name_number', 'postcode', 'property_id'];

    /**
     * @return HasOne
     */
    public function property(): HasOne
    {
        return $this->hasOne(Property::class);
    }
}
