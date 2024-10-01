<div class="btn-group d-flex align-items-center gap-3 flex-wrap">
    <i class="fa fa-list reorder float-left" style="cursor: move;"></i>
    <a href="javascript:void(0)" class="btn btn-primary btn-rounded d-flex align-items-center justify-content-center"
        style="max-width: 30px; max-height: 30px" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="icon-options-vertical"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">

        @foreach ($menuItems as $key => $menuItem)
            @php

                $check = false;
                if (
                    (!isset($menuItem['permissions']) ||
                        !is_array($menuItem['permissions']) ||
                        count($menuItem['permissions']) == 0 ||
                        !admin()->hasAnyPermission($menuItem['permissions'])) &&
                    !isSuperAdmin()
                ) {
                    continue;
                } elseif (
                    (isset($menuItem['permissions']) &&
                        is_array($menuItem['permissions']) &&
                        admin()->hasAnyPermission($menuItem['permissions'])) ||
                    isSuperAdmin()
                ) {
                    $check = true;
                }

                $parameterArray = isset($menuItem['params']) ? $menuItem['params'] : [];
                if (!isset($menuItem['routeName']) || $menuItem['routeName'] == '' || $menuItem['routeName'] == null) {
                    continue;
                } elseif ($menuItem['routeName'] == 'javascript:void(0)') {
                    $check = true;
                    $route = 'javascript:void(0)';
                } else {
                    $check = true;
                    $route = route($menuItem['routeName'], $parameterArray);
                }

                $delete = false;
                $div_id = '';

                if (isset($menuItem['delete']) && isset($menuItem['params'][0]) && $menuItem['delete'] == true) {
                    $div_id = 'delete-form-' . $menuItem['params'][0];
                    $delete = true;
                }
            @endphp
            @if ($check)
                <li>
                    <a target="{{ isset($menuItem['target']) ? $menuItem['target'] : '' }}"
                        title="{{ isset($menuItem['title']) ? $menuItem['title'] : '' }}"
                        href="{{ $delete == true ? 'javascript:void(0)' : $route }}"
                        @if ($delete == true) onclick="confirmDelete(() => document.getElementById('{{ $div_id }}').submit())" @endif
                        class="dropdown-item {{ isset($menuItem['className']) ? $menuItem['className'] : '' }}"
                        @if (isset($menuItem['data-id'])) data-id="{{ $menuItem['data-id'] }}" @endif>{{ __($menuItem['label']) }}</a>
                    @if ($delete == true)
                        <form id="delete-form-{{ $menuItem['params'][0] }}" action="{{ $route }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</div>
