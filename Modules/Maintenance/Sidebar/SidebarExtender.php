<?php

namespace Modules\Maintenance\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.system'), function (Group $group) {
            $group->item(trans('maintenance::sidebar.maintenance'), function (Item $item) {
                $item->icon('fa fa-cog');
                $item->weight(50);
                $item->authorize(
                    $this->auth->hasAnyAccess(['admin.maintenance.index'])
                );

                $item->item(trans('maintenance::sidebar.info'), function (Item $item) {
                    $item->weight(5);
                    $item->route('admin.maintenance.phpinfo');
                    $item->authorize(
                        $this->auth->hasAnyAccess(['admin.maintenance.index'])
                    );
                });

                $item->item(trans('maintenance::sidebar.logs'), function (Item $item) {
                    $item->weight(5);
                    $item->route('admin.maintenance.logs');
                    $item->authorize(
                        $this->auth->hasAnyAccess(['admin.maintenance.index'])
                    );
                });
            });
        });
    }
}
