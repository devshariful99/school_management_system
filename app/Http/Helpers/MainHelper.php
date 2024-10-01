<?php

use App\Models\Permission;
use League\Csv\Writer;
use Illuminate\Support\Facades\File;

function timeFormat($time)
{
    return date(('d M, Y H:i A'), strtotime($time));
}
function admin()
{
    return auth()->guard('admin')->user();
}
function creater_name($user)
{
    return $user->name ?? 'System';
}
function updater_name($user)
{
    return $user->name ?? 'Null';
}

function isSuperAdmin()
{
    return auth()->guard('admin')->user()->role->name == 'Super Admin';
}

function createCSV($filename = 'permissions.csv'): string
{
    $permissions = Permission::all(['name', 'guard_name', 'prefix']);

    $csvPath = public_path('csv/' . $filename);
    // Ensure the directory exists
    File::ensureDirectoryExists(dirname($csvPath));
    // Create the CSV writer
    $csv = Writer::createFromPath($csvPath, 'w+');
    // Insert header
    $csv->insertOne(['name', 'guard_name', 'prefix']);
    // Insert records
    foreach ($permissions as $permission) {
        $csv->insertOne([
            $permission->name,
            $permission->guard_name,
            $permission->prefix,
        ]);
    }
    return $csvPath;
}