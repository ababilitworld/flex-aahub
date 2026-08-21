<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Grid;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexAahub\Package\Plugin\Template\V1\Concrete\List\Template as ListTemplate,
};

class Template extends ListTemplate
{
    /**
     * Template type.
     */
    protected string $type = 'grid';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'container_class' => 'flex-template-grid',
        'item_class' => 'flex-template-grid__item',
        'empty_class' => 'flex-template-grid__empty',
    ];

    /**
     * Render grid item.
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
            $this->render_item_content(
                $item,
                $index
            )
        );
    }

    /**
     * Render item content.
     */
    protected function render_item_content(
        mixed $item,
        int|string $index
    ): string {
        if (is_scalar($item)) {
            return $this->text($item);
        }

        return $this->text(
            wp_json_encode($item)
        );
    }
}