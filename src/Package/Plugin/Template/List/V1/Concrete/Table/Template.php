<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Concrete\Table;

use Ababilithub\{
    FlexAahub\Package\Plugin\Template\List\V1\Base\Template as BaseListTemplate,
    FlexAahub\Package\Plugin\Template\Pagination\V1\Manager\Template as PaginationTemplate,
};

final class Template extends BaseListTemplate
{
    public const TYPE = 'table';
    private PaginationTemplate $pagination_template;

    public function init(array $data = []): static
    {
        $table_default_config = $this->get_table_default_config();

        $supplied_default_config = isset($data['default_config'])
            && is_array($data['default_config'])
                ? $data['default_config']
                : [];

        $data['default_config'] = array_replace_recursive(
            $table_default_config,
            $supplied_default_config
        );

        $this->asset_base_url = $this->get_url('Asset/');
        $this->asset_base_prefix =
            'ababilithub-aahub-template-list-table';

        return parent::init($data);
    }

    protected function init_service(): void
    {
        $this->pagination_template = new PaginationTemplate();
    }

    protected function init_hook(): void
    {
    }

    public function get_type(): string
    {
        return self::TYPE;
    }

    public function render(array $data = []): string
    {
        $this->items = isset($data['items']) && is_array($data['items'])
            ? $data['items']
            : [];

        $supplied_config = isset($data['config']) && is_array($data['config'])
            ? $data['config']
            : [];

        $this->config = array_replace_recursive(
            $this->default_config,
            $supplied_config
        );

        if ($this->items === []) 
        {
            return $this->render_empty_message();
        }

        $headers = $this->get_headers($this->items);
        $wrapper_classes = $this->get_wrapper_classes();
        $list_classes = $this->get_list_classes();
        $columns = $this->get_column_count($headers);

        ob_start();
        ?>

         <div
            class="<?php echo esc_attr($wrapper_classes); ?>"
            role="region"
            aria-label="<?php esc_attr_e('Item list', 'flex-aahub'); ?>"
            tabindex="0"
        >
            <div
                class="<?php echo esc_attr($list_classes); ?>"
                role="table"
                aria-colcount="<?php echo esc_attr((string) $columns); ?>"
                style="<?php echo esc_attr('--faih-list-columns:' . $columns . ';'); ?>"
            >
                <div
                    class="faih-list-header faih-table-header faih-table-row"
                    role="row"
                >
                    <div
                        class="faih-list-cell faih-table-cell"
                        role="columnheader"
                    >
                        <?php esc_html_e('Item', 'flex-aahub'); ?>
                    </div>

                    <?php foreach ($headers as $header) : ?>
                        <div
                            class="faih-list-cell faih-table-cell"
                            role="columnheader"
                        >
                            <?php echo esc_html($header); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($this->items as $item) : ?>
                    <?php
                    if (!is_array($item)) {
                        continue;
                    }

                    $attributes = $this->build_attributes(
                        isset($item['attributes']) && is_array($item['attributes'])
                            ? $item['attributes']
                            : []
                    );
                    ?>

                    <div
                        class="faih-list-item faih-table-row"
                        role="row"
                        <?php echo $attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    >
                        <div
                            class="faih-list-cell faih-table-cell"
                            role="cell"
                        >
                            <?php
                            echo $this->render_title($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </div>

                        <?php foreach ($headers as $key => $label) : ?>
                            <div
                                class="faih-list-cell faih-table-cell"
                                role="cell"
                                data-label="<?php echo esc_attr($label); ?>"
                            >
                                <?php
                                echo wp_kses_post(
                                    (string) ($item['fields'][$key] ?? '—')
                                );
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php echo $this->render_pagination(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php
        return (string) ob_get_clean();
    }

    /**
     * Get this template's default configuration.
     *
     * @return array<string, mixed>
     */
    private function get_table_default_config(): array
    {
        return [
            'type'       => self::TYPE,
            'size'       => 'medium',
            'color'      => 'primary',
            'columns'    => null,
            'attribute'  => ['scroll-x'],
            'pagination' => [
                'enabled'      => false,
                'current_page' => 1,
                'total_pages'  => 1,
            ],
        ];
    }

    /**
     * Build the table headers from item fields.
     *
     * @param array<int|string, mixed> $items
     *
     * @return array<string|int, string>
     */
    private function get_headers(array $items): array
    {
        $headers = [];

        foreach ($items as $item) 
        {
            if (!is_array($item)) {
                continue;
            }

            $fields = isset($item['fields']) && is_array($item['fields'])
                ? $item['fields']
                : [];

            $labels = isset($item['labels']) && is_array($item['labels'])
                ? $item['labels']
                : [];

            foreach ($fields as $key => $value) 
            {
                $headers[$key] = isset($labels[$key])
                    ? (string) $labels[$key]
                    : $this->format_header_label((string) $key);
            }
        }

        return $headers;
    }

    private function format_header_label(string $key): string
    {
        $key = str_replace(['-', '_'], ' ', $key);

        return ucwords($key);
    }

    /**
     * Render the item title.
     *
     * @param array<string, mixed> $item
     */
    private function render_title(array $item): string
    {
        $title = esc_html((string) ($item['title'] ?? ''));

        $url = isset($item['url'])
            ? esc_url((string) $item['url'])
            : '';

        if ($url === '') 
        {
            return $title;
        }

        return sprintf(
            '<a class="faih-list-item-link" href="%1$s">%2$s</a>',
            $url,
            $title
        );
    }

    /**
     * Build validated HTML attributes.
     *
     * @param array<string, mixed> $attributes
     */
    private function build_attributes(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $name => $value) 
        {
            $attribute_name = $this->sanitize_attribute_name(
                (string) $name
            );

            if ($attribute_name === '' || $value === null) {
                continue;
            }

            if (is_bool($value)) {
                if ($value) {
                    $html .= sprintf(
                        ' %s',
                        esc_attr($attribute_name)
                    );
                }

                continue;
            }

            if (is_array($value)) {
                $value = implode(
                    ' ',
                    array_map('sanitize_text_field', $value)
                );
            }

            $html .= sprintf(
                ' %1$s="%2$s"',
                esc_attr($attribute_name),
                esc_attr((string) $value)
            );
        }

        return $html;
    }

    private function sanitize_attribute_name(string $name): string
    {
        $name = strtolower(trim($name));

        if (
            preg_match(
                '/^(id|title|name|value|tabindex|data-[a-z0-9_-]+|aria-[a-z0-9_-]+)$/',
                $name
            ) !== 1
        ) {
            return '';
        }

        return $name;
    }

    private function get_wrapper_classes(): string
    {
        $classes = [
            'faih-table-wrapper',
        ];

        $attributes = $this->config['attribute'] ?? [];

        if (is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $attribute = sanitize_html_class(
                    (string) $attribute
                );

                if ($attribute !== '') {
                    $classes[] = 'has-' . $attribute;
                }
            }
        }

        return implode(' ', array_unique($classes));
    }

