<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
    FlexWordpress\Package\Query\V1\Factory\Query as QueryFactory,

    FlexAahub\Package\Plugin\Query\V1\Concrete\PostType\Query as PosttypeQuery,
    FlexAahub\Package\Plugin\Query\V1\Concrete\Taxonomy\Query as TaxonomyQuery,
    FlexAahub\Package\Plugin\Query\V1\Concrete\PremiumCard\Query as PremiumCardQuery,
    FlexAahub\Package\Plugin\Query\V1\Concrete\Masonry\Query as MasonryQuery,
    FlexAahub\Package\Plugin\Query\V1\Concrete\Table\Query as TableQuery,
};

class Query extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            PosttypeQuery::class,
            TaxonomyQuery::class,

            // Enable/disable implementations here.
        ]);
    }

    public function boot(): void
    {
        foreach ($this->get_items() as $item_class) 
        {
            $template = QueryFactory::get( $item_class );

            if (!$template instanceof QueryContract) 
            {
                continue;
            }

        }
    }
}