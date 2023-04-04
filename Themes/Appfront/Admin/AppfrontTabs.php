<?php

namespace Themes\Appfront\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;
use Modules\Media\Entities\File;
use Illuminate\Support\Facades\Cache;

class AppfrontTabs extends Tabs
{
    /**
     * Make new tabs with groups.
     *
     * @return void
     */
    public function make()
    {
        $this->group('general_settings', trans('appfront::appfront.tabs.group.general_settings'))
            ->active()
            ->add($this->general())
            ->add($this->logo())
            ->add($this->footer())
            ->add($this->newsletter())
            ->add($this->features())
            ->add($this->socialLinks());
    }

    private function general()
    {
        return tap(new Tab('general', trans('appfront::appfront.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);
            $tab->fields(['appfront_slider', 'appfront_copyright_text']);
            $tab->view('admin.appfront.tabs.general');
        });
    }

    private function logo()
    {
        return tap(new Tab('logo', trans('appfront::appfront.tabs.logo')), function (Tab $tab) {
            $tab->weight(10);
            $tab->view('admin.appfront.tabs.logo', [
                'favicon' => $this->getMedia(setting('appfront_favicon')),
                'headerLogo' => $this->getMedia(setting('appfront_header_logo')),
                'footerLogo' => $this->getMedia(setting('appfront_footer_logo')),
                'mailLogo' => $this->getMedia(setting('appfront_mail_logo')),
            ]);
        });
    }

    private function footer()
    {
        return tap(new Tab('footer', trans('appfront::appfront.tabs.footer')), function (Tab $tab) {
            $tab->weight(17);
            $tab->view('admin.appfront.tabs.footer', [
                'acceptedPaymentMethodsImage' => $this->getMedia(setting('appfront_accepted_payment_methods_image')),
            ]);
        });
    }

    private function newsletter()
    {
        if (! setting('newsletter_enabled')) {
            return;
        }

        return tap(new Tab('newsletter', trans('appfront::appfront.tabs.newsletter')), function (Tab $tab) {
            $tab->weight(18);
            $tab->view('admin.appfront.tabs.newsletter', [
                'newsletterBgImage' => $this->getMedia(setting('appfront_newsletter_bg_image')),
            ]);
        });
    }

    private function getMedia($fileId)
    {
        return Cache::rememberForever(md5("files.{$fileId}"), function () use ($fileId) {
            return File::findOrNew($fileId);
        });
    }

    private function features()
    {
        return null;
        return tap(new Tab('features', trans('appfront::appfront.tabs.features')), function (Tab $tab) {
            $tab->weight(20);
            $tab->view('admin.appfront.tabs.features');
        });
    }

    private function socialLinks()
    {
        return tap(new Tab('social_links', trans('appfront::appfront.tabs.social_links')), function (Tab $tab) {
            $tab->weight(25);

            $tab->fields([
                'appfront_fb_link',
                'appfront_twitter_link',
                'appfront_instagram_link',
                'appfront_linkedin_link',
                'appfront_pinterest_link',
                'appfront_gplus_link',
                'appfront_youtube_link',
            ]);

            $tab->view('admin.appfront.tabs.social_links');
        });
    }
}
