<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\PreviousNext;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination
};

class Pagination extends BasePagination
{
    protected string $type = 'previous-next';

    /**
     * Render previous/next pagination.
     *
     * @return string
     */
    public function pagination_links(): string
    {
        if (!$this->get_config_value(
            'enabled',
            true
        )) {
            return '';
        }

        $total_pages = $this->get_total_pages();

        if ($total_pages <= 1) {
            return '';
        }

        $current_page = min(
            $this->get_current_page(),
            $total_pages
        );

        $html = sprintf(
            '<nav class="%s" aria-label="%s">',
            esc_attr(
                trim(
                    'flex-aahub-pagination flex-aahub-pagination--previous-next ' .
                    $this->get_config_value('class', '')
                )
            ),
            esc_attr__(
                'Pagination',
                'flex-aahub'
            )
        );

        if ($current_page > 1) {
            $html .= sprintf(
                '<a class="flex-aahub-pagination__previous" href="%s">%s</a>',
                esc_url(
                    $this->get_page_url(
                        $current_page - 1
                    )
                ),
                esc_html(
                    $this->get_config_value(
                        'prev_text',
                        'Previous'
                    )
                )
            );
        }

        if ($current_page < $total_pages) {
            $html .= sprintf(
                '<a class="flex-aahub-pagination__next" href="%s">%s</a>',
                esc_url(
                    $this->get_page_url(
                        $current_page + 1
                    )
                ),
                esc_html(
                    $this->get_config_value(
                        'next_text',
                        'Next'
                    )
                )
            );
        }

        $html .= '</nav>';

        return $html;
    }
}