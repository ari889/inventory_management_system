<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'name', 'slug'];


    public function module(){
        return $this->belongsTo(Module::class);
    }

    /**
     * module with role relationship
     */
    public function permission_role(){
        return $this->hasMany(PermissioneRole::class);
    }
}
