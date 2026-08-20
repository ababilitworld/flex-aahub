<?php

declare(strict_types=1);

namespace Ababilithub\FlexAahub\Package\Plugin\Template\Pagination\V1\Concrete\PreviousNext;

defined('ABSPATH') || exit;

use Ababilithub\FlexAahub\Package\Plugin\Template\Pagination\V1\Base\Template as BasePaginationTemplate;

class Template extends BasePaginationTemplate
{
    public const TYPE = 'previous-next';

    public function get_type(): string
    {
        return self::TYPE;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data = []): string
    {
        $config = $this->resolve_config($data);

        if (empty($config['enabled']) || ($config['type'] ?? null) === 'none') {
            return '';
        }

        $labels = $this->resolve_labels($config);
        $classes = $this->build_classes(
            self::TYPE,
            (string) ($config['size'] ?? 'medium'),
            (string) ($config['color'] ?? 'primary'),
            $config['attributes'] ?? []
        );

        ob_start();
        ?>
        <nav class="<?php echo esc_attr($classes); ?>"
            data-pagination
            data-pagination-type="<?php echo esc_attr(self::TYPE); ?>"
            data-per-page="<?php echo esc_attr(max(1, (int) ($config['per_page'] ?? 10))); ?>"
            aria-label="<?php echo esc_attr((string) $labels['aria']); ?>">
            <button class="faih-pagination-button faih-pagination-previous" type="button" data-page-previous>
                <?php echo esc_html((string) $labels['previous']); ?>
            </button>
            <span class="faih-pagination-status" data-pagination-status aria-live="polite"></span>
            <button class="faih-pagination-button faih-pagination-next" type="button" data-page-next>
                <?php echo esc_html((string) $labels['next']); ?>
            </button>
        </nav>
        <?php

        return (string) ob_get_clean();
    }
}
