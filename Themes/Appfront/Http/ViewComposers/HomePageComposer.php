<?php

namespace Themes\Appfront\Http\ViewComposers;

use Themes\Appfront\Banner;
use Themes\Appfront\Feature;
use Illuminate\Support\Collection;
use Modules\Slider\Entities\Slider;
use Illuminate\Support\Facades\Cache;

class HomePageComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function compose($view)
    {
        $view->with([
            'slider' => Slider::findWithSlides(setting('appfront_slider')),
            'sliderBanners' => Banner::getSliderBanners(),
            'features' => Feature::all(),
            'threeColumnFullWidthBanners' => $this->threeColumnFullWidthBanners(),
            'twoColumnBanners' => $this->twoColumnBanners(),
            'threeColumnBanners' => $this->threeColumnBanners(),
            'oneColumnBanner' => $this->oneColumnBanner(),
        ]);
    }

    private function threeColumnFullWidthBanners()
    {
        if (setting('appfront_three_column_full_width_banners_enabled')) {
            return Banner::getThreeColumnFullWidthBanners();
        }
    }

    private function twoColumnBanners()
    {
        if (setting('appfront_two_column_banners_enabled')) {
            return Banner::getTwoColumnBanners();
        }
    }

    private function threeColumnBanners()
    {
        if (setting('appfront_three_column_banners_enabled')) {
            return Banner::getThreeColumnBanners();
        }
    }

    private function oneColumnBanner()
    {
        if (setting('appfront_one_column_banner_enabled')) {
            return Banner::getOneColumnBanner();
        }
    }
}
