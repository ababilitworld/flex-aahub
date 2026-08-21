<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Masonry;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List\Template as ListTemplate;

class Template extends ListTemplate
{
    /**
     * Template type.
     */
    protected string $type = 'masonry';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'container_class' => 'flex-template-masonry',
        'item_class' => 'flex-template-masonry__item',
        'empty_class' => 'flex-template-masonry__empty',
    ];

    /**
     * Render masonry item.
     */
    protected function render_item(
        mixed $item,
        int|string $index
    ): string {
        return sprintf(
            '<article class="%1$s">%2$s</article>',
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
}