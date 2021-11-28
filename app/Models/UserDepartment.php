<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'department_vs_users';

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
