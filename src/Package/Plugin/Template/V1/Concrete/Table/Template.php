<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Table;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate
};

/**
 * Table template.
 *
 * Responsible only for presenting prepared items as a table.
 *
 * Expected item structure:
 *
 * [
 *     'id' => 123,
 *
 *     'post' => [
 *         'title' => 'Transaction',
 *         'date'  => '2026-08-21',
 *         ...
 *     ],
 *
 *     'meta' => [
 *         'amount' => 5000,
 *         ...
 *     ],
 *
 *     'taxonomies' => [
 *         'transaction_type' => [
 *             ...
 *         ],
 *     ],
 *
 *     'custom' => [
 *         ...
 *     ],
 * ]
 */
class Template extends BaseTemplate
{
    /**
     * Template type.
     *
     * @var string
     */
    protected string $type = 'table';

    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $default_config = [

        /*
         * Columns.
         */
        'columns' => [],

        /*
         * General.
         */
        'class' => '',

        'size' => 'medium',

        'color' => 'primary',

        'type' => 'normal',

        /*
         * Table attributes.
         *
         * Supported:
         *
         * scroll-x
         * bordered
         * hover
         * sticky-header
         * nowrap
         */
        'attribute' => '',

        /*
         * Caption.
         */
        'caption' => '',

        /*
         * Empty value.
         */
        'empty_value' => '—',

        /*
         * Automatic columns.
         */
        'auto_columns' => true,

        /*
         * Data groups.
         */
        'show_post_fields' => true,

        'show_meta' => true,

        'show_taxonomies' => true,

        'show_custom' => true,
    ];

    /**
     * Render table.
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

        /*
         * Nothing to render.
         */
        if (empty($items)) {
            return '';
        }

        /*
         * Resolve columns.
         */
        $columns = $this->resolve_columns(
            $items,
            is_array($data['fields'] ?? null)
                ? $data['fields']
                : []
        );

        if (empty($columns)) {
            return '';
        }

        /*
         * Build table classes.
         */
        $table_classes = $this->build_table_classes();

        /*
         * Caption.
         */
        $caption = trim(
            (string) $this->get_config_value(
                'caption',
                ''
            )
        );

        ob_start();
        ?>

        <div class="faih-table-wrapper">

