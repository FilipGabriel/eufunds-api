<?php

namespace Modules\Media\Http\Controllers\Admin;

use Modules\Media\Entities\File;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Traits\HasCrudActions;
use Modules\Media\Http\Requests\UploadMediaRequest;

class MediaController
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
    protected $label = 'media::media.media';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'media::admin.media';

    /**
     * Store a newly created media in storage.
     *
     * @param \Modules\Media\Http\Requests\UploadMediaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadMediaRequest $request)
    {
        $output = [];
        parse_str(url()->previous(), $output);
        
        $location = $output['location'] ?? 'appfront';
        $file = $request->file('file');

        if(request()->has('location') && request('location') == 'ftp') {
            $location = request('location');
            $disk = config('filesystems.disks.media_ftp.driver');
            $path = $file->getClientOriginalName();
            Storage::disk('media_ftp')->put($path, file_get_contents($file));
        } else {
            $disk = config('filesystems.default');
            $path = Storage::putFile("media/${location}", $file);
        }

        return File::create([
            'user_id' => auth()->id(),
            'disk' => $disk,
            'location' => $location,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'extension' => $file->guessClientExtension() ?? '',
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

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
