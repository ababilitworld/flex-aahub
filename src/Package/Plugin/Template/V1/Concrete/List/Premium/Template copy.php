<?php ?>
    <div class="faih-template-list-premium-deed-app">
        <!-- Search Panel -->
        <div class="faih-template-list-premium-search-panel">
            <input type="text" class="faih-template-list-premium-search-input" placeholder="Search deeds by location, type, or ID...">
            <button class="faih-template-list-premium-search-btn">
                <i class="fas fa-search"></i> Search
            </button>
        </div>

        <div class="faih-template-list-premium-deed-container">
            <!-- Filter Sidebar -->
            <aside class="faih-template-list-premium-filter-sidebar">
                <div class="faih-template-list-premium-filter-header">
                    <h3 class="faih-template-list-premium-filter-title">Filters</h3>
                    <button class="faih-template-list-premium-filter-reset-btn">Reset All</button>
                </div>

                <div class="faih-template-list-premium-filter-accordions">
                    <?php
                    $taxonomies = get_object_taxonomies('fldeed', 'objects');
                    $icon_map = [
                        'district' => 'fa-map-marker-alt',
                        'thana' => 'fa-map-pin',
                        'land-mouza' => 'fa-vector-square',
                        'land-survey' => 'fa-ruler-combined',
                        'deed-type' => 'fa-file-contract',
                        'price-range' => 'fa-tag'
                    ];

                    foreach ($taxonomies as $taxonomy) {
                        if (in_array($taxonomy->name, ['post_tag', 'category'])) continue;
                        
                        $terms = get_terms([
                            'taxonomy' => $taxonomy->name,
                            'hide_empty' => true,
                        ]);
                        
                        if (empty($terms)) continue;
                        
                        $icon = $icon_map[$taxonomy->name] ?? 'fa-filter';
                        ?>
                        <div class="faih-template-list-premium-filter-accordion" data-taxonomy="<?php echo esc_attr($taxonomy->name); ?>">
                            <button class="faih-template-list-premium-accordion-header">
                                <div class="faih-template-list-premium-accordion-title">
                                    <i class="fas <?php echo esc_attr($icon); ?>"></i>
                                    <span><?php echo esc_html($taxonomy->label); ?></span>
                                </div>
                                <i class="fas fa-chevron-down fa-accordion-icon"></i>
                            </button>
                            <div class="faih-template-list-premium-accordion-content">
                                <div class="faih-template-list-premium-filter-items">
                                    <?php foreach ($terms as $term) { ?>
                                        <label class="faih-template-list-premium-filter-item">
                                            <input type="checkbox" 
                                                name="<?php echo esc_attr($taxonomy->name); ?>[]" 
                                                value="<?php echo esc_attr($term->slug); ?>">
                                            <span class="faih-template-list-premium-filter-label"><?php echo esc_html($term->name); ?></span>
                                            <span class="faih-template-list-premium-filter-badge"><?php echo esc_html($term->count); ?></span>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Price Range Filter -->
                    <div class="faih-template-list-premium-filter-accordion">
                        <button class="faih-template-list-premium-accordion-header">
                            <div class="faih-template-list-premium-accordion-title">
                                <i class="fas fa-tag"></i>
                                <span>Price Range</span>
                            </div>
                            <i class="fas fa-chevron-down fa-accordion-icon"></i>
                        </button>
                        <div class="faih-template-list-premium-accordion-content">
                            <div class="faih-template-list-premium-price-range-filter">
                                <div class="faih-template-list-premium-price-values">
                                    <span class="faih-template-list-premium-min-price">$0</span>
                                    <span class="faih-template-list-premium-max-price">$1M+</span>
                                </div>
                                <div class="faih-template-list-premium-price-slider"></div>
                                <input type="hidden" class="faih-template-list-premium-min-price-input" name="min_price" value="0">
                                <input type="hidden" class="faih-template-list-premium-max-price-input" name="max_price" value="1000000">
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="faih-template-list-premium-deed-list-container">
                <div class="faih-template-list-premium-deed-list">
                    <?php foreach ($posts as $post) {
                        $price = get_post_meta($post->ID, 'price', true);
                        $size = get_post_meta($post->ID, 'land-quantity', true);
                        $deed_number = get_post_meta($post->ID, 'deed-number', true);
                        $thumbnail = get_the_post_thumbnail_url($post->ID, 'large') ?: PLUGIN_URL . 'assets/images/default-land.jpg';
                        
                        // Get terms for filtering
                        $terms_data = [];
                        foreach ($taxonomies as $tax) {
                            $terms = wp_get_post_terms($post->ID, $tax->name, ['fields' => 'slugs']);
                            if (!is_wp_error($terms)) {
                                $terms_data[$tax->name] = $terms;
                            }
                        }
                        ?>
                        <article class="faih-template-list-premium-deed-card" 
                            data-price="<?php echo esc_attr($price ?: 0); ?>"
                            <?php foreach ($terms_data as $tax => $terms) {
                                echo 'data-' . esc_attr($tax) . '="' . esc_attr(implode(' ', $terms)) . '" ';
                            } ?>>
                            <div class="faih-template-list-premium-deed-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>')"></div>
                            <div class="faih-template-list-premium-deed-content">
                                <h3><?php echo esc_html(get_the_title($post)); ?></h3>
                                <div class="faih-template-list-premium-deed-meta">
                                    <?php if ($deed_number) { ?>
                                        <span><i class="fas fa-file-alt"></i> <?php echo esc_html($deed_number); ?></span>
                                    <?php } ?>
                                    <?php if ($size) { ?>
                                        <span><i class="fas fa-ruler-combined"></i> <?php echo esc_html($size); ?> Decimal</span>
                                    <?php } ?>
                                </div>
                                <div class="faih-template-list-premium-deed-footer">
                                    <?php if ($price) { ?>
                                        <div class="faih-template-list-premium-deed-price">$<?php echo number_format($price); ?></div>
                                    <?php } ?>
                                    <a href="<?php the_permalink($post); ?>" class="faih-template-list-premium-view-btn">View Details</a>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

<?php ?>