<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class ChangeRolesSeeder extends Migration
{
    
    public function up()
    {
        DB::table('roles')->delete(1);
        DB::table('roles')->delete(2);
        DB::table('roles')->delete(3);

        DB::table('roles')->insert([
            'id' => 1,
            'name' => "Admin",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => "Customer",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
   
    public function down()
    {
        DB::table('roles')->delete(1);
        DB::table('roles')->delete(2);

        DB::table('roles')->insert([
            'id' => 1,
            'name' => "Admin",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => "Employee",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('roles')->insert([
            'id' => 3,
            'name' => "Company Manager",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
