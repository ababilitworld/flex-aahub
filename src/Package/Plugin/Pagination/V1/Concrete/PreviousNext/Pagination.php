<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\PreviousNext;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination;

class Pagination extends BasePagination
{
    protected string $type = 'previous-next';

    protected array $default_config = [
        'per_page' => 10,
        'current_page' => 1,
        'previous_text' => 'Previous',
        'next_text' => 'Next',
        'size' => 'medium',
        'color' => 'primary',
        'attributes' => [],
    ];

    public function prepare(): static
    {
        $this->set_config_value(
            'current_page',
            max(
                1,
                (int) $this->get_config_value(
                    'current_page',
                    get_query_var('paged', 1)
                )
            )
        );

        return $this;
    }

    public function paginate(): static
    {
        $query = $this->get_query();

        if (!$query) {
            return $this;
        }

        $this->set_config_value('total_items', (int) $query->found_posts);
        $this->set_config_value('total_pages', (int) $query->max_num_pages);

        return $this;
    }

    public function pagination_links(): string
    {
        $query = $this->get_query();

        if (!$query || $query->max_num_pages <= 1) {
            return '';
        }

        $current = max(
            1,
            (int) $this->get_config_value('current_page', 1)
        );

        $total = (int) $query->max_num_pages;

        $html = '<nav class="flex-aahub-pagination flex-aahub-pagination--previous-next" aria-label="' .
            esc_attr__('Pagination', 'flex-aahub') .
            '">';

        if ($current > 1) {
            $html .= sprintf(
                '<a class="flex-aahub-pagination__previous" href="%s">%s</a>',
                esc_url(get_pagenum_link($current - 1)),
                esc_html($this->get_config_value('previous_text', 'Previous'))
            );
        }

        if ($current < $total) {
            $html .= sprintf(
                '<a class="flex-aahub-pagination__next" href="%s">%s</a>',
                esc_url(get_pagenum_link($current + 1)),
                esc_html($this->get_config_value('next_text', 'Next'))
            );
        }

        return $html . '</nav>';
    }
}
