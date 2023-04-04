<div class="users-analytics">
    <div class="grid-header clearfix">
        <h4>
            <i class="fa fa-line-chart" aria-hidden="true"></i>{{ trans('admin::dashboard.users_analytics_title') }}
        </h4>
    </div>

    <div class="canvas">
        <canvas class="chart" width="400" height="250"></canvas>
    </div>
</div>

@push('globals')
    <script>
        SMIS.langs['admin::dashboard.users_analytics.registered_users'] = '{{ trans('admin::dashboard.users_analytics.registered_users') }}';
        SMIS.langs['admin::dashboard.users_analytics.payments'] = '{{ trans('admin::dashboard.users_analytics.payments') }}';
        SMIS.langs['admin::dashboard.users_analytics.projects'] = '{{ trans('admin::dashboard.users_analytics.projects') }}';
    </script>
@endpush
