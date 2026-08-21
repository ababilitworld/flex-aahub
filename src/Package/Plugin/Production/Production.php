<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Production;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexAahub\Package\Plugin\Menu\V1\Manager\Menu as MenuManager,
    FlexAahub\Package\Plugin\Asset\V1\Manager\Asset as AssetManager, 
    FlexAahub\Package\Plugin\Query\V1\Manager\Query as QueryManager,
    FlexAahub\Package\Plugin\Pagination\V1\Manager\Pagination as PaginationManager, 
    FlexAahub\Package\Plugin\Template\V1\Manager\Template as TemplateManager,

};

if (!class_exists(__NAMESPACE__.'\Production')) 
{
    class Production 
    {
        use StandardMixin;

        public function __construct($data = []) 
        {
            $this->init();      
        }

        public function init() 
        {
            add_action('init', function () {
                (new AssetManager())->boot();
            });

            add_action('init', function () {
                (new QueryManager())->boot();
            });

            add_action('init', function () {
                (new PaginationManager())->boot();
            });

            add_action('init', function () {
                (new TemplateManager())->boot();
            });
        }
        
    }
}