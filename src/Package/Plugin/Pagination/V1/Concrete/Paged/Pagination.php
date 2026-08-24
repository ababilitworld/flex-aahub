<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Pagination\V1\Concrete\Paged;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\Pagination\V1\Base\Pagination
    as BasePagination;

class Pagination extends BasePagination
{
    /**
     * Pagination type.
     *
     * @var string
     */
    protected string $type = 'paged';

    /**
     * Render numbered pagination.
     *
     * @return string
     */
    public function pagination_links(): string
    {
        if (
            !$this->get_config_value(
                'enabled',
                true
            )
        ) {
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

        $page_var = sanitize_key(
            (string) $this->get_config_value(
                'page_var',
                'paged'
            )
        );

        if ($page_var === '') {
            $page_var = 'paged';
        }

        $base = add_query_arg(
            $page_var,
            '%#%',
            $this->get_base_url()
        );

        $links = paginate_links([
            'base' => $base,

            'current' => $current_page,

            'total' => $total_pages,

            'mid_size' => max(
                0,
                (int) $this->get_config_value(
                    'mid_size',
                    2
                )
            ),

            'end_size' => max(
                0,
                (int) $this->get_config_value(
                    'end_size',
                    1
                )
            ),

            'prev_text' => $this->get_config_value(
                'prev_text',
                'Previous'
            ),

            'next_text' => $this->get_config_value(
                'next_text',
                'Next'
            ),

            'type' => 'list',
        ]);

        if (!$links) {
            return '';
        }

        return sprintf(
            '<nav class="%s" aria-label="%s">%s</nav>',

            esc_attr(
                trim(
                    'flex-aahub-pagination ' .
                    'flex-aahub-pagination--paged ' .
                    $this->get_config_value(
                        'class',
                        ''
                    )
                )
            ),

            esc_attr__(
                'Pagination',
                'flex-aahub'
            ),

            $links
        );
    }
}