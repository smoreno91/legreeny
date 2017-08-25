/*
 * Galathemes
 *
 * @license commercial software
 * @copyright (c) 2014 Codespot Software JSC - Galathemes.com. (http://www.galathemes.com)
 */
(function($) {
    if (typeof EM == 'undefined') EM = {};
    if (typeof EM.SETTING == 'undefined') EM.SETTING = {};
    /* Retina Image Mobile */
    function emRetina(){        
        if (window.devicePixelRatio > 1 ||
	       (window.matchMedia && window.matchMedia("(-webkit-min-device-pixel-ratio: 1.5),(-moz-min-device-pixel-ratio: 1.5),(min-device-pixel-ratio: 1.5)").matches)) {
			  var images = $('img.retina-img');
              var len = images.length;
              if(len){
                    /* loop through the images and make them hi-res */
    			  for(var i = 0; i < len; i++) {    
    				/* create new image name */
    				var imageType = images[i].src.substr(-4);
    				var imageName = images[i].src.substr(0, images[i].src.length - 4);
    				imageName += "@2x" + imageType;
    
    				/* rename image */
    				images[i].src = imageName;
    			  }
              }
		 }
    };
    
    function emCollapsibleMobile(){
        if(isPhone){
            var sCollap = $("[data-collapse-target]");            
            var len = sCollap.length;
            if(len){
                sCollap.removeClass('em-collapsed non-collapsed').addClass('non-collapsed');
                for(var i=0;i<len;i++){
                    var id = sCollap.eq(i).data('collapse-target');
                    var $id=$(id);
                    if($id.length){
                        if($id.is(':visible')){
                            $id.hide(200,function(){
                                $(this).css('overflow','inherit');
                            });
                        }
                    }                    
                }
                sCollap.unbind('click');
                sCollap.click(function(){
                    var $this = $(this);
                    $this.toggleClass('non-collapsed');
                    $this.toggleClass('em-collapsed');
                    var id = $this.data('collapse-target');
                    var $id = $(id);
                    $id.slideToggle(200,function(){
                        $(this).css('overflow','inherit');
                    });
                });
            }
        } 
    };
    
    function emCollapsible(){        
        if(!isPhone){
            var wi = $(window).width();
            var sCollap = $("[data-collapse-target]");            
            var len = sCollap.length;
            if(wi<768){                
                if(len){
                    sCollap.removeClass('em-collapsed non-collapsed').addClass('non-collapsed');
                    for(var i=0;i<len;i++){
                        var id = sCollap.eq(i).data('collapse-target');
                        var $id=$(id);
                        if($id.length){
                            if($id.is(':visible')){
                                $id.hide(200,function(){
                                    $(this).css('overflow','inherit');
                                });
                            }
                        }                    
                    }
                    sCollap.unbind('click');
                    sCollap.click(function(){
                        var $this = $(this);
                        $this.toggleClass('non-collapsed');
                        $this.toggleClass('em-collapsed');
                        var id = $this.data('collapse-target');
                        var $id = $(id);
                        $id.slideToggle(200,function(){
                            $(this).css('overflow','inherit');
                        });
                    });
                } 
            }else{
                if(len){
                    sCollap.removeClass('em-collapsed non-collapsed');
                    sCollap.unbind('click');
                    for(var i=0;i<len;i++){
                        var id = sCollap.eq(i).data('collapse-target');
                        var $id=$(id);
                        if($id.length){
                            if(!$id.is(':visible')){
                                $id.show(200,function(){
                                    $(this).css('overflow','inherit');
                                });
                            }
                        }                    
                    }
                } 
            }
            
        }        
    };

    /* Fix iPhone/iPod auto zoom-in when text fields, select boxes are focus */
    function fixIPhoneAutoZoomWhenFocus() {
        var viewport = $('head meta[name=viewport]');
        if (viewport.length == 0) {
            $('head').append("<meta name='viewport' content='width=device-width, initial-scale=1.0'/>");
            viewport = $('head meta[name=viewport]');
        }
        var old_content = viewport.attr('content');

        function zoomDisable() {
            viewport.attr('content', old_content + ', user-scalable=0');
        }

        function zoomEnable() {
            viewport.attr('content', old_content);
        }
        $('input[type=text], textarea, select').mouseover(zoomDisable).mousedown(zoomEnable);
    };
    
    /* sticky menu */
    function stickyElement() {
        var $_e = $('.em-fixed-top');
        if ($_e.length) {            
            if (EM.SETTING.STICKY_MENU_SEARCH_CART != 0 && !isPhone) {
                var sticky_navigation = function() {
                    var wWindow = getWindowWidth();
                    var scroll_top = $(window).scrollTop();
                    var navpos = $('#em-fixed-top').offset().top;
                    if (wWindow > 767) {
                        if (scroll_top > navpos) {
                            if (!$_e.hasClass('navbar-fixed-top')) {
                                $_e.addClass('navbar-fixed-top');
                                jQuery(window).trigger('emfixtop');
                            }
                        } else {
                            if ($_e.hasClass('navbar-fixed-top')) { 
                                $_e.removeClass('navbar-fixed-top');
                            }
                        }
                    } else {
                        if ($_e.hasClass('navbar-fixed-top')) {   
                            $_e.removeClass('navbar-fixed-top');
                        }
                    }
                };
                $(window).scroll(function() {
                    sticky_navigation();
                });
                sticky_navigation();
            }            
        }
    };

    /* sticky menu no responsive */
    function freezedMenuNoResponsive() {
        var $_e = $('.em-fixed-top');
		if(typeof(sMenu) == 'undefined') return;
        if (sMenu.length) {
            if (EM.SETTING.STICKY_MENU_SEARCH_CART != 0 && !isPhone) {
                var sticky_navigation = function() {
                    var scroll_top = $(window).scrollTop();
                    var navpos = $('#em-fixed-top').offset().top;
                    if (scroll_top > navpos) {
                        if (!sMenu.hasClass('navbar-fixed-top')) {  
                            sMenu.addClass('navbar-fixed-top');
                        }
                    } else {
                        if (sMenu.hasClass('navbar-fixed-top')) {
                            sMenu.removeClass('navbar-fixed-top');
                        }
                    }
                };
                $(window).scroll(function() {
                    sticky_navigation();
                });
                sticky_navigation();
            }
            if (isPhone) {
                if (sMenu.hasClass('navbar-fixed-top')) { 
                    sMenu.removeClass('navbar-fixed-top');
                }
            }
        }
    };
    /* random string */
    function randomString(len, charSet) {
        charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var randomString = '';
        for (var i = 0; i < len; i++) {
            var randomPoz = Math.floor(Math.random() * charSet.length);
            randomString += charSet.substring(randomPoz, randomPoz + 1);
        }
        return randomString;
    };

    /* do slider */
    function doSliderOwl() {
        function getOption(e, optionType, optionValue) {
            /* number of item class */
            if ($(e).data(optionType)) {
                optionValue = $(e).data(optionType);
            }
            return optionValue;
        };
        var $selector = $('.em-slider');
        var $len = $selector.length;
        var $_rtl = false;
        if(EM.SETTING.RIGHT_TO_LEFT!=0){
            $_rtl = true;
        }
        if ($len) {
            for (var i = 0; i < $len; i++) {
                var stringRandom = randomString(100);
                $selector.eq(i).attr('id', 'owl_slider_' + stringRandom);
                var owl = $('#owl_slider_' + stringRandom); 
                var items = getOption(owl, 'emslider-items', 4); 
                var responsive =  false;            
                if(EM.SETTING.DISABLE_RESPONSIVE!=0){                    
                    var itemsDesktop = getOption(owl, 'emslider-desktop', items);
                    var itemsDesktopSmall = getOption(owl, 'emslider-desktop-small', items);
                    var itemsTablet = getOption(owl, 'emslider-tablet', items);
                    var itemsMobile = getOption(owl, 'emslider-mobile', items);
                    responsive =  {
                        0:{
                            items:itemsMobile,
                        },
                        480:{                            
                            items: itemsTablet, 
                        },
                        768:{                             
                            items: itemsDesktopSmall,                          
                        },
                        1024:{                            
                            items:itemsDesktop,
                        },
                        1280:{                            
                            items:items,
                        },
                    };
                }
                var navigation = getOption(owl, 'emslider-navigation', false);
                var pagination = getOption(owl, 'emslider-pagination', false);
                var paginationNumbers = getOption(owl, 'emslider-pagination-numbers', false);
                if (paginationNumbers == true) {
                    pagination = true;
                }                
                /* do owl carousel */
                owl.owlCarousel({
                    autoplay: false,
                    autoplayHoverPause: false,
                    autoplaySpeed: false,     
                    autoplayTimeout: 5000, 
                    
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    freeDrag: false, 
                    dragEndSpeed: false,             
                    
                    nav: navigation,
                    navText: ['Previous','Next'],
                    navContainer: false,
                    navSpeed: false,                    
                    navRewind: true,
                    
                    dots: pagination,
                    dotsEach: true,
                    dotsSpeed: false,
                    dotsContainer: false,
                    dotsData: paginationNumbers,
                    
                    items: items,
                    
                    responsive:responsive,
                    responsiveBaseElement: window,
                    responsiveClass: true,
                    responsiveRefreshRate: 200,
                    
                    rtl: $_rtl,
                    
                    autoWidth: false,
                    autoHeight: false,  
                    
                    lazyLoad: false,
                    lazyContent: false,
                    
                    animateOut: false,
                    animateIn: false,
                    
                    video: false,
                    videoHeight: false,
                    videoWidth: false,
                    
                    loop:false,
                    center: false,                    
                    margin: 30,
                    stagePadding: 0,
                    merge: false,
                    mergeFit: true,                                      
                    slideBy: 1,                    
                    smartSpeed: 250,
                    fluidSpeed: 250,
                    callbacks: true,
                    info: false,
                    startPosition: 0,                    
                    nestedItemSelector: false,
                    itemElement: 'div',
                    stageElement: 'div',                    
                    URLhashListener: false,
                });
            }
        }
    };

    /* safari hack: remove bold in h5, .h5 */
    function fixFontBoldSafari() {
        if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
            $('h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6').css('font-weight', 'normal');
        }
    };
    
    function fixClickASliderMobile(){
        if(isMobile){
            var $_aProduct = $('a.product-image');
            $_aProduct.each(function(i){
                var temp = false;
                var el = $(this);
                el.on('click', function(e) {
                    var $_this = $(this);
                    e.preventDefault();
                    if(temp==true){                        
                        var link = el.attr('href');
                        window.location = link;
                    }  
                    temp = true;              
                });  
            });
        } 
    };
    
    /** Ready Function **/
    $(document).ready(function() {              
        emLazyloadImg();
        fixFontBoldSafari();
        /* init ajax wishlist for wishlist link which has css class "link-wislist" */
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

        /* responsive */
        if (EM.SETTING.DISABLE_RESPONSIVE != 0) {
            isMobile && fixIPhoneAutoZoomWhenFocus();
            stickyElement();
        } else {
            freezedMenuNoResponsive();
        }
        emRetina();    
        fixPositionSidebar();
        if (EM.SETTING.DISABLE_RESPONSIVE != 0) {        
            if(EM.SETTING.DISABLE_COLLAPSE!=0){
                emCollapsibleMobile();     
            }
        }             
    }); 

    /* Load Function */
    $(window).bind('load', function() {
        if (EM.SETTING.DISABLE_RESPONSIVE != 0) {        
            if(EM.SETTING.DISABLE_COLLAPSE!=0){
                emCollapsible();   
            }
        }
        fixClickASliderMobile();
        doTitleTab();
    });
    
    $(window).bind('load tabs-load', function() {
        setTimeout(function() {
            doSliderOwl();
        }, 300);
    });
        
    $(window).resize($.throttle(300,function(){
        if (EM.SETTING.DISABLE_RESPONSIVE != 0) {
            if(EM.SETTING.DISABLE_COLLAPSE!=0){
                emCollapsible();   
            }
            stickyElement();
        }
        fixPositionSidebar();        	   
    })); 
    
    // Lazy Load Img
    $(window).bind('ajaxblock', function() {
        emLazyloadImg();
    });
    
})(jQuery);

