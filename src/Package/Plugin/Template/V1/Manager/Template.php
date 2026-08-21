<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract,
    FlexWordpress\Package\Template\V1\Factory\Template as TemplateFactory,

    FlexAahub\Package\Plugin\Template\V1\Concrete\List\Template as ListTemplate,
    FlexAahub\Package\Plugin\Template\V1\Concrete\Grid\Template as GridTemplate,
    FlexAahub\Package\Plugin\Template\V1\Concrete\PremiumCard\Template as PremiumCardTemplate,
    FlexAahub\Package\Plugin\Template\V1\Concrete\Masonry\Template as MasonryTemplate,
    FlexAahub\Package\Plugin\Template\V1\Concrete\Table\Template as TableTemplate,
};

class Template extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            ListTemplate::class,
            GridTemplate::class,
            PremiumCardTemplate::class,
            MasonryTemplate::class,
            TableTemplate::class,

            // Enable/disable implementations here.
        ]);
    }

    public function boot(): void
    {
        foreach ($this->get_items() as $item_class) 
        {
            $template = TemplateFactory::get( $item_class );

            if (!$template instanceof TemplateContract) 
            {
                continue;
            }

        }
    }
}