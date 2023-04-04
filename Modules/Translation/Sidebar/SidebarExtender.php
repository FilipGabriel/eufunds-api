<?php

namespace Modules\Translation\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.system'), function (Group $group) {
            $group->weight(15);

            $group->item(trans('admin::sidebar.miscellaneous'), function (Item $item) {
                $item->icon('fa fa-paint-brush');
                
                $item->item(trans('translation::sidebar.translations'), function (Item $item) {
                    $item->weight(20);
                    $item->route('admin.translations.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.translations.index')
                    );
                });
            });
        });
    }
}
