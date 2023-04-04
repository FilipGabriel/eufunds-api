<?php

namespace Modules\Media\Http\Controllers\Admin;

use Modules\Media\Entities\File;
use Modules\Admin\Traits\HasCrudActions;

class FtpController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'media::media.ftp';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'media::admin.ftp';

    /**
     * Remove the specified resources from storage.
     *
     * @param string $ids
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        File::find(explode(',', $ids))->each->delete();
    }
}
