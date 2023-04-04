<?php

namespace Themes\Appfront\Http\ViewComposers;

use Mexitek\PHPColors\Color;
use Spatie\SchemaOrg\Schema;
use Modules\Menu\Entities\Menu;
use Modules\Page\Entities\Page;
use Modules\Media\Entities\File;
use Modules\Menu\MegaMenu\MegaMenu;
use Illuminate\Support\Facades\Cache;

class LayoutComposer
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
            'themeColor' => $this->getThemeColor(),
            'favicon' => $this->getFavicon(),
            'logo' => $this->getHeaderLogo(),
            'newsletterBgImage' => $this->getNewsletterBgImage(),
            'privacyPageUrl' => $this->getPrivacyPageUrl(),
            'primaryMenu' => $this->getPrimaryMenu(),
            'categoryMenu' => $this->getCategoryMenu(),
            'footerMenuOne' => $this->getFooterMenuOne(),
            'footerMenuTwo' => $this->getFooterMenuTwo(),
            'copyrightText' => $this->getCopyrightText(),
            'acceptedPaymentMethodsImage' => $this->getAcceptedPaymentMethodsImage(),
            'schemaMarkup' => $this->getSchemaMarkup(),
        ]);
    }

    private function getThemeColor()
    {
        try {
            return new Color(appfront_theme_color());
        } catch (\Exception $e) {
            return new Color('#0068e1');
        }
    }

    private function getFavicon()
    {
        return $this->getMedia(setting('appfront_favicon'))->path;
    }

    private function getHeaderLogo()
    {
        return $this->getMedia(setting('appfront_header_logo'))->path;
    }

    private function getNewsletterBgImage()
    {
        return $this->getMedia(setting('appfront_newsletter_bg_image'))->path;
    }

    private function getMedia($fileId)
    {
        return Cache::rememberForever(md5("files.{$fileId}"), function () use ($fileId) {
            return File::findOrNew($fileId);
        });
    }

    private function getPrivacyPageUrl()
    {
        return Cache::tags('settings')->rememberForever('privacy_page_url', function () {
            return Page::urlForPage(setting('appfront_privacy_page'));
        });
    }

    private function getPrimaryMenu()
    {
        return new MegaMenu(setting('appfront_primary_menu'));
    }

    private function getCategoryMenu()
    {
        return new MegaMenu(setting('appfront_category_menu'));
    }

    private function getFooterMenuOne()
    {
        return $this->getFooterMenu(setting('appfront_footer_menu_one'));
    }

    private function getFooterMenuTwo()
    {
        return $this->getFooterMenu(setting('appfront_footer_menu_two'));
    }

    private function getFooterMenu($menuId)
    {
        return Cache::tags(['menu_items', 'categories', 'pages', 'settings'])
            ->rememberForever(md5("appfront_footer_menu.{$menuId}:" . locale()), function () use ($menuId) {
                return Menu::for($menuId);
            });
    }

    private function getCopyrightText()
    {
        return strtr(setting('appfront_copyright_text'), [
            '{{ app_url }}' => route('home'),
            '{{ app_name }}' => setting('app_name'),
            '{{ year }}' => date('Y'),
        ]);
    }

    private function getAcceptedPaymentMethodsImage()
    {
        return $this->getMedia(setting('appfront_accepted_payment_methods_image'));
    }

    private function getSchemaMarkup()
    {
        return Schema::webSite()
            ->url(route('home'))
            ->potentialAction($this->searchActionSchema());
    }

    private function searchActionSchema()
    {
        return Schema::searchAction()
            ->target('' . '?query={search_term_string}')
            ->setProperty('query-input', 'required name=search_term_string');
    }
}
