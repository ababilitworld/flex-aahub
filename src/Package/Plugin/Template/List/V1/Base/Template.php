<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
    FlexAahub\Package\Plugin\Template\List\V1\Contract\Template as TemplateListContract,
};

abstract class Template extends BaseTemplate implements TemplateListContract
{
    protected array $items = [];

    public function init(array $data = []): static
    {
        $this->set_default_config(
            $data['default_config'] ?? 
            [
                'empty_message'          => __('No items found.', 'flex-aahub'),
                'wrapper_class'          => '',
                'show_thumbnail'         => true,
                'show_title'             => true,
                'show_action'            => true,
                'default_thumbnail_url'  => '',
            ]
        );

        $this->set_config($data['config'] ?? $data);

        $this->init_service();
        $this->init_hook();

        return $this;
    }

    protected function init_service(): void
    {
    }

    protected function init_hook(): void
    {
    }

    protected function render_empty_message(): string
    {
        return sprintf(
            '<p class="%1$s__empty">%2$s</p>',
            esc_attr($this->asset_base_prefix),
            esc_html(
                (string) $this->get_config_value(
                    'empty_message',
                    'No items found.'
                )
            )
        );
    }

    protected function get_default_thumbnail_url(): string
    {
        $configured_url = (string) $this->get_config_value(
            'default_thumbnail_url',
            ''
        );

        if ($configured_url !== '') {
            return $configured_url;
        }

        return home_url(
            '/wp-content/uploads/flex-image/flex-image-placeholder.png'
        );
    }

    protected function get_item_thumbnail_url(object $item): string
    {
        $thumbnail_id = isset($item->thumbnail_id)
            ? absint($item->thumbnail_id)
            : 0;

        if (
            $thumbnail_id > 0
            && wp_attachment_is_image($thumbnail_id)
        ) {
            $thumbnail_url = wp_get_attachment_image_url(
                $thumbnail_id,
                'medium_large'
            );

            if (is_string($thumbnail_url) && $thumbnail_url !== '') {
                return $thumbnail_url;
            }
        }

        return $this->get_default_thumbnail_url();
    }
}