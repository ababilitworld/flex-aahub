<?php

namespace Ababilithub\FlexAahub\Package\Plugin\DTO\V1\Concrete\Taxonomy;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\DTO\V1\Base\DTO as BaseDTO;

class DTO extends BaseDTO
{
    protected array $default_data = [
        'id'          => null,
        'name'        => '',
        'slug'        => '',
        'taxonomy'    => '',
        'description' => '',
        'parent'      => 0,
        'count'       => 0,
        'link'        => '',
    ];

    protected array $default_config = [
        'include_children' => false,
    ];
}