@php
    //This function will take the route name and return the access permission.
    $check = false;
    if (
        (!isset($permissions) ||
            !is_array($permissions) ||
            count($permissions) == 0 ||
            !admin()->hasAnyPermission($permissions)) &&
        !isSuperAdmin()
    ) {
        $check = false;
    } elseif (
        (isset($permissions) && is_array($permissions) && admin()->hasAnyPermission($permissions)) ||
        isSuperAdmin()
    ) {
        if (!isset($routeName) || $routeName == '' || $routeName == null) {
            $check = false;
        } else {
            $check = true;
        }
    }

    //Parameters
    $parameterArray = isset($params) ? $params : [];
@endphp
@if ($check)
    <a href="{{ route($routeName, $parameterArray) }}"
        class="btn btn-sm {{ $className ?? 'btn-primary' }}">{{ __($label) }}</a>
@endif
