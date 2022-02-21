<?php
namespace Zekini\CrudGenerator\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


trait CreatesPermissionObject
{

    protected function setupPermissions(): void
    {
        foreach ($this->permissions as $permission) {
            // we check if permission exists
            if (!$this->ObjectExists($permission, 'permissions')) {
                $this->createObject($permission, 'permissions');
            }
        }
    }
    

    protected function ObjectExists($objectValue, $type): bool
    {
        return DB::table($type)
            ->where([
                'name' => $objectValue,
                'guard_name' => $this->guardName
            ])
            ->exists();
    }


    protected function getObject($objectValue, $type): object
    {
        return DB::table($type)
        ->where([
            'name' => $objectValue,
            'guard_name' => $this->guardName
        ])
        ->first();
    }


    protected function createObject($objectValue, $type): void
    {
        DB::table($type)->insertGetId([
            'name' => $objectValue,
            'guard_name' => $this->guardName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    protected function deleteObjects($type, $range):void
    {
        DB::table($type)
            ->whereIn('name', $range)
            ->delete();
    }
    
}