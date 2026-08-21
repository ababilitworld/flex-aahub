<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\Paged;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination;

class Pagination extends BasePagination
{
    protected string $type = 'paged';

    protected array $default_config = [
        'per_page' => 10,
        'current_page' => 1,
        'size' => 'medium',
        'color' => 'primary',
        'attributes' => [],
        'base' => '',
        'format' => '?paged=%#%',
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

        $this->set_config_value(
            'total_items',
            (int) $query->found_posts
        );

        $this->set_config_value(
            'total_pages',
            (int) $query->max_num_pages
        );

        return $this;
    }

    public function pagination_links(): string
    {
        $query = $this->get_query();

        if (!$query || $query->max_num_pages <= 1) {
            return '';
        }

        $links = paginate_links([
            'base' => $this->get_config_value(
                'base',
                str_replace(
                    '%_%',
                    1 === $this->get_config_value('current_page', 1)
                        ? ''
                        : '%#%',
                    esc_url_raw(
                        str_replace(
                            '999999999',
                            '%#%',
                            get_pagenum_link(999999999)
                        )
                    )
                )
            ),
            'format' => $this->get_config_value('format', '?paged=%#%'),
            'current' => max(
                1,
                (int) $this->get_config_value('current_page', 1)
            ),
            'total' => (int) $query->max_num_pages,
            'type' => 'plain',
        ]);

        return is_string($links) ? $links : '';
    }
}
