<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Masonry;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate
};

class Template extends BaseTemplate
{
    protected string $type = 'masonry';

    protected array $default_config = [
        'columns' => 3,
        'class' => '',
        'size' => 'medium',
        'color' => 'primary',
    ];

    /**
     * Render masonry.
     *
     * @param array $data
     * @return string
     */
    protected function render_html(
        array $data
    ): string {
        $items = is_array(
            $data['items'] ?? null
        )
            ? $data['items']
            : [];

        if (!$items) {
            return '';
        }

        ob_start();
        ?>

        <div class="flex-aahub-template-masonry <?php echo esc_attr(
            $this->get_config_value(
                'class',
                ''
            )
        ); ?>">

            <?php foreach ($items as $item) : ?>

                <article class="flex-aahub-template-masonry__item">

                    <h3>
                        <?php echo esc_html(
                            $item['post']['title'] ?? ''
                        ); ?>
                    </h3>

                </article>

            <?php endforeach; ?>

        </div>

        <?php

        return (string) ob_get_clean();
    }
}