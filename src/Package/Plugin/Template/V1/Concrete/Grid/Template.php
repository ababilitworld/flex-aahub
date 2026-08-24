<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Grid;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate
};

class Template extends BaseTemplate
{
    protected string $type = 'grid';

    protected array $default_config = [
        'columns' => 3,
        'class' => '',
        'size' => 'medium',
        'color' => 'primary',
    ];

    /**
     * Render grid.
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

        <div
            class="flex-aahub-template-grid <?php echo esc_attr(
                $this->get_config_value('class', '')
            ); ?>"
            data-columns="<?php echo esc_attr(
                (string) $this->get_config_value(
                    'columns',
                    3
                )
            ); ?>"
        >

            <?php foreach ($items as $item) : ?>

                <article class="flex-aahub-template-grid__item">

                    <h3 class="flex-aahub-template-grid__title">
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