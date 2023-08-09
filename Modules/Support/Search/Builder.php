<?php

namespace Modules\Support\Search;

use Illuminate\Support\Facades\Schema;

class Builder
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * The scout builder instance.
     *
     * @var \Laravel\Scout\Builder
     */
    private $scoutBuilder;

    /**
     * Keys of search results.
     *
     * @var array
     */
    private $keys = [];

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Laravel\Scout\Builder $scoutBuilder
     * @return void
     */
    public function __construct($model, $scoutBuilder)
    {
        $this->model = $model;
        $this->scoutBuilder = $scoutBuilder;
    }

    /**
     * Apply filter to the search results.
     *
     * @return mixed
     */
    public function filter($filter)
    {
        return $filter->apply($this->query());
    }

    /**
     * Get the query builder of the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = $this->model->whereIn($this->model->getQualifiedKeyName(), $this->keys())
            ->orWhereHas('translations', function($query) {
                $searchQuery = preg_replace("/[^A-Za-z0-9]+/i", " ", request('query'));

                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->when(Schema::hasColumn($this->model->getTable(), 'sku'), function($query) {
                $query->orWhere('sku', request('query'));
            });

        if ($this->shouldOrderByRelevance()) {
            $this->orderByRelevance($query);
        }

        return $query;
    }

    /**
     * Get keys of search result.
     *
     * @return \Illuminate\Support\Collection
     */
    public function keys()
    {
        if (empty($this->keys)) {
            $this->keys = $this->scoutBuilder->keys();
        }

        return $this->keys;
    }

    /**
     * Determine if query should order by relevance.
     *
     * @return bool
     */
    private function shouldOrderByRelevance()
    {
        return ! request()->has('sort') || request('sort') === 'relevance';
    }

    /**
     * Order query by relevance.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return void
     */
    private function orderByRelevance($query)
    {
        $ids = $this->keys()->filter();

        if ($ids->isNotEmpty()) {
            $query->orderByRaw("FIELD({$this->model->getQualifiedKeyName()}, {$ids->implode(',')})");
        }
    }

    /**
     * Get the results of the search.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->query()->get();
    }
}
