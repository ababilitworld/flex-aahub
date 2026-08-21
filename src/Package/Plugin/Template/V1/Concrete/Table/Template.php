<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\Table;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List\Template as ListTemplate;

class Template extends ListTemplate
{
    /**
     * Template type.
     */
    protected string $type = 'table';

    /**
     * Default data.
     *
     * @var array<string, mixed>
     */
    protected array $default_data = [
        'columns' => [],
        'rows' => [],
        'empty_message' => 'No records found.',
    ];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $default_config = [
        'container_class' => 'flex-template-table',
        'table_class' => 'flex-template-table__table',
        'head_class' => 'flex-template-table__head',
        'body_class' => 'flex-template-table__body',
        'row_class' => 'flex-template-table__row',
        'header_cell_class' => 'flex-template-table__header-cell',
        'cell_class' => 'flex-template-table__cell',
        'empty_class' => 'flex-template-table__empty',
    ];

    /**
     * Render table.
     */
    protected function render_html(
        array $data
    ): string {
        $columns = $data['columns'] ?? [];
        $rows = $data['rows'] ?? [];

        if (!is_array($columns)) {
            $columns = [];
        }

        if (!is_array($rows)) {
            $rows = [];
        }

        if ($rows === []) {
            return $this->render_empty(
                $data
            );
        }

        $html = sprintf(
            '<div class="%1$s"><table class="%2$s">',
            $this->classes(
                $this->get_config_value(
                    'container_class'
                )
            ),
            $this->classes(
                $this->get_config_value(
                    'table_class'
                )
            )
        );

        if ($columns !== []) {
            $html .= sprintf(
                '<thead class="%1$s"><tr>',
                $this->classes(
                    $this->get_config_value(
                        'head_class'
                    )
                )
            );

            foreach ($columns as $column) {
                $label = is_array($column)
                    ? ($column['label'] ?? '')
                    : $column;

                $html .= sprintf(
                    '<th class="%1$s">%2$s</th>',
                    $this->classes(
                        $this->get_config_value(
                            'header_cell_class'
                        )
                    ),
                    $this->text($label)
                );
            }

            $html .= '</tr></thead>';
        }

        $html .= sprintf(
            '<tbody class="%1$s">',
            $this->classes(
                $this->get_config_value(
                    'body_class'
                )
            )
        );

        foreach ($rows as $row) {
            $html .= $this->render_row(
                $row,
                $columns
            );
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Render table row.
     */
    protected function render_row(
        mixed $row,
        array $columns
    ): string {
        $html = sprintf(
            '<tr class="%1$s">',
            $this->classes(
                $this->get_config_value(
                    'row_class'
                )
            )
        );

        foreach ($columns as $column) {
            $key = is_array($column)
                ? ($column['key'] ?? '')
                : '';

            $value = '';

            if (is_array($row)) {
                $value = $row[$key] ?? '';
            }

            $html .= sprintf(
                '<td class="%1$s">%2$s</td>',
                $this->classes(
                    $this->get_config_value(
                        'cell_class'
                    )
                ),
                $this->text($value)
            );
        }

        $html .= '</tr>';

        return $html;
    }
}