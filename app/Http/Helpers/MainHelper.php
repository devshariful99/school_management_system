<?php

function timeFormat($time)
{
    return date(('d-M-Y H:i A'), strtotime($time));
}
function admin()
{
    return auth()->guard('admin')->user();
}
function c_user_name($user)
{
    return $user->name ?? 'System';
}
function u_user_name($user)
{
    return $user->name ?? 'Null';
}