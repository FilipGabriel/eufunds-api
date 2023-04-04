<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\User\Entities\UserDevice;
use Modules\Admin\Traits\HasCrudActions;

class UserDevicesController extends Controller
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = UserDevice::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'user::user_devices.user_devices';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'user::admin.user_devices';
}
