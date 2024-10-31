@if (isset($datas['errors']) && isset($datas['field']))
    @if ($datas['errors']->has($datas['field']))
        @if (count($datas['errors']->get($datas['field'])) > 1)
            @foreach ($datas['errors']->get($datas['field']) as $error)
                @if (is_array($error))
                    @foreach ($error as $er)
                        <span class="invalid-feedback d-block" role="alert">{{ $er }}</span>
                    @endforeach
                @else
                    <span class="invalid-feedback d-block" role="alert">{{ $error }}</span>
                @endif
            @endforeach
        @else
            <span class="invalid-feedback d-block" role="alert">{{ $datas['errors']->first($datas['field']) }}</span>
        @endif
    @endif
@endif
