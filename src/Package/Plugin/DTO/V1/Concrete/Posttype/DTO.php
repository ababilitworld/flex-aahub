<?php

namespace Ababilithub\FlexAahub\Package\Plugin\DTO\V1\Concrete\PostType;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\FlexWordpress\Package\DTO\V1\Base\DTO as BaseDTO;

class DTO extends BaseDTO
{
    protected array $default_data = [
        'id'        => null,
        'title'     => '',
        'date'      => '',
        'status'    => '',
        'type'      => '',
        'author'    => null,
        'content'   => '',
        'excerpt'   => '',
        'permalink' => '',
    ];

    protected array $default_config = [
        'sanitize' => true,
    ];
}