<?php

namespace Modules\Program\Entities;

use Modules\Media\Entities\File;
use Modules\Support\Eloquent\Model;
use Modules\Media\Eloquent\HasMedia;
use Illuminate\Support\Facades\Cache;
use Modules\Category\Entities\Category;
use Modules\Support\Eloquent\Sluggable;
use Modules\Program\Traits\NestableTrait;
use Modules\Support\Eloquent\Translatable;

class Program extends Model
{
    use Translatable, Sluggable, HasMedia, NestableTrait;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'slug', 'position', 'is_searchable', 'is_active'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['translations'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_searchable' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = ['name'];

    /**
     * The attribute that will be slugged.
     *
     * @var string
     */
    protected $slugAttribute = 'name';

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($program) {
            if (! empty(request()->all())) {
                $program->saveRelations(request()->all());
            }
        });

        static::addActiveGlobalScope();
    }

    public static function findBySlug($slug)
    {
        return static::with('files')->where('slug', $slug)->firstOrNew([]);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'program_categories');
    }

    public function isRoot()
    {
        return $this->exists && is_null($this->parent_id);
    }

    public function url()
    {
        return route('programs.products.index', ['program' => $this->slug]);
    }

    public static function tree()
    {
        return Cache::tags('programs')
            ->rememberForever(md5('programs.tree:' . locale()), function () {
                return static::with('files')
                    ->orderByRaw('-position DESC')
                    ->get()
                    ->nest();
            });
    }

    public static function filtered($ids)
    {
        return Cache::tags('programs')
            ->rememberForever(md5('programs.tree:' . locale()), function () use ($ids) {
                return static::with('files')
                    ->whereIn('id',$ids)
                    ->orderByRaw('-position DESC')
                    ->get()
                    ->nest();
            });
    }

    public static function treeList()
    {
        return Cache::tags('programs')->rememberForever(md5('programs.tree_list:' . locale()), function () {
            return static::orderByRaw('-position DESC')
                ->get()
                ->nest()
                ->setIndent('¦–– ')
                ->listsFlattened('name');
        });
    }

    public static function searchable()
    {
        return Cache::tags('programs')
            ->rememberForever(md5('programs.searchable:' . locale()), function () {
                return static::where('is_searchable', true)
                    ->get()
                    ->map(function ($program) {
                        return [
                            'slug' => $program->slug,
                            'name' => $program->name,
                            'banner' => $program->banner->path ?? null,
                        ];
                    });
            });
    }

    public function getBannerAttribute()
    {
        return $this->files->where('pivot.zone', 'banner')->first() ?: new File;
    }

    public function forCard()
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function toArray()
    {
        $attributes = parent::toArray();

        if ($this->relationLoaded('files')) {
            $attributes += [
                'banner' => [
                    'id' => $this->banner->id,
                    'path' => $this->banner->path,
                    'exists' => $this->banner->exists,
                ],
            ];
        }

        return $attributes;
    }

    /**
     * Save associated relations for the product.
     *
     * @param array $attributes
     * @return void
     */
    public function saveRelations($attributes = [])
    {
        $this->categories()->sync(array_get($attributes, 'categories', []));
    }
}
