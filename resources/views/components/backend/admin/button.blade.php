@php
    //This function will take the route name and return the access permission.
    $check = false;
    if (
        (!isset($datas['permissions']) ||
            !is_array($datas['permissions']) ||
            count($datas['permissions']) == 0 ||
            !admin()->hasAnyPermission($datas['permissions'])) &&
        !isSuperAdmin()
    ) {
        $check = false;
    } elseif (
        (isset($datas['permissions']) &&
            is_array($datas['permissions']) &&
            admin()->hasAnyPermission($datas['permissions'])) ||
        isSuperAdmin()
    ) {
        if (!isset($datas['routeName']) || $datas['routeName'] == '' || $datas['routeName'] == null) {
            $check = false;
        } else {
            $check = true;
        }
    }

    //Parameters
    $parameterArray = isset($datas['params']) ? $datas['params'] : [];
@endphp
@if ($check)
    <a href="@if (isset($datas['delete']) && $datas['delete'] == true) javascript:void(0) @else {{ route($datas['routeName'], $parameterArray) }} @endif"
        @if (isset($datas['delete']) && $datas['delete'] == true) onclick="confirmDelete(() => document.getElementById('{{ $datas['form_id'] }}').submit())" @endif
        class="btn btn-sm {{ $datas['className'] ?? 'btn-primary' }}">{{ __($datas['label'] ?? '') }}

        @if (isset($datas['delete']) && $datas['delete'] == true)
            <form id="{{ $datas['form_id'] }}" action="{{ route($datas['routeName'], $parameterArray) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </a>



@endif
