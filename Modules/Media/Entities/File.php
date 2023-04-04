<?php

namespace Modules\Media\Entities;

use Modules\Media\IconResolver;
use Modules\User\Entities\User;
use Modules\Media\Admin\MediaTable;
use Modules\Support\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = ['id', 'filename', 'path', 'location'];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($file) {
            Storage::disk($file->disk)->delete($file->getRawOriginal('path'));
        });
    }

    /**
     * Get the user that uploaded the file.
     *
     * @return void
     */
    public function uploader()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file's path.
     *
     * @param string $path
     * @return string|null
     */
    public function getPathAttribute($path)
    {
        if (! is_null($path)) {
            return Storage::disk($this->disk)->url($path);
        }
    }

    /**
     * Get file's real path.
     *
     * @return void
     */
    public function realPath()
    {
        if (! is_null($this->attributes['path'])) {
            return Storage::disk($this->disk)->path($this->attributes['path']);
        }
    }

    /**
     * Determine if the file type is image.
     *
     * @return bool
     */
    public function isImage()
    {
        return strtok($this->mime, '/') === 'image';
    }

    /**
     * Get the file's icon.
     *
     * @return string
     */
    public function icon()
    {
        return IconResolver::resolve($this->mime);
    }

    /**
     * Get table data for the resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function table($request)
    {
        parse_str(url()->previous(), $output);

        $location = $output['location'] ?? 'appfront';

        if(request()->routeIs('admin.ftp.index')) {
            $location = 'ftp';
        }

        $query = $this->newQuery()
            ->when(! is_null($request->type) && $request->type !== 'null', function ($query) use ($request) {
                $query->where('mime', 'LIKE', "{$request->type}/%");
            })->when($location !== '', function ($query) use ($location) {
                $query->whereLocation($location);
            });

        return new MediaTable($query);
    }

    public function updateFile($params)
    {
        $location = $params['location'];
        $file = $params['file'];

        $disk = $params['disk'] ?? config('filesystems.default');
        $path = $params['path'] ?? "media/{$location}";

         $this->update([
            'user_id' => $params['user_id'],
            'disk' => $disk,
            'location' => $location,
            'filename' => $file->getClientOriginalName(),
            'path' => Storage::disk($disk)->putFile($path, $file),
            'extension' => $file->guessClientExtension() ?? '',
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return $this;
    }

    public static function uploadFile($params, $model, $zone,$events = true)
    {
        $location = $params['location'];
        $file = $params['file'];

        $disk = $params['disk'] ?? config('filesystems.default');
        $path = $params['path'] ?? "media/{$location}";

        $newFile = static::create([
            'user_id' => $params['user_id'],
            'disk' => $disk,
            'location' => $location,
            'filename' => $file->getClientOriginalName(),
            'path' => Storage::disk($disk)->putFile($path, $file),
            'extension' => $file->guessClientExtension() ?? '',
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        if(! $events) {
            $model->withoutEvents(function () use ($model, $newFile, $zone) {
                $model->files()->attach($newFile, ['zone' => $zone, 'entity_type' => get_class($model)]);
            });
        } else {
            $model->files()->attach($newFile, ['zone' => $zone, 'entity_type' => get_class($model)]);
        }

        return $newFile;
    }

    /**
     * Add file number if duplicate (windows like)
     *
     * @param string $fileName
     * @param array $existingFiles
     * @param bool $usePregMatch
     * @return string
     */
    public static function getFilename(string $fileName, array $existingFiles, bool $usePregMatch = true): string
    {
        if (in_array($fileName, $existingFiles)) {
            //already got a numbered file
            if ($usePregMatch && preg_match_all(
                "/\((\d+)\)/",
                $fileName,
                $matches,
                PREG_SET_ORDER | PREG_OFFSET_CAPTURE
            )) {
                $lastMatch = $matches[count($matches) - 1][count($matches[count($matches) - 1]) - 1];
                $start = rtrim(substr($fileName, 0, $lastMatch[1] - 1));
                $end = substr($fileName, $lastMatch[1] + strlen($lastMatch[0]) + 1);
                $fileNumber = (int)$lastMatch[0] + 1;

                // no numbered file but we have an extension
            } elseif (false !== $position = strrpos($fileName, '.')) {
                $start = substr($fileName, 0, $position);
                $end = substr($fileName, $position);
                $fileNumber = 1;

                // not already numbered and no extension
            } else {
                $start = $fileName;
                $end = "";
                $fileNumber = 1;
            }

            while (in_array("{$start} ({$fileNumber}){$end}", $existingFiles)) {
                $fileNumber++;
            }

            return "{$start} ({$fileNumber}){$end}";
        }

        return $fileName;
    }
}
