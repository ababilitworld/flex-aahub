<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Query\V1\Contract\Query as QueryContract,
    FlexWordpress\Package\Query\V1\Factory\Query as QueryFactory,
    FlexAahub\Package\Plugin\Query\V1\Concrete\PostType\Query as PostTypeQuery,
    FlexAahub\Package\Plugin\Query\V1\Concrete\Taxonomy\Query as TaxonomyQuery,
};

class Query extends BaseManager
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize query implementations.
     *
     * @return void
     */
    protected function init(): void
    {
        $this->set_items([
            PostTypeQuery::class,
            TaxonomyQuery::class,

            // Add more query implementations here.
        ]);
    }

    /**
     * Boot registered query implementations.
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->get_items() as $item_class) 
        {
            $query = QueryFactory::get( $item_class );

            if (!$query instanceof QueryContract) 
            {
                continue;
            }
        }
    }
}