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
    FlexAahub\PLUGIN_VERSION,
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
         // Compute the absolute URL to the Asset folder (4 levels up from this file)
    $this->asset_base_url = plugin_dir_url(dirname(__FILE__, 3));
    $this->asset_base_prefix = 'ababilithub-flex-aahub-asset-';

    // (Optional) Remove debug echo when done testing
    // echo "<pre>"; print_r(array($this->asset_base_prefix, $this->asset_base_url, $this->asset_base_url.'Asset/Css/Theme/V1/Manager/Style.css')); echo "</pre>"; //exit;

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
            $this->asset_base_prefix.'app-framework-style', 
            $this->asset_base_url.'Asset/Presentation/Css/Framework/V1/Concrete/Ababilithub/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            $this->asset_base_prefix.'tab-component',
            $this->asset_base_url.'Asset/Presentation/Js/Component/V1/Concrete/Tab/V1/Tab.js',
            array(),
            time(),
            true
        );

        // wp_enqueue_style(
        //     $this->asset_base_prefix.'app-theme-style', 
        //     $this->asset_base_url.'Asset/Css/Layo/V1/Manager/Style.css',
        //     array(), 
        //     time()
        // );

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
