<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Role extends Model
{
    //

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