            <table class="<?php echo esc_attr(
                implode(' ', $table_classes)
            ); ?>">

                <?php if ($caption !== '') : ?>

                    <caption class="faih-table-caption">
                        <?php echo esc_html($caption); ?>
                    </caption>

                <?php endif; ?>

                <thead class="faih-table-header">

                    <tr class="faih-table-row">

                        <?php foreach ($columns as $column) : ?>

                            <th
                                scope="col"
                                class="faih-table-cell"
                            >
                                <?php echo esc_html(
                                    $column['label']
                                ); ?>
                            </th>

                        <?php endforeach; ?>

                    </tr>

                </thead>

                <tbody class="faih-table-body">

                    <?php foreach ($items as $item) : ?>

                        <tr class="faih-table-row">

                            <?php foreach ($columns as $column) : ?>

                                <td class="faih-table-cell">

                                    <?php
                                    echo $this->render_value(
                                        $this->get_item_value(
                                            $item,
                                            $column['key']
                                        ),
                                        $column
                                    );
                                    ?>

                                </td>

                            <?php endforeach; ?>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <?php

        return (string) ob_get_clean();
    }

    /**
     * Build table classes.
     *
     * @return array
     */
    protected function build_table_classes(): array
    {
        $classes = [
            'faih-table',
        ];

        /*
         * Size.
         */
        $size = sanitize_html_class(
            (string) $this->get_config_value(
                'size',
                'medium'
            )
        );

        if ($size !== '') {
            $classes[] = 'size-' . $size;
        }

        /*
         * Color.
         */
        $color = sanitize_html_class(
            (string) $this->get_config_value(
                'color',
                'primary'
            )
        );

        if ($color !== '') {
            $classes[] = 'color-' . $color;
        }

        /*
         * Table type.
         */
        $type = sanitize_html_class(
            (string) $this->get_config_value(
                'type',
                'normal'
            )
        );

        if ($type !== '') {
            $classes[] = 'type-' . $type;
        }

        /*
         * Attributes.
         */
        $attributes = $this->get_config_value(
            'attribute',
            ''
        );

        if (is_string($attributes)) {
            $attributes = array_filter(
                array_map(
                    'trim',
                    explode(
                        ',',
                        $attributes
                    )
                )
            );
        }

        if (is_array($attributes)) {

            foreach ($attributes as $attribute) {

                $attribute = sanitize_html_class(
                    (string) $attribute
                );

                if ($attribute === '') {
                    continue;
                }

                /*
                 * Avoid duplicate attribute classes.
                 */
                $class = 'attribute-' . $attribute;

                if (!in_array(
                    $class,
                    $classes,
                    true
                )) {
                    $classes[] = $class;
                }
            }
        }

        /*
         * Custom class.
         */
        $custom_class = trim(
            (string) $this->get_config_value(
                'class',
                ''
            )
        );

        if ($custom_class !== '') {

            /*
             * Support multiple classes.
             */
            $custom_classes = preg_split(
                '/\s+/',
                $custom_class
            );

            foreach ($custom_classes as $class) {

                $class = sanitize_html_class(
                    $class
                );

                if ($class === '') {
                    continue;
                }

                $classes[] = $class;
            }
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Resolve columns.
     *
     * Priority:
     *
     * 1. Configured columns.
     * 2. Explicit fields.
     * 3. Automatic columns.
     *
     * @param array $items
     * @param array $fields
     * @return array
     */
    protected function resolve_columns(
        array $items,
        array $fields
    ): array {
        $configured = $this->get_config_value(
            'columns',
            []
        );

        /*
         * Support comma-separated columns.
         */
        if (is_string($configured)) {

            $configured = array_filter(
                array_map(
                    'trim',
                    explode(
                        ',',
                        $configured
                    )
                )
            );
        }

        /*
         * Explicit configuration.
         */
        if (
            is_array($configured)
            && !empty($configured)
        ) {
            return $this->normalize_columns(
                $configured
            );
        }

        /*
         * Explicit fields from template data.
         */
        if (!empty($fields)) {

            return $this->normalize_columns(
                $fields
            );
        }

        /*
         * Automatic columns.
         */
        if (
            $this->get_config_value(
                'auto_columns',
                true
            )
        ) {
            return $this->build_auto_columns(
                $items
            );
        }

        return [];
    }

    /**
     * Normalize columns.
     *
     * Supported:
     *
     * 'post.title'
     *
     * [
     *     'key'   => 'post.title',
     *     'label' => 'Title',
     *     'type'  => 'text',
     * ]
     *
     * @param array $columns
     * @return array
     */
    protected function normalize_columns(
        array $columns
    ): array {
        $normalized = [];

        foreach ($columns as $key => $column) {

            /*
             * Simple string definition.
             */
            if (is_string($column)) {

                $column_key = trim($column);

                if ($column_key === '') {
                    continue;
                }

                $normalized[] = [
                    'key' => $column_key,

                    'label' => $this->make_label(
                        $column_key
                    ),

                    'type' => 'text',
                ];

                continue;
            }

            /*
             * Invalid definition.
             */
            if (!is_array($column)) {
                continue;
            }

            /*
             * Resolve key.
             */
            $column_key = (string) (
                $column['key']
                ?? (
                    is_string($key)
                        ? $key
                        : ''
                )
            );

            $column_key = trim(
                $column_key
            );

            if ($column_key === '') {
                continue;
            }

            /*
             * Resolve label.
             */
            $label = (string) (
                $column['label']
                ?? $this->make_label(
                    $column_key
                )
            );

            /*
             * Resolve type.
             */
            $type = sanitize_key(
                (string) (
                    $column['type']
                    ?? 'text'
                )
            );

            $normalized[] = [

                'key' => $column_key,

                'label' => $label,

                'type' => $type !== ''
                    ? $type
                    : 'text',
            ];
        }

        return $normalized;
    }

    /**
     * Build automatic columns.
     *
     * @param array $items
     * @return array
     */
    protected function build_auto_columns(
        array $items
    ): array {
        $columns = [];

        $groups = [
            'post' => 'show_post_fields',

            'meta' => 'show_meta',

            'taxonomies' => 'show_taxonomies',

            'custom' => 'show_custom',
        ];

        foreach ($items as $item) {

            if (!is_array($item)) {
                continue;
            }

            foreach ($groups as $group => $config_key) {

                /*
                 * Group disabled.
                 */
                if (!$this->get_config_value(
                    $config_key,
                    true
                )) {
                    continue;
                }

                /*
                 * Group unavailable.
                 */
                if (!is_array(
                    $item[$group] ?? null
                )) {
                    continue;
                }

                foreach (
                    array_keys(
                        $item[$group]
                    ) as $key
                ) {

                    $path = $group . '.' . $key;

                    /*
                     * Already registered.
                     */
                    if (isset($columns[$path])) {
                        continue;
                    }

                    $columns[$path] = [
                        'key' => $path,

                        'label' => $this->make_label(
                            $key
                        ),

                        'type' =>
                            $group === 'taxonomies'
                                ? 'taxonomy'
                                : 'text',
                    ];
                }
            }
        }

        return array_values(
            $columns
        );
    }

    /**
     * Get nested item value.
     *
     * Examples:
     *
     * post.title
     * post.date
     * meta.amount
     * taxonomies.transaction_type
     * custom.amount_display
     *
     * @param array $item
     * @param string $path
     * @return mixed
     */
    protected function get_item_value(
        array $item,
        string $path
    ): mixed {
        $value = $item;

        foreach (
            explode(
                '.',
                $path
            ) as $segment
        ) {

            if (
                !is_array($value)
                || !array_key_exists(
                    $segment,
                    $value
                )
            ) {
                return null;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Render cell value.
     *
     * @param mixed $value
     * @param array $column
     * @return string
     */
    protected function render_value(
        mixed $value,
        array $column
    ): string {
        /*
         * Empty.
         */
        if (
            $value === null
            || $value === ''
        ) {
            return esc_html(
                (string) $this->get_config_value(
                    'empty_value',
                    '—'
                )
            );
        }

        /*
         * Common wrapped value.
         */
        if (
            is_array($value)
            && array_key_exists(
                'value',
                $value
            )
        ) {
            $value = $value['value'];
        }

        /*
         * Array.
         */
        if (is_array($value)) {

            return esc_html(
                $this->format_array_value(
                    $value
                )
            );
        }

        /*
         * URL.
         */
        if (
            ($column['type'] ?? 'text') === 'url'
            && is_string($value)
            && filter_var(
                $value,
                FILTER_VALIDATE_URL
            )
        ) {

            return sprintf(
                '<a class="faih-table-link" href="%s">%s</a>',

                esc_url($value),

                esc_html($value)
            );
        }

        /*
         * Date.
         */
        if (
            ($column['type'] ?? 'text') === 'date'
            && is_string($value)
        ) {

            $timestamp = strtotime(
                $value
            );

            if ($timestamp !== false) {

                return esc_html(
                    wp_date(
                        get_option(
                            'date_format'
                        ),
                        $timestamp
                    )
                );
            }
        }

        /*
         * Number.
         */
        if (
            ($column['type'] ?? 'text') === 'number'
            && is_numeric($value)
        ) {

            return esc_html(
                (string) $value
            );
        }

        /*
         * Taxonomy.
         */
        if (
            ($column['type'] ?? 'text') === 'taxonomy'
            && is_array($value)
        ) {

            return esc_html(
                $this->format_array_value(
                    $value
                )
            );
        }

        /*
         * Default scalar.
         */
        return esc_html(
            $this->format_scalar_value(
                $value
            )
        );
    }

    /**
     * Format array value.
     *
     * @param array $value
     * @return string
     */
    protected function format_array_value(
        array $value
    ): string {
        $values = [];

        foreach ($value as $item) {

            /*
             * Nested array.
             */
            if (is_array($item)) {

                /*
                 * Taxonomy term.
                 */
                if (
                    array_key_exists(
                        'name',
                        $item
                    )
                ) {
                    $values[] = (string) $item['name'];

                    continue;
                }

                /*
                 * Generic value.
                 */
                if (
                    array_key_exists(
                        'value',
                        $item
                    )
                ) {
                    $values[] =
                        $this->format_scalar_value(
                            $item['value']
                        );

                    continue;
                }

                continue;
            }

            /*
             * Scalar.
             */
            if (is_scalar($item)) {

                $values[] = (string) $item;
            }
        }

        return implode(
            ', ',
            $values
        );
    }

    /**
     * Format scalar value.
     *
     * @param mixed $value
     * @return string
     */
    protected function format_scalar_value(
        mixed $value
    ): string {
        if ($value instanceof \WP_Error) {
            return '';
        }

        if (is_bool($value)) {
            return $value
                ? 'Yes'
                : 'No';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    /**
     * Generate human-readable label.
     *
     * @param string $key
     * @return string
     */
    protected function make_label(
        string $key
    ): string {
        $label = preg_replace(
            '/^[._-]+/',
            '',
            $key
        );

        $label = str_replace(
            [
                '_',
                '-',
                '.',
            ],
            ' ',
            (string) $label
        );

        return ucwords(
            $label
        );
    }
}