<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Contract;

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Contract\Template as BaseTemplateContract,
};

interface Template extends BaseTemplateContract
{
    public function get_type(): string;
}