function setEqualElement($_element,$_selector) {
    var $=jQuery;
    var $_e = $($_element);
    if($_e.length){
        var $_maxHeight= 0;
        var $listItems = $_e.find($_selector);            
        var lenLi = $listItems.length;
        $listItems.css('min-height', '');
        if(lenLi>1){
            for(var j=0;j<lenLi;j++){                
                $_maxHeight = Math.max($_maxHeight, $listItems.eq(j).outerHeight());
            }
        }
        $listItems.css('min-height', $_maxHeight + 'px');        
    }
};

function emLazyloadImg(){
    var $=jQuery;
    var $_img= $('img[data-original]');
    $_img.bind('inview', function(event, isVisible) {
        if (!isVisible) { return; }        
        var $_this = $(this);
        $_this.attr('src', $_this.attr('data-original')).removeAttr('data-original');
        if($_this.hasClass('img-banner-lazyload')){$_this.removeClass('img-banner-lazyload');}
        if($_this.hasClass('em-img-lazy')){$_this.removeClass('em-img-lazy');}
    });
};


function getWindowWidth() {
    var w = window;
    var d = document;
    var e = d.documentElement;
    var g = d.getElementsByTagName('body')[0];
    var x = w.innerWidth||e.clientWidth||g.clientWidth;
	return x;
};

