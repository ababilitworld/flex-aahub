<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\PostType;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Query\V1\Base\Query as BaseQuery;

class Query extends BaseQuery
{
    protected array $default_config = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => 10,
        'paged' => 1,
        'meta_query' => [],
        'tax_query' => [],
        's' => '',
        'fields' => 'all',
        'no_found_rows' => false,
    ];

    protected function prepare_query_args(array $args): array
    {
        $config = array_replace_recursive(
            $this->default_config,
            $this->config
        );

        $args['post_type'] = $config['post_type'];
        $args['post_status'] = $config['post_status'];
        $args['orderby'] = $config['orderby'];
        $args['order'] = $config['order'];
        $args['posts_per_page'] = $this->get_per_page();
        $args['paged'] = $this->get_current_page();
        $args['fields'] = $config['fields'];
        $args['no_found_rows'] = (bool) $config['no_found_rows'];

        if ($config['s'] !== '') {
            $args['s'] = sanitize_text_field((string) $config['s']);
        }

        if (!empty($config['meta_query'])) {
            $args['meta_query'] = $this->normalize_query(
                $config['meta_query'],
                'meta'
            );
        }

        if (!empty($config['tax_query'])) {
            $args['tax_query'] = $this->normalize_query(
                $config['tax_query'],
                'tax'
            );
        }

        return $args;
    }

    /**
     * Normalize nested logical query structures.
     *
     * Example:
     * [
     *     'relation' => 'AND',
     *     [
     *         'key' => 'amount',
     *         'value' => 100,
     *         'compare' => '>=',
     *         'type' => 'NUMERIC',
     *     ],
     *     [
     *         'relation' => 'OR',
     *         ...
     *     ],
     * ]
     */
    protected function normalize_query(
        array $query,
        string $type
    ): array {
        $relation = isset($query['relation'])
            ? strtoupper((string) $query['relation'])
            : 'AND';

        $normalized = [
            'relation' => in_array(
                $relation,
                ['AND', 'OR'],
                true
            ) ? $relation : 'AND',
        ];

        foreach ($query as $key => $clause) {
            if ($key === 'relation' || !is_array($clause)) {
                continue;
            }

            if (isset($clause['relation'])) {
                $normalized[] = $this->normalize_query(
                    $clause,
                    $type
                );

                continue;
            }

            if ($type === 'meta') {
                $normalized[] = [
                    'key' => isset($clause['key'])
                        ? sanitize_key((string) $clause['key'])
                        : '',
                    'value' => $clause['value'] ?? '',
                    'compare' => isset($clause['compare'])
                        ? strtoupper((string) $clause['compare'])
                        : '=',
                    'type' => isset($clause['type'])
                        ? strtoupper((string) $clause['type'])
                        : 'CHAR',
                ];
            } else {
                $normalized[] = [
                    'taxonomy' => isset($clause['taxonomy'])
                        ? sanitize_key((string) $clause['taxonomy'])
                        : '',
                    'field' => isset($clause['field'])
                        ? sanitize_key((string) $clause['field'])
                        : 'term_id',
                    'terms' => $clause['terms'] ?? [],
                    'operator' => isset($clause['operator'])
                        ? strtoupper((string) $clause['operator'])
                        : 'IN',
                ];
            }
        }

        return $normalized;
    }
}
