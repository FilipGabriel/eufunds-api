<?php

namespace Modules\Product\Entities;

use Modules\Support\Money;
use Modules\Media\Entities\File;
use Modules\Brand\Entities\Brand;
use Illuminate\Support\Facades\DB;
use Modules\Coupon\Entities\Coupon;
use Modules\Option\Entities\Option;
use Modules\Support\Eloquent\Model;
use Modules\Media\Eloquent\HasMedia;
use Modules\Program\Entities\Program;
use Modules\Meta\Eloquent\HasMetaData;
use Modules\Support\Search\Searchable;
use Modules\Category\Entities\Category;
use Modules\Product\Admin\ProductTable;
use Modules\Support\Eloquent\Sluggable;
use Illuminate\Support\Facades\Artisan;
use Modules\Support\Eloquent\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attribute\Entities\ProductAttribute;

class Product extends Model
{
    use Translatable,
        Searchable,
        Sluggable,
        HasMedia,
        HasMetaData,
        SoftDeletes;

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
    protected $fillable = [
        'nod_id',
        'brand_id',
        'warranty',
        'slug',
        'sku',
        'rma',
        'documents',
        'price',
        'ps_price',
        'special_price',
        'special_price_type',
        'special_price_start',
        'special_price_end',
        'selling_price',
        'manage_stock',
        'shipping',
        'qty',
        'supplier_stock',
        'supplier_stock_date',
        'reserved_stock',
        'in_stock',
        'virtual',
        'is_active',
        'new_from',
        'new_to',
        'special_price_valid_to',
        'is_on_demand_only',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'documents' => 'array',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_on_demand_only' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'special_price_start',
        'special_price_end',
        'new_from',
        'new_to',
        'special_price_valid_to',
        'supplier_stock_date',
        'start_date',
        'end_date',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'base_image', 'is_in_stock', 'is_out_of_stock'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = ['name', 'description', 'short_description'];

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
        static::saved(function ($product) {
            if (! empty(request()->all())) {
                $product->saveRelations(request()->all());
            }

            $product->withoutEvents(function () use ($product) {
                $product->update(['selling_price' => $product->getSellingPrice()->amount()]);
            });

            if(request('is_active') && $product->nod_id) {
                Artisan::call("nod:import-product-info", ['nodId' => $product->nod_id]);
            }
        });

        static::addActiveGlobalScope();
    }

    public static function newArrivals($limit)
    {
        return static::forCard()
            ->latest()
            ->take($limit)
            ->get();
    }

