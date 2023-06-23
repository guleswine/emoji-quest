<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    public function delete()
    {
        \DB::beginTransaction();

        $this->cells()->delete();

        $result = parent::delete();

        \DB::commit();

        return $result;
    }

    public function cells()
    {
        return $this->hasMany(Cell::class);
    }
}
