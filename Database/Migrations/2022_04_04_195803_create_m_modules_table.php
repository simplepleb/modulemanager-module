<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Feature;
use App\Models\Permission;

class CreateMModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        $feature = Feature::where('feature_name', 'modulemanager')->first();
        if( !$feature ){
            $feature = new Feature();
            $feature->feature_name = 'modulemanager';
            $feature->description = 'Module Manager';
            $feature->created_at = now();
            $feature->save();
        }

        Permission::insert([

            ['name' => 'add modules', 'display_name' => 'Add Modules', 'feature_id' => $feature->id],
            ['name' => 'edit modules', 'display_name' => 'Edit Modules', 'feature_id' => $feature->id],
            ['name' => 'delete modules', 'display_name' => 'Delete Modules', 'feature_id' => $feature->id],

        ]);

        $permissions = Permission::where('feature_id',$feature->id )->get();
        $super = \App\Models\Role::where('name', 'Super Admin')->first();
        $admin = \App\Models\Role::where('name', 'Admin')->first();

        foreach( $permissions as $permission ){
            $super->givePermissionTo($permission);
            $admin->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_modules');
    }
}
