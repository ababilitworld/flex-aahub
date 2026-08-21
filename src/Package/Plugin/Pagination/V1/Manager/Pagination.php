<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Pagination\V1\Contract\Pagination as PaginationContract,
    FlexWordpress\Package\Pagination\V1\Factory\Pagination as PaginationFactory,

    FlexAahub\Package\Plugin\Pagination\V1\Concrete\Paged\Pagination as PagedPagination,
    FlexAahub\Package\Plugin\Pagination\V1\Concrete\PreviousNext\Pagination as PreviousNextPagination,
    FlexAahub\Package\Plugin\Pagination\V1\Concrete\LoadMore\Pagination as LoadMorePagination,
    
};

class Pagination extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            PagedPagination::class,
            PreviousNextPagination::class,
            LoadMorePagination::class,

            // Enable/disable implementations here.
        ]);
    }

    public function boot(): void
    {
        foreach ($this->get_items() as $item_class) 
        {
            $template = PaginationFactory::get( $item_class );

            if (!$template instanceof PaginationContract) 
            {
                continue;
            }

        }
    }
}