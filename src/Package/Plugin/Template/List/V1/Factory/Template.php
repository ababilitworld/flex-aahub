<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\List\V1\Factory;

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexAahub\Package\Plugin\Template\List\V1\Contract\Template as ListTemplateContract,
};

class Template extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return ListTemplateContract
     */
    protected static function resolve(string $targetClass): ListTemplateContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof ListTemplateContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement ListTemplateContract");
        }

        return $instance;
    }
} 