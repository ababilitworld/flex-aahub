<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Concrete\PremiumCard;

use Ababilithub\{
    FlexAahub\Package\Plugin\Template\List\V1\Base\Template as BaseListTemplate,
};

final class Template extends BaseListTemplate
{
    public const TYPE = 'premium-card';

    public function init(array $data = []): static
    {
        $this->asset_base_url = $this->get_url('Asset/');
        $this->asset_base_prefix = 'ababilithub-template-list-premium-card';

        return parent::init($data);
    }

    public function get_type(): string
    {
        return self::TYPE;
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
        <div class="ababilithub-template-list-premium-card">
            <?php foreach ($items as $item) : ?>
                <?php
                if (!is_object($item)) {
                    continue;
                }

                $name = isset($item->name)
                    ? (string) $item->name
                    : '';

                $description = isset($item->description)
                    ? (string) $item->description
                    : '';

                $url = isset($item->url)
                    ? (string) $item->url
                    : '';

                $thumbnail_url = $this->get_item_thumbnail_url(
                    $item
                );
                ?>

                <article
                    class="ababilithub-template-list-premium-card__item"
                >
                    <img
                        class="ababilithub-template-list-premium-card__image"
                        src="<?php echo esc_url($thumbnail_url); ?>"
                        alt="<?php echo esc_attr($name); ?>"
                        loading="lazy"
                    >

                    <div
                        class="ababilithub-template-list-premium-card__body"
                    >
                        <h3
                            class="ababilithub-template-list-premium-card__title"
                        >
                            <?php echo esc_html($name); ?>
                        </h3>

                        <?php if ($description !== '') : ?>
                            <p
                                class="ababilithub-template-list-premium-card__description"
                            >
                                <?php echo esc_html($description); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($url !== '') : ?>
                            <a
                                class="ababilithub-template-list-premium-card__button"
                                href="<?php echo esc_url($url); ?>"
                            >
                                <?php
                                echo esc_html__(
                                    'View Details',
                                    'flex-wordpress'
                                );
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php

        return (string) ob_get_clean();
    }
}