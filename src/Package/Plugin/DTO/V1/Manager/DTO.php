<?php

namespace Ababilithub\FlexAahub\Package\Plugin\DTO\V1\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,

    FlexWordpress\Package\DTO\V1\Contract\DTO as DTOContract,
    FlexWordpress\Package\DTO\V1\Factory\DTO as DTOFactory,

    FlexAahub\Package\Plugin\DTO\V1\Concrete\PostType\DTO as PostTypeDTO,
    FlexAahub\Package\Plugin\DTO\V1\Concrete\Taxonomy\DTO as TaxonomyDTO,
};

class DTO extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->set_items([
            PostTypeDTO::class,
            TaxonomyDTO::class,

            // Enable/disable implementations here.
        ]);
    }

    public function boot(): void
    {
        foreach ($this->get_items() as $item_class) 
        {

            $dto = DTOFactory::get($item_class);

            if (!$dto instanceof DTOContract) {
                continue;
            }
        }
    }
}