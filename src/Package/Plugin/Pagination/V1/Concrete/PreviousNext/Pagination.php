<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\PreviousNext;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination
};

class Pagination extends BasePagination
{
    /**
     * Pagination type.
     */
    protected string $type = 'previous-next';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'prev_text' => 'Previous',
        'next_text' => 'Next',

        'container_class' =>
            'flex-pagination-previous-next',

        'link_class' =>
            'flex-pagination-previous-next__link',

        'previous_class' =>
            'flex-pagination-previous-next__previous',

        'next_class' =>
            'flex-pagination-previous-next__next',

        'aria_label' =>
            'Pagination',
    ];

    /**
     * Generate links.
     */
    public function pagination_links(): string
    {
        if ($this->get_total_pages() <= 1) {
            return '';
        }

        $links = [];

        /*
         * Previous.
         */
        if ($this->has_previous()) {
            $links[] = sprintf(
                '<a class="%1$s %2$s" href="%3$s" rel="prev">%4$s</a>',
                esc_attr(
                    $this->get_config_value(
                        'link_class'
                    )
                ),
                esc_attr(
                    $this->get_config_value(
                        'previous_class'
                    )
                ),
                $this->get_page_url(
                    $this->get_current_page() - 1
                ),
                esc_html(
                    $this->get_config_value(
                        'prev_text'
                    )
                )
            );
        }

        /*
         * Next.
         */
        if ($this->has_next()) {
            $links[] = sprintf(
                '<a class="%1$s %2$s" href="%3$s" rel="next">%4$s</a>',
                esc_attr(
                    $this->get_config_value(
                        'link_class'
                    )
                ),
                esc_attr(
                    $this->get_config_value(
                        'next_class'
                    )
                ),
                $this->get_page_url(
                    $this->get_current_page() + 1
                ),
                esc_html(
                    $this->get_config_value(
                        'next_text'
                    )
                )
            );
        }

        return sprintf(
            '<nav class="%1$s" aria-label="%2$s">%3$s</nav>',
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
            implode('', $links)
        );
    }
}