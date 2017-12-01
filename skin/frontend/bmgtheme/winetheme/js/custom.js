function sliderLayoutChange(selector){
	var mobilebreakpoint = 767;
	var sliderActivated = selector.hasClass("bx-deactivated") ? true : false;
	var slider_Config = {
		mode: 'fade',
		Controls: true
	};
	if(jQuery(window).width()< mobilebreakpoint){
		if(sliderActivated){
			selector.toggleClass("bx-deactivated");
			slider = selector.bxSlider(slider_Config);
		}
	}else if(jQuery(window).width()> mobilebreakpoint){
		if(sliderActivated == false){
			selector.toggleClass("bx-deactivated");
			slider.destroySlider();
		}
	}
}

jQuery(window).on('resize', function (e) {
    var win = jQuery(this);
    if (win.width() < 992) {
        jQuery('div.product-content').insertBefore(jQuery('div.sidebar-content'));
    }
    if (win.width() >= 991) {
        jQuery('div.sidebar-content').insertBefore(jQuery('div.product-content'));
    }
});

jQuery(document).ready(function ($) {
    if (768 < jQuery(window).width() < 991) {
        jQuery('.tab-row').removeClass('row');
    }
    var win = jQuery(this);
    if (win.width() < 992) {
        jQuery('div.product-content').insertBefore(jQuery('div.sidebar-content'));
    }
    if (win.width() >= 991) {
        jQuery('div.sidebar-content').insertBefore(jQuery('div.product-content'));
    }

    // to-top-btn
    if (jQuery('#back-to-top').length) {
        var scrollTrigger = 100, // px
            backToTop = function () {
                var scrollTop = jQuery(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    jQuery('#back-to-top').addClass('show');
                } else {
                    jQuery('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        jQuery(window).on('scroll', function () {
            backToTop();
        });
        jQuery('#back-to-top').on('click', function (e) {
            e.preventDefault();
            jQuery('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }

    jQuery('.styled-checkbox').click(function(){
        if(typeof jQuery(this).attr('data-link') != 'undefined')
            location.href = jQuery(this).attr('data-link');
    });
});
