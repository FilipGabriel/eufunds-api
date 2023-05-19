<?php

namespace Modules\Brand\Admin;

use Modules\Admin\Ui\AdminTable;
use Modules\Brand\Entities\Brand;

class BrandTable extends AdminTable
{
    /**
     * Raw columns that will not be escaped.
     *
     * @var array
     */
    protected $rawColumns = ['is_searchable'];

    /**
     * Make table response for the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make()
    {
        return $this->newTable()
            ->addColumn('logo', function (Brand $brand) {
                return view('admin::partials.table.image', [
                    'file' => $brand->logo,
                ]);
            })
            ->editColumn('is_searchable', function ($entity) {
                return $entity->is_searchable
                    ? '<span class="dot green"></span>'
                    : '<span class="dot red"></span>';
            });
    }
}
