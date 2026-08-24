<?php

namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\PremiumCard;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate
};

use const Ababilithub\{
    FlexAahub\PLUGIN_URL,
    FlexAahub\PLUGIN_PRE_UNDS,
    FlexAahub\PLUGIN_PRE_HYPH,
};

class Template extends BaseTemplate
{
    protected string $type = 'premium-card';

    protected array $default_config = [
        'columns' => 3,
        'class' => '',
        'size' => 'medium',
        'color' => 'primary',
    ];

    public function init($data = []): static
    {
        // Compute the absolute URL to the Asset folder (4 levels up from this file)
        $this->asset_base_prefix = PLUGIN_PRE_HYPH.'-template-premium-card';
        $this->asset_base_url = PLUGIN_URL.'/src/Package/Plugin/';
        
        $this->init_hook();
        $this->init_service();
        return $this;
    }

    public function init_hook(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));       
    }

    public function init_service(): void
    {
        //
    }

    public function enqueue_scripts(): void
    {
        wp_enqueue_style(
            $this->asset_base_prefix.'-style', 
            $this->asset_base_url.'Template/V1/Concrete/PremiumCard/Asset/Css/Manager/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            $this->asset_base_prefix.'-script',
            $this->asset_base_url.'Template/V1/Concrete/PremiumCard/Asset/Js/Manager/Script.js',
            array(),
            time(),
            true
        );
    }

    /**
     * Render premium cards.
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

        if (!$items) {
            return '';
        }

        ob_start();
        ?>

        <div class="flex-aahub-template-premium-card <?php echo esc_attr(
            $this->get_config_value(
                'class',
                ''
            )
        ); ?>">

            <?php foreach ($items as $item) : ?>

                <article class="flex-aahub-template-premium-card__item">

                    <h3 class="flex-aahub-template-premium-card__title">
                        <?php echo esc_html(
                            $item['post']['title'] ?? ''
                        ); ?>
                    </h3>

                </article>

                <article 
                    class="faih-card
                    type-premium
                    size-medium
                    color-primary
                    orientation-vertical
                    attribute-bordered
                    attribute-rounded
                    attribute-hover"
                >

                    <div class="faih-card-media">

                        <img
                            class="faih-card-image"
                            src="https://example.com/image.jpg"
                            alt="Card title"
                            loading="lazy"
                        >

                        <span class="faih-card-badge">
                            Featured
                        </span>

                    </div>


                    <div class="faih-card-content">

                        <header class="faih-card-header">

                            <div class="faih-card-heading">

                                <div class="faih-card-eyebrow">
                                    Category
                                </div>

                                <h3 class="faih-card-title">
                                    <?php 
                                        echo esc_html(
                                            $item['post']['title'] ?? ''
                                        ); 
                                    ?>
                                </h3>

                            </div>

                            <div class="faih-card-header-actions">

                                <button
                                    type="button"
                                    class="faih-card-action"
                                    aria-label="More options"
                                >
                                    <i
                                        class="fas fa-ellipsis-v"
                                        aria-hidden="true"
                                    ></i>
                                </button>

                            </div>

                        </header>


                        <div class="faih-card-body">

                            <div class="faih-card-description">

                                <p>
                                    This is the reusable description area of the
                                    generic premium card component.
                                </p>

                            </div>


                            <div class="faih-card-meta">

                                <div class="faih-card-meta-item">

                                    <span class="faih-card-meta-icon">
                                        <i
                                            class="fas fa-file-alt"
                                            aria-hidden="true"
                                        ></i>
                                    </span>

                                    <span class="faih-card-meta-content">
                                        REF-001
                                    </span>

                                </div>


                                <div class="faih-card-meta-item">

                                    <span class="faih-card-meta-icon">
                                        <i
                                            class="fas fa-calendar"
                                            aria-hidden="true"
                                        ></i>
                                    </span>

                                    <span class="faih-card-meta-content">
                                        24 Aug 2026
                                    </span>

                                </div>

                            </div>

                        </div>


                        <footer class="faih-card-footer">

                            <div class="faih-card-footer-primary">

                                <div class="faih-card-price">

                                    <span class="faih-card-price-label">
                                        Price
                                    </span>

                                    <strong class="faih-card-price-value">
                                        $25,000
                                    </strong>

                                </div>

                            </div>


                            <div class="faih-card-footer-actions">

                                <a
                                    href="#"
                                    class="faih-card-button"
                                >
                                    View Details
                                </a>

                            </div>

                        </footer>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

        <?php

        return (string) ob_get_clean();
    }
}