<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Template\V1\Factory\Template as TemplateFactory,
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract,
    FlexAahub\Package\Plugin\Template\V1\Concrete\List\PremiumCard\Template as PremiumCardListTemplate,
    
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
            PremiumCardListTemplate::class,
            // Add more classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $itemInstasnce = TemplateFactory::get($itemClass);

            if ($itemInstasnce instanceof TemplateContract) 
            {
                $itemInstasnce->register();
            }
        }
    }
}
