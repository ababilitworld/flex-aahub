<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\Taxonomy;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Base\Query as BaseQuery
};
use WP_Query;
use WP_Term_Query;

class Query extends BaseQuery
{
    /**
     * Query type.
     */
    protected string $type = 'taxonomy';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'taxonomy' => '',
        'hide_empty' => false,

        /*
         * Conditions applied to attached posts.
         */
        'post_type' => 'post',
        'post_status' => 'publish',

        /*
         * Optional post constraints.
         */
        'post_meta_query' => [],
        'post_tax_query' => [],

        /*
         * Optional direct term query arguments.
         */
        'term_args' => [],
    ];

    /**
     * Default arguments.
     *
     * @var array<string, mixed>
     */
    protected array $default_args = [
        'posts_per_page' => -1,
    ];

    /**
     * Matched post IDs.
     *
     * @var array<int, int>
     */
    protected array $matched_post_ids = [];

    /**
     * WP_Term_Query.
     */
    protected ?WP_Term_Query $term_query = null;

    /**
     * Initialize.
     */
    public function init(array $data = []): static
    {
        parent::init($data);

        foreach (
            [
                'taxonomy',
                'hide_empty',
                'post_type',
                'post_status',
                'post_meta_query',
                'post_tax_query',
                'term_args',
            ] as $key
        ) {
            if (array_key_exists($key, $data)) {
                $this->set_config_value(
                    $key,
                    $data[$key]
                );
            }
        }

        return $this;
    }

    /**
     * Prepare arguments.
     */
    protected function prepare_args(): void
    {
        parent::prepare_args();

        /*
         * Taxonomy queries return terms, so pagination
         * applies to terms rather than posts.
         */
    }

    /**
     * Execute taxonomy query.
     */
    protected function run_query(): void
    {
        $this->matched_post_ids =
            $this->find_matching_post_ids();

        /*
         * No matching posts means no terms.
         */
        if ($this->matched_post_ids === []) {
            $this->results = [];
            $this->found_items = 0;
            $this->max_num_pages = 0;

            return;
        }

        $this->query_terms();
    }

    /**
     * Find posts satisfying post type/meta/taxonomy
     * conditions.
     *
     * @return array<int, int>
     */
    protected function find_matching_post_ids(): array
    {
        $args = [
            'post_type' =>
                $this->get_config_value(
                    'post_type',
                    'post'
                ),

            'post_status' =>
                $this->get_config_value(
                    'post_status',
                    'publish'
                ),

            'posts_per_page' => -1,

            'fields' => 'ids',

            'no_found_rows' => true,
        ];

        /*
         * Post meta conditions.
         */
        $meta_query =
            $this->get_config_value(
                'post_meta_query',
                []
            );

        if (
            is_array($meta_query) &&
            $meta_query !== []
        ) {
            $args['meta_query'] =
                $this->normalize_meta_query(
                    $meta_query
                );
        }

        /*
         * Post taxonomy conditions.
         */
        $tax_query =
            $this->get_config_value(
                'post_tax_query',
                []
            );

        if (
            is_array($tax_query) &&
            $tax_query !== []
        ) {
            $args['tax_query'] =
                $this->normalize_tax_query(
                    $tax_query
                );
        }

        /*
         * Allow additional post query arguments.
         */
        $additional_args =
            $this->get_config_value(
                'post_args',
                []
            );

        if (
            is_array($additional_args) &&
            $additional_args !== []
        ) {
            $args = array_replace(
                $args,
                $additional_args
            );
        }

        $query = new WP_Query($args);

        return array_map(
            'absint',
            $query->posts
        );
    }

    /**
     * Query terms attached to matching posts.
     */
    protected function query_terms(): void
    {
        $taxonomy =
            $this->get_config_value(
                'taxonomy',
                ''
            );

        if ($taxonomy === '') {
            return;
        }

        $per_page = $this->get_per_page();

        $current_page =
            $this->get_current_page();

        $offset =
            ($current_page - 1) * $per_page;

        /*
         * First resolve all matching terms.
         *
         * We use fields=ids here because the total number
         * of terms is required for pagination.
         */
        $all_term_ids = get_terms([
            'taxonomy' => $taxonomy,
            'object_ids' => $this->matched_post_ids,
            'hide_empty' =>
                (bool) $this->get_config_value(
                    'hide_empty',
                    false
                ),
            'fields' => 'ids',
        ]);

        if (is_wp_error($all_term_ids)) {
            $this->results = [];
            $this->found_items = 0;
            $this->max_num_pages = 0;

            return;
        }

        $this->found_items =
            count($all_term_ids);

        $this->max_num_pages =
            $this->found_items > 0
                ? (int) ceil(
                    $this->found_items / $per_page
                )
                : 0;

        if ($this->found_items === 0) {
            $this->results = [];

            return;
        }

        /*
         * Direct term arguments.
         */
        $term_args =
            $this->get_config_value(
                'term_args',
                []
            );

        if (!is_array($term_args)) {
            $term_args = [];
        }

        $term_args = array_replace(
            [
                'taxonomy' => $taxonomy,
                'object_ids' => $this->matched_post_ids,
                'hide_empty' =>
                    (bool) $this->get_config_value(
                        'hide_empty',
                        false
                    ),
                'number' => $per_page,
                'offset' => $offset,
            ],
            $term_args
        );

        /*
         * Make sure pagination cannot accidentally be
         * overridden by term_args.
         */
        $term_args['number'] = $per_page;
        $term_args['offset'] = $offset;

        $this->term_query =
            new WP_Term_Query(
                $term_args
            );

        $terms = $this->term_query->get_terms();

        $this->results =
            is_array($terms)
                ? $terms
                : [];
    }

    /**
     * Get matched post IDs.
     *
     * @return array<int, int>
     */
    public function get_matched_post_ids(): array
    {
        return $this->matched_post_ids;
    }

    /**
     * Get WP_Term_Query.
     */
    public function get_term_query(): ?WP_Term_Query
    {
        return $this->term_query;
    }

    /**
     * Pagination currently operates on terms,
     * so the pagination query is represented by
     * the term query.
     */
    protected function get_pagination_query(): mixed
    {
        return $this->term_query;
    }
}