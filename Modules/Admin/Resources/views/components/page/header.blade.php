@section('title')
    @isset($subtitle)
        {{  "{$subtitle} - {$title}" }}
    @else
        {{ $title }}
    @endisset
@endsection

@section('content_header')
    <ul class="content-header-left">
        <li><h3>{{ $title }}</h3></li>
        @if(isset($preview))
        <li class="link-preview">
            <a href="{{ $preview }}" target="_new">
                <i class="fa fa-eye"></i>
                {{ trans('admin::dashboard.preview') }}
            </a>
        </li>
        @endif
    </ul>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}">{{ trans('admin::dashboard.dashboard') }}</a></li>

        {{ $slot }}
    </ol>
@endsection
