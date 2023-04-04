<nav class="navbar navbar-static-top clearfix">
    <ul class="nav navbar-nav clearfix">
        <li class="visit-app hidden-sm hidden-xs">
            <a href="{{ config('app.frontend_domain') }}" target="_new">
                <i class="fa fa-desktop"></i>
                {{ trans('admin::admin.visit_app') }}
            </a>
        </li>

        <li class="dropdown top-nav-menu pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if ($currentUser->user_logo->exists)
                    <img src="{{ $currentUser->user_logo->path }}" alt="thumbnail">
                @else
                    <i class="fa fa-user-circle-o"></i>
                @endif
                <span>{{ $currentUser->name }}</span>
            </a>

            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.profile.edit') }}">{{ trans('user::users.profile') }}</a></li>
                <li><a href="{{ route('admin.logout') }}">{{ trans('user::auth.logout') }}</a></li>
            </ul>
        </li>

        @if (count(supported_locales()) > 1)
            <li class="language dropdown top-nav-menu pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span>{{ strtoupper(locale()) }}</span>
                </a>

                <ul class="dropdown-menu">
                    @foreach (supported_locales() as $locale => $language)
                        <li class="{{ $locale === locale() ? 'active' : '' }}">
                            <a href="{{ localized_url($locale) }}">{{ $language['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if($currentUser->hasAccess('admin.smis_notifications.index'))
        <li class="dropdown messages-menu pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <i class="fa fa-bell-o"></i>
                <span class="notifications-counter animated">{{ $notifications->count() }}</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <div class="slim-scroll">
                        <ul class="menu notifications-list">
                            @if($notifications->count() === 0)
                            <li class="no-notifications">
                                <h4 class="text-center p-t-15 p-b-15">
                                    {{ trans('notification::notifications.no_notifications') }}
                                </h4>
                            </li>
                            @endif
                            @foreach($notifications as $notification)
                            <li>
                                <div class="item" data-id="{{ $notification->id }}">
                                    <div class="notification-wrapper">
                                        <div class="notification-content">
                                            <div class="notification-title">
                                                <h5>{{ $notification->title }}</h5>
                                                <small class="text-right text-muted">
                                                    <i class="fa fa-clock-o"></i> {{ $notification->time_ago }}
                                                </small>
                                            </div>
                                            <span class="notification-body">{!! $notification->message !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($notifications->count() > 0)
                    <li class="view-all text-center">
                        <a href="javascript::void(0)">{!! trans('notification::notifications.view_all') !!}</a>
                    </li>
                    @endif
                </li>
            </ul>
        </li>
        @endif
    </ul>
</nav>
