<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List\Table;

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
    FlexAahub\Package\Plugin\Template\V1\Concrete\Pagination\Template as PaginationTemplate,
};

class Template extends BaseTemplate
{
    private PaginationTemplate $pagination_template;

    public function init(array $data = []): static
    {
        $this->pagination_template = new PaginationTemplate();
        return $this;
    }

    public function render($items = null, array $options = []): bool|string
    {
        $items = is_array($items) ? $items : [];
        $options = array_merge([
            'type' => 'grid',
            'size' => 'medium',
            'color' => 'primary',
            'columns' => 3,
            'empty_message' => __('No items found.', 'flex-aahub'),
            'table' => [],
            'pagination' => ['enabled' => false],
        ], $options);

        if (!$items) {
            return '<p class="faih-list-empty">' . esc_html($options['empty_message']) . '</p>';
        }

        $classes = sprintf(
            'faih-list type-%s size-%s color-%s',
            sanitize_html_class($options['type']),
            sanitize_html_class($options['size']),
            sanitize_html_class($options['color'])
        );
        $table_wrapper_classes = 'faih-table-wrapper';

        if ($options['type'] === 'table') {
            $table = array_merge([
                'attributes' => ['scroll-x'],
                'type' => 'normal',
                'size' => 'medium',
                'color' => 'primary',
            ], $options['table']);
            $attributes = array_map('sanitize_html_class', (array) $table['attributes']);
            if (in_array('scroll-x', $attributes, true)) {
                $table_wrapper_classes .= ' attribute-scroll-x';
            }
            $classes .= sprintf(
                ' faih-table type-%s size-%s color-%s',
                sanitize_html_class($table['type']),
                sanitize_html_class($table['size']),
                sanitize_html_class($table['color'])
            );
            foreach (array_diff($attributes, ['scroll-x']) as $attribute) {
                $classes .= ' attribute-' . $attribute;
            }
        }

        $headers = $this->headers($items);
        ob_start();
        if ($options['type'] === 'table') {
            echo '<div class="' . esc_attr($table_wrapper_classes) . '">';
        }
        ?>
        <div class="<?php echo esc_attr($classes); ?>"
            style="<?php echo esc_attr('--faih-list-columns:' . max(1, min(6, (int) $options['columns'])) . ';'); ?>">
            <?php if ($options['type'] === 'table') : ?>
                <div class="faih-list-header faih-table-header faih-table-row" role="row">
                    <div class="faih-list-cell faih-table-cell" role="columnheader"><?php esc_html_e('Item', 'flex-aahub'); ?></div>
                    <?php foreach ($headers as $header) : ?>
                        <div class="faih-list-cell faih-table-cell" role="columnheader"><?php echo esc_html($header); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php foreach ($items as $item) :
                $attributes = $this->attributes((array) ($item['attributes'] ?? []));
                if ($options['type'] === 'table') :
                    ?>
                    <div class="faih-list-item faih-table-row" role="row"<?php echo $attributes; ?>>
                        <div class="faih-list-cell faih-table-cell" role="cell"><?php echo $this->title($item); ?></div>
                        <?php foreach ($headers as $key => $label) : ?>
                            <div class="faih-list-cell faih-table-cell" role="cell">
                                <?php echo wp_kses_post((string) ($item['fields'][$key] ?? '—')); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <article class="faih-list-item"<?php echo $attributes; ?>>
                        <div class="faih-list-item-content">
                            <h3 class="faih-list-item-title"><?php echo $this->title($item); ?></h3>
                            <?php if (!empty($item['accent'])) : ?>
                                <p class="faih-list-item-accent"><?php echo wp_kses_post((string) $item['accent']); ?></p>
                            <?php endif; ?>
                            <dl class="faih-list-fields">
                                <?php foreach ((array) ($item['fields'] ?? []) as $key => $value) :
                                    if ($value === '' || $value === null) {
                                        continue;
                                    }
                                    ?>
                                    <div>
                                        <dt><?php echo esc_html((string) ($item['labels'][$key] ?? $key)); ?></dt>
                                        <dd><?php echo wp_kses_post((string) $value); ?></dd>
                                    </div>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        if ($options['type'] === 'table') {
            echo '</div>';
        }
        echo $this->pagination_template->render($options['pagination']);
        return ob_get_clean();
    }

    private function headers(array $items): array
    {
        $headers = [];
        foreach ($items as $item) {
            foreach ((array) ($item['fields'] ?? []) as $key => $value) {
                $headers[$key] = (string) ($item['labels'][$key] ?? $key);
            }
        }
        return $headers;
    }

    private function title(array $item): string
    {
        $title = esc_html((string) ($item['title'] ?? ''));
        return empty($item['url'])
            ? $title
            : '<a class="faih-list-item-link" href="' . esc_url($item['url']) . '">' . $title . '</a>';
    }

    private function attributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $name => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($name), esc_attr((string) $value));
        }
        return $html;
    }
}
