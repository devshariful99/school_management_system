<?php

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