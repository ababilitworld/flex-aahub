<?php

declare(strict_types=1);

namespace Ababilithub\FlexAahub\Package\Plugin\Template\Pagination\V1\Contract;

defined('ABSPATH') || exit;

use Ababilithub\FlexWordpress\Package\Template\V1\Contract\Template as BaseTemplateContract;

interface Template extends BaseTemplateContract
{
    public function get_type(): string;

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data = []): string;
}
