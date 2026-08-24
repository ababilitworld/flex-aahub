<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\LoadMore;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Pagination\V1\Base\Pagination as BasePagination
};

class Pagination extends BasePagination
{
    protected string $type = 'load-more';

    /**
     * Render load-more button.
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

        $current_page = min(
            $this->get_current_page(),
            max(1, $total_pages)
        );

        if ($total_pages <= $current_page) {
            return '';
        }

        return sprintf(
            '<div class="%s" data-page="%d" data-next-page="%d" data-total-pages="%d">
                <button type="button" class="flex-aahub-pagination__load-more">%s</button>
            </div>',
            esc_attr(
                trim(
                    'flex-aahub-pagination flex-aahub-pagination--load-more ' .
                    $this->get_config_value('class', '')
                )
            ),
            $current_page,
            $current_page + 1,
            $total_pages,
            esc_html__(
                'Load more',
                'flex-aahub'
            )
        );
    }
}