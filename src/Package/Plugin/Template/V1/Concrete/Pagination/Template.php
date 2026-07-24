<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Pagination;

use Ababilithub\FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate;

class Template extends BaseTemplate
{
    public function init(array $data = []): static
    {
        return $this;
    }

    public function render($config = null): bool|string
    {
        $config = array_merge([
            'enabled' => true,
            'type' => 'load-more',
            'attributes' => ['centered'],
            'size' => 'medium',
            'color' => 'primary',
            'per_page' => 10,
            'labels' => [],
        ], is_array($config) ? $config : []);

        if (!$config['enabled'] || $config['type'] === 'none') {
            return '';
        }

        $labels = array_merge([
            'previous' => __('Previous', 'flex-aahub'),
            'next' => __('Next', 'flex-aahub'),
            'load_more' => __('Load more', 'flex-aahub'),
            'aria' => __('List pagination', 'flex-aahub'),
        ], $config['labels']);

        $classes = sprintf(
            'faih-pagination type-%s size-%s color-%s',
            sanitize_html_class($config['type']),
            sanitize_html_class($config['size']),
            sanitize_html_class($config['color'])
        );
        foreach ((array) $config['attributes'] as $attribute) {
            $classes .= ' attribute-' . sanitize_html_class($attribute);
        }

        ob_start();
        ?>
        <nav class="<?php echo esc_attr($classes); ?>"
            data-pagination
            data-pagination-type="<?php echo esc_attr($config['type']); ?>"
            data-per-page="<?php echo esc_attr(max(1, (int) $config['per_page'])); ?>"
            aria-label="<?php echo esc_attr($labels['aria']); ?>">
            <button class="faih-pagination-button faih-pagination-previous" type="button" data-page-previous>
                <?php echo esc_html($labels['previous']); ?>
            </button>
            <span class="faih-pagination-pages" data-pagination-pages></span>
            <span class="faih-pagination-status" data-pagination-status aria-live="polite"></span>
            <button class="faih-pagination-button faih-pagination-next" type="button" data-page-next>
                <?php echo esc_html($labels['next']); ?>
            </button>
            <button class="faih-pagination-button faih-pagination-load-more" type="button" data-load-more>
                <?php echo esc_html($labels['load_more']); ?>
            </button>
        </nav>
        <?php
        return ob_get_clean();
    }
}
