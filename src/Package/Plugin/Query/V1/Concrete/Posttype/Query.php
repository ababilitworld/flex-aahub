<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Query\V1\Concrete\PostType;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\V1\Base\Query as BaseQuery
};
use WP_Query;

class Query extends BaseQuery
{
    /**
     * Query type.
     */
    protected string $type = 'post-type';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'post_type' => 'post',
        'post_status' => 'publish',
    ];

    /**
     * Default arguments.
     *
     * @var array<string, mixed>
     */
    protected array $default_args = [
        'posts_per_page' => 10,
        'paged' => 1,
    ];

    /**
     * Initialize.
     */
    public function init(array $data = []): static
    {
        parent::init($data);

        /*
         * Convenient top-level values.
         */
        if (isset($data['post_type'])) {
            $this->set_config_value(
                'post_type',
                $data['post_type']
            );
        }

        if (isset($data['post_status'])) {
            $this->set_config_value(
                'post_status',
                $data['post_status']
            );
        }

        return $this;
    }

    /**
     * Prepare WP_Query arguments.
     */
    protected function prepare_args(): void
    {
        parent::prepare_args();

        $this->args['post_type'] =
            $this->get_config_value(
                'post_type',
                'post'
            );

        $this->args['post_status'] =
            $this->get_config_value(
                'post_status',
                'publish'
            );

        /*
         * Normalize logical query structures.
         */
        if (
            isset($this->args['meta_query']) &&
            is_array($this->args['meta_query'])
        ) {
            $this->args['meta_query'] =
                $this->normalize_meta_query(
                    $this->args['meta_query']
                );
        }

        if (
            isset($this->args['tax_query']) &&
            is_array($this->args['tax_query'])
        ) {
            $this->args['tax_query'] =
                $this->normalize_tax_query(
                    $this->args['tax_query']
                );
        }
    }

    /**
     * Execute WP_Query.
     */
    protected function run_query(): void
    {
        $query = new WP_Query(
            $this->args
        );

        $this->results =
            $query->posts;

        $this->found_items =
            absint($query->found_posts);

        $this->max_num_pages =
            absint($query->max_num_pages);

        $this->set_wp_query($query);
    }

    /**
     * WP_Query instance.
     */
    protected ?WP_Query $wp_query = null;

    /**
     * Set WP_Query.
     */
    protected function set_wp_query(
        WP_Query $query
    ): void {
        $this->wp_query = $query;
    }

    /**
     * Get WP_Query.
     */
    public function get_wp_query(): ?WP_Query
    {
        return $this->wp_query;
    }

    /**
     * Get query used by pagination.
     */
    protected function get_pagination_query(): mixed
    {
        return $this->wp_query;
    }
}