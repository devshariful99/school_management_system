<?php

use App\Models\Permission;
use League\Csv\Writer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function timeFormat($time)
{
    return date(('d M, Y H:i A'), strtotime($time));
}
function admin()
{
    return auth()->guard('web')->user();
}
function user()
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


function slugToTitle($slug)
{
    return Str::replace('-', ' ', $slug);
}
function storage_url($urlOrArray)
{
    $image = asset('default_img/no_img.jpg');
    if (is_array($urlOrArray) || is_object($urlOrArray)) {
        $result = '';
        $count = 0;
        $itemCount = count($urlOrArray);
        foreach ($urlOrArray as $index => $url) {

            $result .= $url ? asset('storage/' . $url) : $image;

            if ($count === $itemCount - 1) {
                $result .= '';
            } else {
                $result .= ', ';
            }
            $count++;
        }
        return $result;
    } else {
        return $urlOrArray ? asset('storage/' . $urlOrArray) : $image;
    }
}

function auth_storage_url($url, $gender = false)
{
    $image = asset('default_img/other.png');
    if ($gender == 1) {
        $image = asset('default_img/male.png');
    } elseif ($gender == 2) {
        $image = asset('default_img/female.png');
    }
    return $url ? asset('storage/' . $url) : $image;
}