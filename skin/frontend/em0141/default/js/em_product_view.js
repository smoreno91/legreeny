/*
 * Galathemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - Galathemes.com. (http://www.galathemes.com)
 */

(function($) {
    if (typeof EM == 'undefined') EM = {};
    if (typeof EM.SETTING == 'undefined') EM.SETTING = {};
    var domLoaded = false;
    // details tabs
    function decorateProductCollateralTabs() {
        var sTab = $('.em-details-tabs');
        if (sTab.length) {
            sTab.each(function(i) {
                $(this).prepend("<ul class='tabs-control'></ul>");
                var controlTab = $('.tabs-control', this);
                $('.box-collateral', $(this)).each(function(j) {
                    id = 'em-details-tab-' + i + '-' + j;
                    $(this).attr('id', id);
                    controlTab.append('<li><a href="#' + id + '">' + $('h2', this).html() + '</a></li>');
                });
                $(this).responsiveTabs({
                    animation: 'fade',
                    startCollapsed:'accordion', 
                });
            });
        }
    };

    //scroll review
    function ScrollToElement(e) {
        if ($(e).size()) {
            $('html, body').animate({
                scrollTop: $(e).offset().top
            }, 500);
        } else {
            return false;
        }
        return true;
    };

    function ScrollToReview() {
        var sReview = $('.product-view .product-essential ');
        var sClick1 = $('.link_review_list', sReview);
        if(sClick1.length){
            sClick1.click(function(e) {
                if (ScrollToElement('#customer_review_list')) {
                    e.preventDefault();
                }
            });
        }
        
        var sClick2 = $('.link_review_form', sReview);
        if(sClick2.length){
            sClick2.click(function(e) {
                if (ScrollToElement('#customer_review_form')) {
                    e.preventDefault();
                }
            });
        }
    };
    //Ready Function
    $(document).ready(function() {
        domLoaded = true;
        // details
        if (EM.SETTING.USE_TAB != 0) {
            decorateProductCollateralTabs();
        }
        ScrollToReview();
              
    });
    $(window).load(function() {
        setEqualElement('#em-wrapper-related','.product-name');       
        setEqualElement('#em-wrapper-upsell','.product-name'); 
    });
})(jQuery);