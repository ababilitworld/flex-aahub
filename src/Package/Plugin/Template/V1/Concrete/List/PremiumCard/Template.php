<?php
namespace Ababilithub\FlexAahub\Package\Plugin\Template\V1\Concrete\List\PremiumCard;

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardMixin,
    FlexWordpress\Package\Mixin\V1\Standard\Mixin as StandardWpMixin,
    FlexWordpress\Package\Template\V1\Base\Template as BaseTemplate,
};

use const Ababilithub\{
    FlexAahub\PLUGIN_URL,
};

class Template extends BaseTemplate
{
    use StandardMixin, StandardWpMixin;

    public function init(array $data =[]) : static
    {
        $this->asset_base_url = $this->get_url('Asset/');
        $this->asset_base_prefix = 'ababilithub-template-list-premiumcard';
        $this->init_service();
        $this->init_hook();
        return $this;
    }

    public function init_service():void
    {

    }

    public function init_hook() : void
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-slider');

        wp_enqueue_style(
            $this->asset_base_prefix.'-style', 
            $this->asset_base_url.'Css/Style.css',
            array(), 
            time()
        );

        wp_enqueue_script(
            $this->asset_base_prefix.'-script', 
            $this->asset_base_url.'Js/Script.js',
            array('jquery', 'jquery-ui-slider'), 
            time(), 
            true
        );
        
        wp_localize_script(
            $this->asset_base_prefix.'-script', 
            $this->asset_base_prefix.'_template_localize', 
            array(
                'adminAjaxUrl' => admin_url('admin-ajax.php'),
                'ajaxNonce' => wp_create_nonce($this->asset_base_prefix.'_nonce'),
            )
        );
    }

    public function render($items = null) : bool|string
    {        
        if (empty($items)) 
        {
            return '<p>No items found to render. !!!</p>';
        }
        
        ob_start();
        ?>
        <div class="fa-template-premiumcard-app">
            <!-- Search Panel -->
            <div class="fa-search-panel">
                <input type="text" class="fa-search-input" placeholder="Search deeds by location, type, or ID...">
                <button class="fa-search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <div class="fa-template-premiumcard-container">
                <!-- Filter Sidebar -->
                <aside class="fa-filter-sidebar">
                    <div class="fa-filter-header">
                        <h3 class="fa-filter-title">Filters</h3>
                        <button class="fa-filter-reset-btn">Reset All</button>
                    </div>

                    <div class="fa-filter-accordions">
                        <?php

                        foreach ($items['taxonomies'] as $taxonomy) 
                        {
                            if (in_array($taxonomy->name, ['post_tag', 'category'])) continue;
                            
                            $terms = get_terms([
                                'taxonomy' => $taxonomy->name,
                                'hide_empty' => true,
                            ]);
                            
                            if (empty($terms)) continue;
                            
                            $icon = $items['taxonomies_icon_map'][$taxonomy->name] ?? 'fa-filter';
                            ?>
                            <div class="fa-filter-accordion" data-taxonomy="<?php echo esc_attr($taxonomy->name); ?>">
                                <button class="fa-accordion-header">
                                    <div class="fa-accordion-title">
                                        <i class="fas <?php echo esc_attr($icon); ?>"></i>
                                        <span><?php echo esc_html($taxonomy->label); ?></span>
                                    </div>
                                    <i class="fas fa-chevron-down fa-accordion-icon"></i>
                                </button>
                                <div class="fa-accordion-content">
                                    <div class="fa-filter-items">
                                        <?php foreach ($terms as $term) { ?>
                                            <label class="fa-filter-item">
                                                <input type="checkbox" 
                                                    name="<?php echo esc_attr($taxonomy->name); ?>[]" 
                                                    value="<?php echo esc_attr($term->slug); ?>">
                                                <span class="fa-filter-label"><?php echo esc_html($term->name); ?></span>
                                                <span class="fa-filter-badge"><?php echo esc_html($term->count); ?></span>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Price Range Filter -->
                        <div class="fa-filter-accordion">
                            <button class="fa-accordion-header">
                                <div class="fa-accordion-title">
                                    <i class="fas fa-tag"></i>
                                    <span>Price Range</span>
                                </div>
                                <i class="fas fa-chevron-down fa-accordion-icon"></i>
                            </button>
                            <div class="fa-accordion-content">
                                <div class="fa-price-range-filter">
                                    <div class="fa-price-values">
                                        <span class="fa-min-price">$0</span>
                                        <span class="fa-max-price">$1M+</span>
                                    </div>
                                    <div class="fa-price-slider"></div>
                                    <input type="hidden" class="fa-min-price-input" name="min_price" value="0">
                                    <input type="hidden" class="fa-max-price-input" name="max_price" value="1000000">
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="fa-template-premiumcard-list-container">
                    <div class="fa-template-premiumcard-list">
                        <?php 
                        foreach ($items['posts'] as $post) 
                        {
                            $price = get_post_meta($post->ID, 'price', true);
                            $size = get_post_meta($post->ID, 'land-quantity', true);
                            $deed_number = get_post_meta($post->ID, 'deed-number', true);
                            $thumbnail = get_the_post_thumbnail_url($post->ID, 'large') ?: admin_url('/images/wordpress-logo.svg');
                            
                            // Get terms for filtering
                            $terms_data = [];
                            foreach ($items['taxonomies'] as $tax) {
                                $terms = wp_get_post_terms($post->ID, $tax->name, ['fields' => 'slugs']);
                                if (!is_wp_error($terms)) {
                                    $terms_data[$tax->name] = $terms;
                                }
                            }
                            ?>
                            <article class="fa-template-premiumcard" 
                                data-price="<?php echo esc_attr($price ?: 0); ?>"
                                <?php foreach ($terms_data as $tax => $terms) {
                                    echo 'data-' . esc_attr($tax) . '="' . esc_attr(implode(' ', $terms)) . '" ';
                                } ?>>
                                <div class="fa-template-premiumcard-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>')"></div>
                                <div class="fa-template-premiumcard-content">
                                    <h3><?php echo esc_html(get_the_title($post)); ?></h3>
                                    <div class="fa-template-premiumcard-meta">
                                        <?php if ($deed_number) { ?>
                                            <span><i class="fas fa-file-alt"></i> <?php echo esc_html($deed_number); ?></span>
                                        <?php } ?>
                                        <?php if ($size) { ?>
                                            <span><i class="fas fa-ruler-combined"></i> <?php echo esc_html($size); ?> Decimal</span>
                                        <?php } ?>
                                    </div>
                                    <div class="fa-template-premiumcard-footer">
                                        <?php if ($price) { ?>
                                            <div class="fa-template-premiumcard-price">$<?php echo number_format($price); ?></div>
                                        <?php } ?>
                                        <a href="<?php the_permalink($post); ?>" class="fa-view-btn">View Details</a>
                                    </div>
                                </div>
                            </article>
                        <?php } ?>
                    </div>
                </main>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}