    private function get_list_classes(): string
    {
        $type = sanitize_html_class(
            (string) ($this->config['type'] ?? self::TYPE)
        );

        $size = sanitize_html_class(
            (string) ($this->config['size'] ?? 'medium')
        );

        $color = sanitize_html_class(
            (string) ($this->config['color'] ?? 'primary')
        );

        return implode(
            ' ',
            [
                'faih-list',
                'faih-table',
                'type-' . $type,
                'size-' . $size,
                'color-' . $color,
            ]
        );
    }

    /**
     * Determine the rendered column count.
     *
     * The item-title column is included automatically.
     *
     * @param array<string|int, string> $headers
     */
    private function get_column_count(array $headers): int
    {
        $actual_columns = count($headers) + 1;

        $configured_columns = absint(
            $this->config['columns'] ?? $actual_columns
        );

        if ($configured_columns < 1) {
            $configured_columns = $actual_columns;
        }

        return max(
            1,
            min(12, $configured_columns)
        );
    }

    private function render_pagination(): string
    {
        $pagination_config = $this->config['pagination'] ?? [];

        if (
            !is_array($pagination_config)
            || empty($pagination_config['enabled'])
        ) {
            return '';
        }

        return $this->pagination_template->render(
            $pagination_config
        );
    }
}
