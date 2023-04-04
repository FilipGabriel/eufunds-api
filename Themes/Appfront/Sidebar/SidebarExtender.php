<?php

namespace Themes\Appfront\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        // $menu->group(trans('admin::sidebar.system'), function (Group $group) {
        //     $group->item(trans('admin::sidebar.miscellaneous'), function (Item $item) {
        //         $item->item(trans('appfront::sidebar.appfront'), function (Item $item) {
        //             $item->weight(100);
        //             $item->route('admin.appfront.settings.edit');
        //             $item->authorize(
        //                 $this->auth->hasAccess('admin.appfront.edit')
        //             );
        //         });
        //     });
        // });
    }
}
