<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Concrete\Masonry;

use Ababilithub\{
    FlexAahub\Package\Plugin\Template\List\V1\Base\Template as BaseListTemplate,
};

final class Template extends BaseListTemplate
{
    public const TYPE = 'masonry';

    public function init(array $data = []): static
    {
        $this->asset_base_url = $this->get_url('Asset/');
        $this->asset_base_prefix = 'ababilithub-template-list-masonry';

        return parent::init($data);
    }

    public function get_type(): string
    {
        return self::TYPE;
    }

    protected function init_hook(): void
    {
        add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );
    }

    public function enqueue_scripts(): void
    {
        $version = defined('WP_DEBUG') && WP_DEBUG
            ? (string) time()
            : '1.0.0';

        wp_enqueue_style(
            $this->asset_base_prefix . '-style',
            $this->asset_base_url . 'Css/Style.css',
            [],
            $version
        );

        wp_enqueue_script(
            $this->asset_base_prefix . '-script',
            $this->asset_base_url . 'Js/Script.js',
            ['jquery'],
            $version,
            true
        );

        wp_localize_script(
            $this->asset_base_prefix . '-script',
            'ababilithubTemplateListMasonry',
            [
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce'    => wp_create_nonce(
                    $this->asset_base_prefix . '_nonce'
                ),
            ]
        );
    }

    public function render(iterable $items = []): string
    {
        $items = is_array($items)
            ? $items
            : iterator_to_array($items);

        if ($items === []) {
            return $this->render_empty_message();
        }

        ob_start();
        ?>
        <div
            class="ababilithub-template-list-masonry"
            data-template-type="<?php echo esc_attr(self::TYPE); ?>"
        >
            <div class="ababilithub-template-list-masonry__grid">
                <?php foreach ($items as $item) : ?>
                    <?php
                    if (!is_object($item)) {
                        continue;
                    }

                    $name = isset($item->name)
                        ? (string) $item->name
                        : '';

                    $url = isset($item->url)
                        ? (string) $item->url
                        : '';

                    $data = isset($item->data)
                        ? (string) $item->data
                        : '';

                    $thumbnail_url = $this->get_item_thumbnail_url(
                        $item
                    );
                    ?>

                    <article
                        class="ababilithub-template-list-masonry__item"
                        data-layout-item="<?php echo esc_attr($data); ?>"
                    >
                        <?php if (
                            $this->get_config_value('show_title', true)
                        ) : ?>
                            <header
                                class="ababilithub-template-list-masonry__header"
                            >
                                <h3
                                    class="ababilithub-template-list-masonry__title"
                                >
                                    <?php echo esc_html($name); ?>
                                </h3>
                            </header>
                        <?php endif; ?>

                        <?php if (
                            $this->get_config_value(
                                'show_thumbnail',
                                true
                            )
                        ) : ?>
                            <div
                                class="ababilithub-template-list-masonry__content"
                            >
                                <img
                                    class="ababilithub-template-list-masonry__image"
                                    src="<?php echo esc_url($thumbnail_url); ?>"
                                    alt="<?php echo esc_attr($name); ?>"
                                    loading="lazy"
                                >
                            </div>
                        <?php endif; ?>

                        <?php if (
                            $url !== ''
                            && $this->get_config_value(
                                'show_action',
                                true
                            )
                        ) : ?>
                            <footer
                                class="ababilithub-template-list-masonry__footer"
                            >
                                <a
                                    class="ababilithub-template-list-masonry__button"
                                    href="<?php echo esc_url($url); ?>"
                                >
                                    <?php
                                    echo esc_html__(
                                        'View Details',
                                        'flex-wordpress'
                                    );
                                    ?>
                                </a>
                            </footer>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php

        return (string) ob_get_clean();
    }
}