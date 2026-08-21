<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
};

class Template extends BaseTemplate
{
    /**
     * Template type.
     */
    protected string $type = 'list';

    /**
     * Default data.
     *
     * @var array<string, mixed>
     */
    protected array $default_data = [
        'items' => [],
        'empty_message' => 'No items found.',
    ];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'container_class' => 'flex-template-list',
        'item_class' => 'flex-template-list__item',
        'empty_class' => 'flex-template-list__empty',
    ];

    /**
     * Render list.
     */
    protected function render_html(
        array $data
    ): string {
        $items = $data['items'] ?? [];

        if (!is_array($items)) {
            $items = [];
        }

        if ($items === []) {
            return $this->render_empty(
                $data
            );
        }

        $html = sprintf(
            '<div class="%1$s">',
            $this->classes(
                $this->get_config_value(
                    'container_class'
                )
            )
        );

        foreach ($items as $index => $item) {
            $html .= $this->render_item(
                $item,
                $index
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Render individual item.
     *
     * Child templates override this.
     */
    protected function render_item(
        mixed $item,
        int|string $index
    ): string {
        return sprintf(
            '<div class="%1$s">%2$s</div>',
            $this->classes(
                $this->get_config_value(
                    'item_class'
                )
            ),
            $this->text(
                is_scalar($item)
                    ? $item
                    : wp_json_encode($item)
            )
        );
    }

    /**
     * Render empty state.
     */
    protected function render_empty(
        array $data
    ): string {
        return sprintf(
            '<div class="%1$s">%2$s</div>',
            $this->classes(
                $this->get_config_value(
                    'empty_class'
                )
            ),
            $this->text(
                $data['empty_message']
                ?? 'No items found.'
            )
        );
    }
}