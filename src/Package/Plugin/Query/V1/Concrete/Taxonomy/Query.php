<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\Taxonomy;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Query\V1\Base\Query as BaseQuery;

class Query extends BaseQuery
{
    /**
     * Query type.
     *
     * @var string
     */
    protected string $type = 'taxonomy';

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $default_config = [
        /*
         * Taxonomy.
         */
        'taxonomy' => '',

        /*
         * Number of terms.
         */
        'number' => 10,

        /*
         * Pagination.
         */
        'paged' => 1,

        /*
         * Ordering.
         */
        'orderby' => 'name',
        'order'   => 'ASC',

        /*
         * Search.
         */
        'search' => '',

        /*
         * Parent.
         */
        'parent' => null,

        /*
         * Include / exclude.
         */
        'include' => [],
        'exclude' => [],

        /*
         * Hide empty terms.
         */
        'hide_empty' => true,

        /*
         * Meta query.
         */
        'meta_query' => [],

        /*
         * Object/post-type filtering.
         *
         * This is useful when your taxonomy query needs to
         * return terms associated with specific post types.
         */
        'object_ids' => [],
    ];

    /**
     * Prepare WordPress taxonomy query arguments.
     *
     * @return array
     */
    public function prepare_args(): array
    {
        $config = $this->get_config();

        $args = [
            'taxonomy'   => $config['taxonomy'],
            'number'     => (int) $config['number'],
            'paged'      => (int) $config['paged'],
            'orderby'    => $config['orderby'],
            'order'      => $config['order'],
            'hide_empty' => (bool) $config['hide_empty'],
        ];

        /*
         * Search.
         */
        if (!empty($config['search'])) {
            $args['search'] = $config['search'];
        }

        /*
         * Parent.
         */
        if ($config['parent'] !== null && $config['parent'] !== '') {
            $args['parent'] = absint($config['parent']);
        }

        /*
         * Include terms.
         */
        if (!empty($config['include'])) {
            $args['include'] = array_map(
                'absint',
                (array) $config['include']
            );
        }

        /*
         * Exclude terms.
         */
        if (!empty($config['exclude'])) {
            $args['exclude'] = array_map(
                'absint',
                (array) $config['exclude']
            );
        }

        /*
         * Term meta query.
         */
        if (!empty($config['meta_query'])) {
            $args['meta_query'] = $config['meta_query'];
        }

        /*
         * Object IDs.
         */
        if (!empty($config['object_ids'])) {
            $args['object_ids'] = array_map(
                'absint',
                (array) $config['object_ids']
            );
        }

        return $args;
    }

    /**
     * Create the WordPress taxonomy query.
     *
     * @param array $args
     *
     * @return \WP_Term_Query
     */
    protected function create_query(array $args): \WP_Term_Query
    {
        return new \WP_Term_Query($args);
    }
}