var $_check_fixPositionSidebar = 0;    
function fixPositionSidebar() {
    var $ = jQuery;
    var wi = getWindowWidth();    
    try {
        if ((!isPhone) && (wi > 767)) {
            $_check_fixPositionSidebar = 0;
            var fixSideBar = function() {                
                var $_sidebar = $('.em-sidebar');
                var $_colmain = $('.em-col-main');                                
                if($_sidebar.length){
                    var scroll_top = $(window).scrollTop();
                    var winHeight = $(window).height();      
                    $_sidebar.each(function(){
                        var $_this = $(this);
                        var sidebarHeight = $_this.height();                            
                        var colMainHeight = $_colmain.height();
                        if(colMainHeight > sidebarHeight){                            
                            var colMainOffsetTop = $_colmain.offset().top;
                            var realColMainHeight = colMainHeight + colMainOffsetTop;
                            var realSideBarHeight = sidebarHeight + colMainOffsetTop;                                
                            if(sidebarHeight < winHeight){
                                $_this.stop().animate({            
                                   marginTop: scroll_top - colMainOffsetTop
                                });   
                            }
                            else if (scroll_top >= realSideBarHeight-winHeight && scroll_top + winHeight <= realColMainHeight) {                            
                                $_this.stop().animate({                        
                                    marginTop: scroll_top + winHeight - realSideBarHeight + 40
                                });
                            }
                            if(scroll_top <= colMainOffsetTop){                            
                                $_this.stop().animate({            
                                   marginTop: 0                    
                                });
                            }
                            if(scroll_top + winHeight >= realColMainHeight ){
                                $(this).stop().animate({            
                                   marginTop: colMainHeight - sidebarHeight                 
                                });   
                            }
                        }else{
                            $_this.stop().animate({            
                               marginTop: 0                    
                            });
                        }
                    });                    
                }
            };
            $(window).bind('scroll emAjaxLayer',function(){
                if($_check_fixPositionSidebar == 0){
                    fixSideBar();
                }
            });   
        }  
        else{
            $_check_fixPositionSidebar = 1;
            var $_sidebar = $('.em-sidebar');         
            $_sidebar.removeAttr('margin-top');
        }
    } catch (e) {
        console.log(e.message, e.name);
    }
};


function doTitleTab(){
    var $_currentTitle = jQuery('.emajaxtabs-current-title');
    if($_currentTitle.length){
        $_currentTitle.click(function(e){
            e.preventDefault();
            var $_this = jQuery(this);
            var $_listTitle = jQuery($_this.data('emajaxtabs-title'));
            if($_listTitle.length){
                if($_this.hasClass('active')){
                    $_this.removeClass('active');                
                    $_listTitle.addClass('hidden-xs').removeClass('active');
                }else{
                    $_this.addClass('active');
                    $_listTitle.removeClass('hidden-xs').addClass('active');
                }
            }          
        });
    }
};