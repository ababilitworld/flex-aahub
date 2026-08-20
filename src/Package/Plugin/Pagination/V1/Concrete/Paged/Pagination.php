<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\Paged;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination
};

class Pagination extends BasePagination
{
    /**
     * Pagination type.
     */
    protected string $type = 'paged';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'range' => 2,
        'prev_text' => 'Previous',
        'next_text' => 'Next',
        'current_class' => 'is-current',
        'container_class' => 'flex-pagination',
        'list_class' => 'flex-pagination__list',
        'item_class' => 'flex-pagination__item',
        'link_class' => 'flex-pagination__link',
        'aria_label' => 'Pagination',
        'show_first' => true,
        'show_last' => true,
    ];

    /**
     * Generate pagination links.
     */
    public function pagination_links(): string
    {
        if ($this->get_total_pages() <= 1) {
            return '';
        }

        $current = $this->get_current_page();
        $total = $this->get_total_pages();
        $range = max(
            1,
            absint(
                $this->get_config_value(
                    'range',
                    2
                )
            )
        );

        $items = [];

        /*
         * Previous.
         */
        if ($this->has_previous()) {
            $items[] = sprintf(
                '<li class="%1$s"><a class="%2$s" href="%3$s" rel="prev">%4$s</a></li>',
                esc_attr(
                    $this->get_config_value(
                        'item_class'
                    )
                ),
                esc_attr(
                    $this->get_config_value(
                        'link_class'
                    )
                ),
                $this->get_page_url($current - 1),
                esc_html(
                    $this->get_config_value(
                        'prev_text'
                    )
                )
            );
        }

        /*
         * First page.
         */
        if (
            $this->get_config_value(
                'show_first',
                true
            ) &&
            $current > ($range + 1)
        ) {
            $items[] = $this->render_page_item(
                1,
                $current
            );

            if ($current > ($range + 2)) {
                $items[] = $this->render_ellipsis();
            }
        }

        /*
         * Page range.
         */
        $start = max(
            1,
            $current - $range
        );

        $end = min(
            $total,
            $current + $range
        );

        for ($page = $start; $page <= $end; $page++) {
            $items[] = $this->render_page_item(
                $page,
                $current
            );
        }

        /*
         * Last page.
         */
        if (
            $this->get_config_value(
                'show_last',
                true
            ) &&
            $current < ($total - $range)
        ) {
            if ($current < ($total - $range - 1)) {
                $items[] = $this->render_ellipsis();
            }

            $items[] = $this->render_page_item(
                $total,
                $current
            );
        }

        /*
         * Next.
         */
        if ($this->has_next()) {
            $items[] = sprintf(
                '<li class="%1$s"><a class="%2$s" href="%3$s" rel="next">%4$s</a></li>',
                esc_attr(
                    $this->get_config_value(
                        'item_class'
                    )
                ),
                esc_attr(
                    $this->get_config_value(
                        'link_class'
                    )
                ),
                $this->get_page_url($current + 1),
                esc_html(
                    $this->get_config_value(
                        'next_text'
                    )
                )
            );
        }

        return sprintf(
            '<nav class="%1$s" aria-label="%2$s"><ul class="%3$s">%4$s</ul></nav>',
            esc_attr(
                $this->get_config_value(
                    'container_class'
                )
            ),
            esc_attr(
                $this->get_config_value(
                    'aria_label'
                )
            ),
            esc_attr(
                $this->get_config_value(
                    'list_class'
                )
            ),
            implode('', $items)
        );
    }

    /**
     * Render page item.
     */
    protected function render_page_item(
        int $page,
        int $current
    ): string {
        $item_class = $this->get_config_value(
            'item_class'
        );

        $link_class = $this->get_config_value(
            'link_class'
        );

        if ($page === $current) {
            return sprintf(
                '<li class="%1$s %2$s"><span class="%3$s" aria-current="page">%4$d</span></li>',
                esc_attr($item_class),
                esc_attr(
                    $this->get_config_value(
                        'current_class'
                    )
                ),
                esc_attr($link_class),
                $page
            );
        }

        return sprintf(
            '<li class="%1$s"><a class="%2$s" href="%3$s">%4$d</a></li>',
            esc_attr($item_class),
            esc_attr($link_class),
            $this->get_page_url($page),
            $page
        );
    }

    /**
     * Render ellipsis.
     */
    protected function render_ellipsis(): string
    {
        return sprintf(
            '<li class="%1$s" aria-hidden="true"><span class="%2$s">…</span></li>',
            esc_attr(
                $this->get_config_value(
                    'item_class'
                )
            ),
            esc_attr(
                $this->get_config_value(
                    'link_class'
                )
            )
        );
    }
}