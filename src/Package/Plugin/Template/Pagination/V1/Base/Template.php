<?php

declare(strict_types=1);

namespace Ababilithub\FlexAahub\Package\Plugin\Template\Pagination\V1\Base;

defined('ABSPATH') || exit;

use Ababilithub\{
    FlexAahub\Package\Plugin\Template\Pagination\V1\Contract\Template as PaginationTemplateContract,
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
};

abstract class Template extends BaseTemplate implements PaginationTemplateContract
{
    /**
     * @param array<string, mixed> $data
     */
    public function init(array $data = []): static
    {
        $this->set_default_config([
            'enabled' => true,
            'type' => 'paged',
            'attributes' => ['centered'],
            'size' => 'medium',
            'color' => 'primary',
            'per_page' => 10,
            'labels' => [],
        ]);

        $config = isset($data['config']) && is_array($data['config'])
            ? $data['config']
            : $data;

        return $this->set_config($config);
    }

    /**
     * @return array<string, string>
     */
    protected function resolve_labels(array $config): array
    {
        return array_replace([
            'previous' => __('Previous', 'flex-aahub'),
            'next' => __('Next', 'flex-aahub'),
            'load_more' => __('Load more', 'flex-aahub'),
            'aria' => __('List pagination', 'flex-aahub'),
        ], is_array($config['labels'] ?? null) ? $config['labels'] : []);
    }
}
