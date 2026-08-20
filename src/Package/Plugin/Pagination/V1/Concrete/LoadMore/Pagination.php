<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\LoadMore;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination
};

class Pagination extends BasePagination
{
    /**
     * Pagination type.
     */
    protected string $type = 'load-more';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'button_text' => 'Load More',
        'loading_text' => 'Loading...',
        'finished_text' => 'No More Results',

        'container_class' =>
            'flex-pagination-load-more',

        'button_class' =>
            'flex-pagination-load-more__button',

        'loading_class' =>
            'is-loading',

        'disabled_class' =>
            'is-disabled',

        'aria_label' =>
            'Load more results',

        /*
         * Front-end JavaScript can use this identifier
         * to determine which query should be loaded.
         */
        'query_id' => '',
    ];

    /**
     * Generate Load More markup.
     */
    public function pagination_links(): string
    {
        if (!$this->has_next()) {
            return '';
        }

        $current_page =
            $this->get_current_page();

        $next_page =
            $current_page + 1;

        $total_pages =
            $this->get_total_pages();

        $query_id =
            (string) $this->get_config_value(
                'query_id',
                ''
            );

        return sprintf(
            '<div class="%1$s" data-pagination-type="load-more" data-current-page="%2$d" data-next-page="%3$d" data-total-pages="%4$d" data-query-id="%5$s">
                <button type="button" class="%6$s" aria-label="%7$s" data-page="%3$d">%8$s</button>
            </div>',
            esc_attr(
                $this->get_config_value(
                    'container_class'
                )
            ),
            $current_page,
            $next_page,
            $total_pages,
            esc_attr($query_id),
            esc_attr(
                $this->get_config_value(
                    'button_class'
                )
            ),
            esc_attr(
                $this->get_config_value(
                    'aria_label'
                )
            ),
            esc_html(
                $this->get_config_value(
                    'button_text'
                )
            )
        );
    }

    /**
     * Get AJAX/load-more data.
     *
     * Useful for REST/AJAX implementations.
     *
     * @return array<string, mixed>
     */
    public function get_load_more_data(): array
    {
        return [
            'type' => $this->get_type(),

            'current_page' =>
                $this->get_current_page(),

            'next_page' =>
                $this->has_next()
                    ? $this->get_current_page() + 1
                    : null,

            'total_pages' =>
                $this->get_total_pages(),

            'total_items' =>
                $this->get_total_items(),

            'per_page' =>
                $this->get_per_page(),

            'query_id' =>
                $this->get_config_value(
                    'query_id',
                    ''
                ),
        ];
    }
}