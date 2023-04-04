<?php

namespace Modules\Program\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.content'), function (Group $group) {
            $group->item(trans('program::programs.programs'), function (Item $item) {
                $item->weight(9);
                $item->icon('fa fa-list-alt');
                $item->route('admin.programs.index');
                $item->authorize(
                    $this->auth->hasAccess('admin.programs.index')
                );
            });
        });
    }
}
