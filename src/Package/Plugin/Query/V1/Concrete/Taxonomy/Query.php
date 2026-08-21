<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\Taxonomy;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Query\V1\Base\Query as BaseQuery;

class Query extends BaseQuery
{
    protected array $default_config = [
        'taxonomy' => '',
        'hide_empty' => true,
        'number' => 10,
        'offset' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
        'search' => '',
        'object_ids' => [],
        'post_type' => [],
        'meta_query' => [],
    ];

    protected function prepare_query_args(array $args): array
    {
        $config = array_replace_recursive(
            $this->default_config,
            $this->config
        );

        $taxonomy = sanitize_key((string) $config['taxonomy']);

        if ($taxonomy === '') {
            throw new \InvalidArgumentException(
                'Taxonomy query requires a taxonomy.'
            );
        }

        $args['taxonomy'] = $taxonomy;
        $args['hide_empty'] = (bool) $config['hide_empty'];
        $args['number'] = $this->get_per_page();
        $args['offset'] = max(0, (int) $config['offset']);
        $args['orderby'] = sanitize_key((string) $config['orderby']);
        $args['order'] = strtoupper((string) $config['order']) === 'DESC'
            ? 'DESC'
            : 'ASC';

        if ($config['search'] !== '') {
            $args['search'] = sanitize_text_field(
                (string) $config['search']
            );
        }

        if (!empty($config['object_ids'])) {
            $args['object_ids'] = array_map(
                'absint',
                (array) $config['object_ids']
            );
        }

        /*
         * WP_Term_Query does not use WP_Query. This concrete class therefore
         * intentionally remains a compatibility adapter. If your current
         * Query base is WP_Query-only, use this class through a dedicated
         * term-query base before enabling it.
         */
        return $args;
    }
}
