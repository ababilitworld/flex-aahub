<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Asset\V1\Concrete\Theme;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
    FlexWordpress\Package\Asset\V1\Base\Asset as BaseAsset,
    FlexWordpress\Package\Notice\V1\Factory\Notice as NoticeFactory,
    FlexWordpress\Package\Notice\V1\Concrete\Transient\Notice as TransientNotice,
    FlexWordpress\Package\Query\V1\Cascade\Taxonomy\V1\Factory\Query as TaxonomyQueryFactory,
    FlexWordpress\Package\Template\V1\Factory\Template as TemplateFactory,
    FlexWordpress\Package\Template\V1\Concrete\List\Masonry\Template as MasonryListTemplate,
    FlexWordpress\Package\Template\V1\Concrete\List\PremiumCard\Template as PremiumCardListTemplate,
};

use const Ababilithub\{
    FlexAahub\PLUGIN_PRE_UNDS,
    FlexAahub\PLUGIN_PRE_HYPH,
};

class Asset extends BaseAsset
{
    public $asset_base_prefix;
    public $asset_base_url;

    use  StandardWpMixin;
    
    public function init($data = []): static
    {
        $this->init_hook();
        $this->init_service();
        return $this;
    }

    public function init_hook(): void
    {
        $this->asset_base_url = $this->get_url('../../../../Asset/');
        $this->asset_base_prefix = 'ababilithub-flex-aahub-theme-asset';

        echo "<pre>";print_r($this->asset_base_prefix);echo "</pre>";exit;

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));        
    }

    public function init_service(): void
    {
        //$this->template = TemplateFactory::get(MasonryListTemplate::class);
        $this->notice_board = NoticeFactory::get(TransientNotice::class);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-slider');

        // wp_enqueue_script(
        //     $this->asset_base_prefix.'app-script', 
        //     $this->asset_base_url.'App/Js/Script.js',
        //     array('jquery', 'jquery-ui-slider'), 
        //     time(), 
        //     true
        // );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-theme-style', 
            $this->asset_base_url.'Css/Theme/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-base-style', 
            $this->asset_base_url.'Css/Animation/V1/Base/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-bounce-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Bounce/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-fade-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Fade/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-flash-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Flash/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-flip-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Flip/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-pulse-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Pulse/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-ripple-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Ripple/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-rotate-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Rotate/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-shake-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Shake/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-shimmer-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Shimmer/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-slide-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Slide/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-animation-concrete-zoom-style', 
            $this->asset_base_url.'Css/Animation/V1/Concrete/Zoom/Animation.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-title-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Title/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-rating-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Rating/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-cover-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Cover/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-cover-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Cover/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-author-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Author/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-header-component-bookinfo-component-publisher-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Header/Component/BookInfo/Component/Publisher/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-sidebar-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Sidebar/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-sidebar-component-h2-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Sidebar/Component/H2/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-sidebar-component-accordion-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Sidebar/Component/Accordion/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-sidebar-component-search-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Sidebar/Component/Search/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-main-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Main/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-main-component-toggle-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Main/Component/Toggle/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-main-component-content-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Main/Component/Content/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-main-component-content-code-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Main/Component/Content/Code/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-template-page-layout-ebook-layoutitem-footer-style', 
            $this->asset_base_url.'Css/Template/Page/Layout/Ebook/LayoutItem/Footer/Style.css',
            array(), 
            time()
        );

        wp_enqueue_style(
            $this->asset_base_prefix.'app-component-table-style', 
            $this->asset_base_url.'Css/Component/Table/Style.css',
            array(), 
            time()
        );

        $this->add_js_module();

        
    }

    public function add_js_module()
    {

    }

    public function localize_script():void
    {
        wp_localize_script(
            $this->asset_base_prefix . '-app-module-script',
            'flexAahubLocalizeScriptObject',
            array(
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce' => wp_create_nonce($this->asset_base_prefix.'_nonce'),
            )
        );
    }

    public function register(): void
    {

    }
    
}