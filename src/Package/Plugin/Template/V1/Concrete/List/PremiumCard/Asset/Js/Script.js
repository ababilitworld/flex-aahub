jQuery(document).ready(function($) {
    // Initialize accordions
    $('.fa-filter-accordion .fa-accordion-header').on('click', function() {
        const accordion = $(this).parent();
        accordion.toggleClass('active');
        accordion.find('.fa-accordion-content').toggleClass('active');
    });

    // Initialize price slider
    $('.fa-price-slider').slider({
        range: true,
        min: 0,
        max: 1000000,
        step: 10000,
        values: [0, 1000000],
        slide: function(event, ui) {
            $('.fa-min-price').text('$' + ui.values[0].toLocaleString());
            $('.fa-max-price').text(ui.values[1] >= 1000000 ? '$1M+' : '$' + ui.values[1].toLocaleString());
            $('.fa-min-price-input').val(ui.values[0]);
            $('.fa-max-price-input').val(ui.values[1]);
            applyFilters();
        }
    });

    // Filter functionality
    function applyFilters() 
    {
        const activeFilters = {};
        const priceRange = $('.fa-price-slider').slider('values');
        
        // Get all checked filters
        $('.fa-filter-items input[type="checkbox"]:checked').each(function() {
            const taxonomy = $(this).closest('.fa-filter-accordion').data('taxonomy');
            if (!activeFilters[taxonomy]) {
                activeFilters[taxonomy] = [];
            }
            activeFilters[taxonomy].push($(this).val());
        });
        
        // Filter deed cards
        $('.fa-template-premiumcard').each(function() {
            const card = $(this);
            let shouldShow = true;
            
            // Check taxonomy filters
            for (const taxonomy in activeFilters) {
                if (activeFilters[taxonomy].length === 0) continue;
                
                const cardTerms = card.data(taxonomy)?.split(' ') || [];
                const hasMatch = activeFilters[taxonomy].some(term => cardTerms.includes(term));
                
                if (!hasMatch) {
                    shouldShow = false;
                    break;
                }
            }
            
            // Check price range
            if (shouldShow) {
                const cardPrice = parseFloat(card.data('price')) || 0;
                if (cardPrice < priceRange[0] || cardPrice > priceRange[1]) {
                    shouldShow = false;
                }
            }
            
            card.toggleClass('hidden', !shouldShow);
        });
    }
    
    // Apply filters when checkbox changes
    $('.fa-filter-items input[type="checkbox"]').on('change', applyFilters);
    
    // Reset all filters
    $('.fa-filter-reset-btn').on('click', function() {
        $('.fa-filter-items input[type="checkbox"]').prop('checked', false);
        $('.fa-price-slider').slider('values', [0, 1000000]);
        $('.fa-min-price').text('$0');
        $('.fa-max-price').text('$1M+');
        $('.fa-min-price-input').val(0);
        $('.fa-max-price-input').val(1000000);
        applyFilters();
    });
});