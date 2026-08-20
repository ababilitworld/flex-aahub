<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\Pagination\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Template\V1\Factory\Template as TemplateFactory,
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract,
    FlexAahub\Package\Plugin\Template\Pagination\V1\Concrete\LoadMore\Template as LoadMorePaginationTemplate,
    FlexAahub\Package\Plugin\Template\Pagination\V1\Concrete\Paged\Template as PagedPaginationTemplate,
    FlexAahub\Package\Plugin\Template\Pagination\V1\Concrete\PreviousNext\Template as PreviousNextPaginationTemplate,
    
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
            LoadMorePaginationTemplate::class,
            PagedPaginationTemplate::class,
            PreviousNextPaginationTemplate::class,
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
                
            }
        }
    }
}
