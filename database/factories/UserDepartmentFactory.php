<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserDepartment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department_id' => function() {
                return Department::all()->random();
            },
            'user_id' => function() {
                return User::all()->random();
            }
        ];
    }
}
