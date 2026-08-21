<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Asset\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Asset\V1\Factory\Asset as AssetFactory,
    FlexWordpress\Package\Asset\V1\Contract\Asset as AssetContract,
    FlexAahub\Package\Plugin\Asset\V1\Concrete\Framework\Asset as FrameworkAsset,
    
};

class Asset extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            FrameworkAsset::class,
            // Add more classes here...
        ]);
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $itemClass) 
        {
            $itemInstasnce = AssetFactory::get($itemClass);

            if ($itemInstasnce instanceof AssetContract) 
            {
                $itemInstasnce->register();
            }
        }
    }
}