    public static function list($ids = [])
    {
        return static::select('id')
            ->withName()
            ->whereIn('id', $ids)
            ->when(! empty($ids), function ($query) use ($ids) {
                $idsString = collect($ids)->filter()->implode(',');

                $query->orderByRaw("FIELD(id, {$idsString})");
            })
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->name];
            });
    }

    public function scopeForCard($query)
    {
        $query->withName()
            ->withBaseImage()
            ->withPrice()
            ->withCount('options')
            ->addSelect([
                'products.id',
                'products.slug',
                'products.in_stock',
                'products.manage_stock',
                'products.sku',
                'products.qty',
                'products.reserved_stock',
                'products.supplier_stock',
                'products.supplier_stock_date',
                'products.is_on_demand_only',
            ]);
    }

    public function scopeWithPrice($query)
    {
        $query->addSelect([
            'products.price',
            'products.ps_price',
            'products.special_price',
            'products.special_price_type',
            'products.selling_price',
            'products.special_price_start',
            'products.special_price_end',
            'products.special_price_valid_to',
        ]);
    }

    public function scopeWithName($query)
    {
        $query->with('translations:id,product_id,locale,name');
    }

    public function scopeWithBaseImage($query)
    {
        $query->with(['files' => function ($q) {
            $q->wherePivot('zone', 'base_image');
        }]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'product_programs');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'product_options')
            ->orderBy('position')
            ->withTrashed();
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(static::class, 'related_products', 'product_id', 'related_product_id');
    }

    public function upSellProducts()
    {
        return $this->belongsToMany(static::class, 'up_sell_products', 'product_id', 'up_sell_product_id');
    }

    public function filter($filter)
    {
        return $filter->apply($this);
    }

    public function getPriceAttribute($price)
    {
        return Money::inDefaultCurrency($price);
    }

    public function getSpecialPriceAttribute($specialPrice)
    {
        if (! is_null($specialPrice)) {
            return Money::inDefaultCurrency($specialPrice);
        }
    }

    public function getPsPriceAttribute($psPrice)
    {
        if (! is_null($psPrice)) {
            return Money::inDefaultCurrency($psPrice);
        }
    }

    public function getSellingPriceAttribute($sellingPrice)
    {
        return Money::inDefaultCurrency($sellingPrice);
    }

    public function getTotalAttribute($total)
    {
        return Money::inDefaultCurrency($total);
    }

    /**
     * Get the product's base image.
     *
     * @return \Modules\Media\Entities\File
     */
    public function getBaseImageAttribute()
    {
        return $this->files->where('pivot.zone', 'base_image')->first() ?: new File;
    }

    /**
     * Get product's additional images.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdditionalImagesAttribute()
    {
        return $this->files
            ->where('pivot.zone', 'additional_images')
            ->sortBy('pivot.id');
    }

    /**
     * Get product's downloadable files.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDownloadsAttribute()
    {
        return $this->files
            ->where('pivot.zone', 'downloads')
            ->sortBy('pivot.id')
            ->flatten();
    }

    public function getFormattedPriceAttribute()
    {
        return product_price_formatted($this);
    }

    public function getIsInStockAttribute()
    {
        return $this->isInStock();
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->isOutOfStock();
    }

    public function getHasPercentageSpecialPriceAttribute()
    {
        return $this->hasPercentageSpecialPrice();
    }

    public function getSpecialPricePercentAttribute()
    {
        return $this->getSpecialPricePercent();
    }

    public function getAttributeSetsAttribute()
    {
        return $this->getAttribute('attributes')->groupBy('attributeSet');
    }

    public function url()
    {
        return route('products.show', ['slug' => $this->slug]);
    }

    public function isInStock()
    {
        // $this->manage_stock && 
        if ($this->qty === 0) {
            return false;
        }

        return $this->in_stock;
    }

    public function isOutOfStock()
    {
        return ! $this->isInStock();
    }

    public function markAsInStock()
    {
        $this->withoutEvents(function () {
            $this->update(['in_stock' => true]);
        });
    }

    public function markAsOutOfStock()
    {
        $this->withoutEvents(function () {
            $this->update(['in_stock' => false]);
        });
    }

    public function hasAnyAttribute()
    {
        return $this->getAttribute('attributes')->isNotEmpty();
    }

    public function hasAnyOption()
    {
        return $this->options->isNotEmpty();
    }

    public function getSellingPrice()
    {
        $sellingPrice = $this->hasSpecialPrice() ? $this->getSpecialPrice() : $this->price;

        $sellingPrice = $this->applyProgramDiscounts($sellingPrice);
        $sellingPrice = $this->applyCategoryDiscounts($sellingPrice);
        $sellingPrice = $this->applyUserDiscount($sellingPrice);

        return $sellingPrice;
    }

    public function getHasDnshAttribute()
    {
        $programDnsh = $categoryDnsh = false;
        $program = Program::findBySlug(request('program'));
        $dnshCoupon = Coupon::findByCode('product-dnsh');
        $categoryIds = $this->categories->pluck('id');

        foreach($this->getCouponsByProgram($program->id) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $dnshCoupon && $coupon && $coupon->valid() && ! $coupon->usageLimitReached() && $couponId == $dnshCoupon->id &&
                ! $coupon->perCustomerUsageLimitReached() && ! $coupon->excludePrograms->contains($program->id)
            ) {
                $programDnsh = true;
            }
        }
        
        foreach($this->getCouponsByCategory($categoryIds) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $dnshCoupon && $coupon && $coupon->valid() && ! $coupon->usageLimitReached() && ! $coupon->perCustomerUsageLimitReached() &&
                $coupon->excludeCategories->intersect($this->categories)->isEmpty() && $couponId == $dnshCoupon->id
            ) {
                $categoryDnsh = true;
            }
        }

        return $programDnsh || $categoryDnsh || $this->hasAnyOption();
    }

    private function applyProgramDiscounts($sellingPrice)
    {
        $program = Program::findBySlug(request('program'));

        foreach($this->getCouponsByProgram($program->id) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() &&
                ! $coupon->perCustomerUsageLimitReached() && ! $coupon->excludePrograms->contains($program->id)
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($this->price));
            }
        }

        return $sellingPrice;
    }

    private function applyCategoryDiscounts($sellingPrice)
    {
        $categoryIds = $this->categories->pluck('id');
        
        foreach($this->getCouponsByCategory($categoryIds) as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() && ! $coupon->perCustomerUsageLimitReached() &&
                $coupon->excludeCategories->intersect($this->categories)->isEmpty()
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($this->price));
            }
        }

        return $sellingPrice;
    }

    private function applyUserDiscount($sellingPrice)
    {
        foreach($this->getCouponsByUser() as $couponId) {
            $coupon = Coupon::find($couponId);

            if(
                $coupon && $coupon->valid() && ! $coupon->usageLimitReached() &&
                ! $coupon->perCustomerUsageLimitReached() && ! $coupon->excludeUsers->contains(auth()->id())
            ) {
                $sellingPrice = $sellingPrice->subtract($coupon->getCalculatedValue($this->price));
            }
        }

        return $sellingPrice;
    }

    public function getCouponsByProgram($id)
    {
        return DB::table('coupon_programs')
            ->where(function($query) use ($id) {
                $query->whereExclude(false)->whereProgramId($id);
            })->orWhere('exclude', true)
            ->distinct()->pluck('coupon_id');
    }

    public function getCouponsByCategory($ids)
    {
        return DB::table('coupon_categories')
            ->where(function($query) use ($ids) {
                $query->whereExclude(false)->whereIn('category_id', $ids);
            })->orWhere('exclude', true)
            ->distinct()->pluck('coupon_id');
    }

    public function getCouponsByUser()
    {
        return DB::table('coupon_users')
            ->where(function($query) {
                $query->whereExclude(false)->where('user_id', auth()->id());
            })->orWhere('exclude', true)
            ->distinct()->pluck('coupon_id');
    }

    public function getSpecialPrice()
    {
        $specialPrice = $this->attributes['special_price'];

        if ($this->special_price_type === 'percent') {
            $discountedPrice = ($specialPrice / 100) * $this->attributes['price'];

            $specialPrice = $this->attributes['price'] - $discountedPrice;
        }

        if ($specialPrice < 0) {
            $specialPrice = 0;
        }

        return Money::inDefaultCurrency($specialPrice);
    }

    public function hasPercentageSpecialPrice()
    {
        return $this->hasSpecialPrice() && $this->special_price_type === 'percent';
    }

    public function getSpecialPricePercent()
    {
        if ($this->hasPercentageSpecialPrice()) {
            return round($this->special_price->amount(), 2);
        }
    }

    public function getRealStock()
    {
        if ($this->isInStock()) {
            $stock = "Stoc: {$this->qty}";

            if($this->reserved_stock) {
                $stock .= "\n Stoc rezervat: {$this->reserved_stock}";
            }

            return $stock;
        }
        
        if ($this->reserved_stock) {
            return "Stoc rezervat: {$this->reserved_stock}";
        } 
        
        $stock = "\n Stoc: indisponibil";

        if($this->supplier_stock) {
            $stock .= "\n {$this->supplier_stock} bucati disponibile de la {$this->supplier_stock_date->format('d.m.Y')}";
        } else if ($this->is_on_demand_only) {
            $stock .= " (Disponibil doar la cerere)";
        }

        return $stock;
    }

    public function hasSpecialPrice()
    {
        if (is_null($this->special_price)) {
            return false;
        }

        if ($this->hasSpecialPriceStartDate() && $this->hasSpecialPriceEndDate()) {
            return $this->specialPriceStartDateIsValid() && $this->specialPriceEndDateIsValid();
        }

        if ($this->hasSpecialPriceStartDate()) {
            return $this->specialPriceStartDateIsValid();
        }

        if ($this->hasSpecialPriceEndDate()) {
            return $this->specialPriceEndDateIsValid();
        }

        return true;
    }

    private function hasSpecialPriceStartDate()
    {
        return ! is_null($this->special_price_start);
    }

    private function hasSpecialPriceEndDate()
    {
        return ! is_null($this->special_price_end);
    }

    private function specialPriceStartDateIsValid()
    {
        return today() >= $this->special_price_start;
    }

    private function specialPriceEndDateIsValid()
    {
        return today() <= $this->special_price_end;
    }

    private function hasNewFromDate()
    {
        return ! is_null($this->new_from);
    }

    private function hasNewToDate()
    {
        return ! is_null($this->new_to);
    }

    private function newFromDateIsValid()
    {
        return today() >= $this->new_from;
    }

    private function newToDateIsValid()
    {
        return today() <= $this->new_to;
    }

    public function relatedProductList()
    {
        return $this->relatedProducts()
            ->withoutGlobalScope('active')
            ->pluck('related_product_id');
    }

    public function upSellProductList()
    {
        return $this->upSellProducts()
            ->withoutGlobalScope('active')
            ->pluck('up_sell_product_id');
    }

    public static function findBySlug($slug)
    {
        return self::with([
            'categories', 'attributes.attribute.attributeSet',
            'options', 'files', 'relatedProducts', 'upSellProducts',
        ])
        ->where('slug', $slug)
        ->firstOrFail();
    }

    public static function findByNodId($nodId)
    {
        return self::with([ 'options', 'categories', 'attributes.attribute.attributeSet' ])
            ->withoutGlobalScope('active')
            ->where('nod_id', $nodId)
            ->first();
    }

    public function clean()
    {
        return array_except($this->toArray(), [
            'description',
            'short_description',
            'translations',
            'categories',
            'files',
            'is_active',
            'in_stock',
            'brand_id',
            'tax_class',
            'tax_class_id',
            'viewed',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table($request)
    {
        $query = $this->newQuery()
            ->withoutGlobalScope('active')
            ->with('categories')
            ->withName()
            ->withBaseImage()
            ->withPrice()
            ->addSelect(['products.id', 'nod_id', 'sku', 'products.is_active', 'products.created_at'])
            ->when($request->has('except'), function ($query) use ($request) {
                $query->whereNotIn('id', explode(',', $request->except));
            })->when($request->has('category_id'), function ($query) use ($request) {
                $query->whereHas('categories', function ($categoryQuery) use ($request) {
                    $categoryQuery->whereId($request->category_id);
                });
            });

        return new ProductTable($query);
    }

    /**
     * Save associated relations for the product.
     *
     * @param array $attributes
     * @return void
     */
    public function saveRelations($attributes = [])
    {
        $this->programs()->sync(array_get($attributes, 'programs', []));
        $this->categories()->sync(array_get($attributes, 'categories', []));
        $this->upSellProducts()->sync(array_get($attributes, 'up_sells', []));
        $this->relatedProducts()->sync(array_get($attributes, 'related_products', []));
    }

    /**
     * Get the indexable data array for the product.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        // MySQL Full-Text search handles indexing automatically.
        if (config('scout.driver') === 'mysql') {
            return [];
        }

        $translations = $this->translations()
            ->withoutGlobalScope('locale')
            ->get(['name', 'description', 'short_description']);

        return ['id' => $this->id, 'translations' => $translations];
    }

    public function searchTable()
    {
        return 'product_translations';
    }

    public function searchKey()
    {
        return 'product_id';
    }

    public function searchColumns()
    {
        return ['name'];
    }
}
