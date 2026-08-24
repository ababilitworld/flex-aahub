<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\PostType;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Base\Query as BaseQuery
};

class Query extends BaseQuery
{
    /**
     * Query type.
     *
     * @var string
     */
    protected string $type = 'post-type';

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $default_config = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'paged'          => 1,

        'orderby'        => 'date',
        'order'          => 'DESC',

        's'              => '',

        'meta_query'     => [],
        'tax_query'      => [],
        'post__in'       => [],
        'post__not_in'   => [],
        'post_parent'    => null,
        'author'         => null,
    ];

    /**
     * Prepare WP_Query arguments.
     *
     * @return array
     */
    public function prepare_args(): array
    {
        $config = $this->get_config();

        $posts_per_page = max(
            1,
            absint(
                $config['posts_per_page'] ?? 10
            )
        );

        $paged = max(
            1,
            absint(
                $config['paged'] ?? 1
            )
        );

        $args = [
            'post_type' => $config['post_type'],

            'post_status' => $config['post_status'],

            'posts_per_page' => $posts_per_page,

            /*
             * This is the value that changes when
             * the user clicks page 2, 3, 4, etc.
             */
            'paged' => $paged,

            'orderby' => $config['orderby'],

            'order' => $config['order'],
        ];

        /*
         * Search.
         */
        if (!empty($config['s'])) {
            $args['s'] = $config['s'];
        }

        /*
         * Meta query.
         */
        if (!empty($config['meta_query'])) {
            $args['meta_query'] = $config['meta_query'];
        }

        /*
         * Taxonomy query.
         */
        if (!empty($config['tax_query'])) {
            $args['tax_query'] = $config['tax_query'];
        }

        /*
         * Included posts.
         */
        if (!empty($config['post__in'])) {
            $args['post__in'] = array_map(
                'absint',
                (array) $config['post__in']
            );
        }

        /*
         * Excluded posts.
         */
        if (!empty($config['post__not_in'])) {
            $args['post__not_in'] = array_map(
                'absint',
                (array) $config['post__not_in']
            );
        }

        /*
         * Parent.
         */
        if (
            $config['post_parent'] !== null
            && $config['post_parent'] !== ''
        ) {
            $args['post_parent'] = absint(
                $config['post_parent']
            );
        }

        /*
         * Author.
         */
        if (
            $config['author'] !== null
            && $config['author'] !== ''
        ) {
            $args['author'] = absint(
                $config['author']
            );
        }

        return $args;
    }

    /**
     * Create WordPress query.
     *
     * @param array $args
     *
     * @return \WP_Query
     */
    protected function create_query(
        array $args
    ): \WP_Query {
        return new \WP_Query($args);
    }
}