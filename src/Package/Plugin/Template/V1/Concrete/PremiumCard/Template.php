<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\PremiumCard;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List\Template as ListTemplate;

class Template extends ListTemplate
{
    /**
     * Template type.
     */
    protected string $type = 'premium-card';

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'container_class' => 'flex-template-premium-card-list',
        'item_class' => 'flex-template-premium-card',
        'empty_class' => 'flex-template-premium-card__empty',
    ];

    /**
     * Render premium card.
     */
    protected function render_item(
        mixed $item,
        int|string $index
    ): string {
        $title = '';
        $content = '';

        if (is_array($item)) {
            $title = $item['title'] ?? '';
            $content = $item['content'] ?? '';
        } elseif (is_scalar($item)) {
            $content = (string) $item;
        }

        return sprintf(
            '<article class="%1$s">
                <header class="flex-template-premium-card__header">
                    <h3 class="flex-template-premium-card__title">%2$s</h3>
                </header>
                <div class="flex-template-premium-card__content">%3$s</div>
            </article>',
            $this->classes(
                $this->get_config_value(
                    'item_class'
                )
            ),
            $this->text($title),
            $this->text($content)
        );
    }
}