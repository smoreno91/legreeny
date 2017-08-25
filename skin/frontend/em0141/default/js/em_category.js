/*
 * Galathemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - Galathemes.com. (http://www.galathemes.com)
 */
 (function($) {
    if (typeof EM == 'undefined') EM = {};
    if (typeof EM.SETTING == 'undefined') EM.SETTING = {};
    
    //Ready Function
    $(document).ready(function() {
        // category
        toolbarCategory();
    });

    //Load Function
    $(window).bind('load', function() {
        setTimeout(function() { 
            setEqualHeightItemsCategory();
        }, 2000);
    });
    var tmresize;
    $(window).resize($.throttle(300,function(){
        if (EM.SETTING.DISABLE_RESPONSIVE != 0) {
            clearTimeout(tmresize);
            tmresize = setTimeout(function() {
                if (typeof em_slider !== 'undefined') {
                    em_slider.reinit();
                }
            }, 300);
        }
        setTimeout(function() {
            setEqualHeightItemsCategory();
        }, 2000);   
    }));
})(jQuery);

function toolbarCategory() {
    var $ = jQuery;
    if (!isMobile) {
        var sToolbar = $('.toolbar-top').find('.toolbar-show');
        var sSortby = $('.toolbar-top').find('.sortby');
        if(sToolbar.length){
            sToolbar.insertUl();
            sToolbar.selectUl();
        }
        if(sSortby.length){
            sSortby.insertUl();
            sSortby.selectUl();
        }
    }
};

//After Layer Update
window.afterLayerUpdate = function() {
    var $ = jQuery;
    toolbarCategory();  
    setTimeout(function(){
        setEqualHeightItemsCategory();
    },500);
    if (EM.SETTING.DISABLE_AJAX_ADDTO != 0){
        var sWishlist = $('.add-to-links').find('a.link-wishlist');
        if(sWishlist.length){sWishlist.emAjaxWishList();}            

        var sCompare = $('.add-to-links').find('a.link-compare');
        if(sCompare.length){
            sCompare.emAjaxCompare({
                sidebarSelector : ".block-compare"
            });
        }
    } 
    if(EM.SETTING.COLOR_SWATCHES!='0'){ConfigurableSwatchesList.init(); }
    if ((typeof EM_QUICKSHOP_DISABLED == 'undefined' || !EM_QUICKSHOP_DISABLED)){
        em_qs();  
    }
    
    var $_img= $('.category-products').find('img[data-original]');
    $_img.bind('inview', function(event, isVisible) {
        if (!isVisible) { return; }        
        var $_this = $(this);
        $_this.attr('src', $_this.attr('data-original')).removeAttr('data-original');
        if($_this.hasClass('img-banner-lazyload')){$_this.removeClass('img-banner-lazyload');}
        if($_this.hasClass('em-img-lazy')){$_this.removeClass('em-img-lazy');}
    }); 
};

function setEqualHeightItemsCategory() {
    var $ = jQuery;
    var $list = $('#em-grid-mode');
    var len = $list.length;
    if(len){        
        var gridItemMaxHeight = 0;
        var $listItems = $list.find('li.item');
        var lenLi = $listItems.length;
        if(lenLi){
            for(var j=0;j<lenLi;j++){
                $listItems.eq(j).css('height', '');
                gridItemMaxHeight = Math.max(gridItemMaxHeight, $listItems.eq(j).height());
            }
        }
        $listItems.height(gridItemMaxHeight);
    }
};