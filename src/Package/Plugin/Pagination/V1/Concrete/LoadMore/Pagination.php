<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\LoadMore;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination;

class Pagination extends BasePagination
{
    protected string $type = 'load-more';

    protected array $default_config = [
        'per_page' => 10,
        'current_page' => 1,
        'button_text' => 'Load More',
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

        $next = $current + 1;

        return sprintf(
            '<button type="button" class="flex-aahub-pagination flex-aahub-pagination--load-more" data-flex-aahub-page="%d">%s</button>',
            esc_attr($next),
            esc_html($this->get_config_value('button_text', 'Load More'))
        );
    }
}
