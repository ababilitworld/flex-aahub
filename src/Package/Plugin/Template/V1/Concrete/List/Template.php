<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate
};

class Template extends BaseTemplate
{
    protected string $type = 'list';

    protected array $default_config = [
        'class' => '',
        'size' => 'medium',
        'color' => 'primary',
    ];

    /**
     * Render list.
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

        $class = trim(
            'flex-aahub-template-list ' .
            $this->get_config_value('class', '')
        );

        ob_start();
        ?>

        <div class="<?php echo esc_attr($class); ?>">

            <?php foreach ($items as $item) : ?>

                <div class="flex-aahub-template-list__item">

                    <?php
                    echo esc_html(
                        $this->get_item_title($item)
                    );
                    ?>

                </div>

            <?php endforeach; ?>

        </div>

        <?php

        return (string) ob_get_clean();
    }

    /**
     * Get item title.
     *
     * @param array $item
     * @return string
     */
    protected function get_item_title(
        array $item
    ): string {
        return (string) (
            $item['post']['title']
            ?? ''
        );
    }
}