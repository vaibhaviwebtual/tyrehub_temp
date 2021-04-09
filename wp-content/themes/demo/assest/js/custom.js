(function($) {
    "use strict";
    var $document = $(document),
        $window = $(window),
        plugins = {
            mainSlider: $('#mainSlider'),
            slideNav: $('#slide-nav'),
            categoryCarousel: $('.category-carousel'),
            servicesCarousel: $('.services-carousel'),
            servicesAltCarousel: $('.services-alt'),
            servicesBlockAlt: $('.services-block-alt'),
            textIconCarousel: $('.text-icon-carousel'),
            personCarousel: $('.person-carousel'),
            submenu: $('[data-submenu]'),
            googleMapFooter: $('#footer-map'),
            counterBlock: $('#counterBlock'),
            isotopeGallery: $('.gallery-isotope'),
            postGallery: $('.blog-isotope'),
            postCarousel: $('.post-carousel'),
            postMoreLink: $('.view-more-post'),
            animation: $('.animation')
        },
        $shiftMenu = $('#slidemenu, #pageContent, #mainSliderWrapper, .page-footer, .page-header .header-row, body, .darkout-menu'),
        $navbarToggle = $('.navbar-toggle'),
        $dropdown = $('.dropdown-submenu, .dropdown'),
        $fullHeight = $('#mainSlider, #mainSlider .img--holder'),
        $marginTop = $('body.fixedSlider #pageContent'),
        $marginBottom = $('body.fixedFooter #pageContent');
    $document.ready(function() {
        var windowWidth = window.innerWidth || $window.width();
        var windowH = $window.height();
        if (windowWidth < 992) {
            $fullHeight.height('');
        } else {
            var windowHeight = $window.height();
            var footerHeight = $('.page-footer').height();
            $fullHeight.height(windowHeight);
            $marginTop.css({
                'margin-top': windowHeight + 'px'
            });
            $marginBottom.css({
                'margin-bottom': footerHeight + 'px'
            })
        }
        $("div.vertical-tab-menu>div.list-group>a").on('click', function(e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.vertical-tab>div.vertical-tab-content").removeClass("active");
            $("div.vertical-tab>div.vertical-tab-content").eq(index).addClass("active");
        });
        $(".view-more-link").on('click', function(e) {
            var $this = $(this);
            var $target = $($this.attr('href'));
            if ($this.hasClass('opened')) {
                $this.removeClass('opened');
                $('.view-more-mobile', $target).stop(true, true).fadeOut();
            } else {
                $this.addClass('opened');
                $('.view-more-mobile', $target).stop(true, true).fadeIn();
            }
            e.preventDefault();
        })
        $('.modal').on('shown.bs.modal', function() {
            var $el = $('.animate', $(this));
            doAnimations($el);
        }).on('hidden.bs.modal', function() {
            var $el = $('.animate', $(this));
            $el.addClass('animation');
            $('html').css({
                'overflow-y': ''
            })
            $('.page-header, #mainSliderWrapper').css({
                'padding-right': ''
            });
        })
        if (plugins.mainSlider.length) {
            var $el = plugins.mainSlider;
            $el.on('init', function(e, slick) {
                var $firstAnimatingElements = $('div.slide:first-child').find('[data-animation]');
                doAnimations($firstAnimatingElements);
            });
            $el.on('beforeChange', function(e, slick, currentSlide, nextSlide) {
                var $currentSlide = $('div.slide[data-slick-index="' + nextSlide + '"]');
                var $animatingElements = $currentSlide.find('[data-animation]');
                setTimeout(function() {
                    $('div.slide').removeClass('slidein');
                }, 500);
                setTimeout(function() {
                    $currentSlide.addClass('slidein');
                }, 1000);
                doAnimations($animatingElements);
            });
            $el.slick({
                arrows: true,
                dots: false,
                autoplay: true,
                autoplaySpeed: 7000,
                fade: true,
                speed: 500,
                pauseOnHover: false,
                pauseOnDotsHover: true
            });
        }
        if (plugins.counterBlock.length) {
            plugins.counterBlock.waypoint(function() {
                $('.number > span.count', plugins.counterBlock).each(count);
                this.destroy();
            }, {
                triggerOnce: true,
                offset: '80%'
            });
        }
		
        if (plugins.slideNav.length) {
            var $slideNav = plugins.slideNav,
			toggler = '.navbar-toggle',
			$closeNav = $('.darkout-menu, .close-menu');
			$slideNav.after($('<div id="navbar-height-col"></div>'));
			var $heightCol = $('#navbar-height-col')
			$slideNav.on("click", toggler, function(e) {
				var $this = $(this);
				$heightCol.toggleClass('slide-active');
				$this.toggleClass('slide-active');
				$('#slidemenu').toggleClass('slide-active');
				$('header.page-header .navbar-toggle').css('z-index', '0');
			});
			$closeNav.on("click", function(e) { 
				$heightCol.toggleClass('slide-active');
				$('#slidemenu').toggleClass('slide-active');
				$('header.page-header .navbar-toggle').css('z-index', '1');
			});
			/*$('.container,.content-area').on('click', function (e) {
				$( "#slidemenu" ).removeClass( "slide-active" );
				$( "#navbar-height-col" ).removeClass( "slide-active" );
				$('header.page-header .navbar-toggle').css('z-index', '1');
			});*/
		}
		
        if (plugins.isotopeGallery.length) {
            plugins.isotopeGallery.find('a.hover').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
        if (plugins.isotopeGallery.length) {
            var $gallery = plugins.isotopeGallery;
            $gallery.imagesLoaded(function() {
                $gallery.isotope({
                    itemSelector: '.gallery-item',
                    masonry: {
                        columnWidth: '.gallery-item',
                        gutter: 30
                    }
                });
            });
            isotopeFilters($gallery);
        }
        if (plugins.postGallery.length) {
            console.log('dsad')
            var $postgallery = $('.blog-isotope');
            $postgallery.imagesLoaded(function() {
                $postgallery.isotope({
                    itemSelector: '.blog-post',
                    masonry: {
                        gutter: 30,
                        columnWidth: '.blog-post'
                    }
                });
            });
        }
        if (plugins.postMoreLink.length) {
            var $postMoreLink = plugins.postMoreLink,
                $postPreload = $('#postPreload'),
                $postLoader = $('#moreLoader');
            $postMoreLink.on('click', function() {
                var target = $(this).attr('data-load');
                $postLoader.addClass('visible');
                $(this).hide();
                $.ajax({
                    url: target,
                    success: function(data) {
                        setTimeout(function() {
                            $postPreload.append(data);
                            $postLoader.removeClass('visible');
                        }, 500);
                    }
                });
            })
        }
        if (plugins.textIconCarousel.length) {
            plugins.textIconCarousel.slick({
                mobileFirst: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                dots: true,
                arrows: false,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                }, {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
        }
        if (plugins.personCarousel.length) {
            plugins.personCarousel.slick({
                mobileFirst: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: false,
                dots: true,
                responsive: [{
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3
                    }
                }, {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
        }
        if (plugins.categoryCarousel.length) {
            plugins.categoryCarousel.slick({
                mobileFirst: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                arrows: false,
                dots: true,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3
                    }
                }, {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
        }
        if (plugins.postCarousel.length) {
            plugins.postCarousel.slick({
                mobileFirst: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                autoplay: false,
                arrows: true,
                dots: false
            });
        }
        if (plugins.animation.length) {
            onScrollInit(plugins.animation, windowWidth);
        }
        toggleNavbarMethod(windowWidth);
        mobileClickBanner(windowWidth);
        $window.resize(function() {
            var windowWidth = window.innerWidth || $window.width();
            startCarousel();
            if (windowWidth < 992) {
                $fullHeight.height('');
            }
            if (windowWidth > 767 && $navbarToggle.is(':hidden')) {
                $shiftMenu.removeClass('slide-active');
            }
            if (plugins.servicesBlockAlt.length) {
                $(".services-block-alt, .services-block-alt .title, .services-block-alt .text").each(function() {
                    $(this).css({
                        'height': ''
                    });
                })
            }
        });
        $(window).resize(debouncer(function(e) {
            var windowWidth = window.innerWidth || $window.width();
            if (windowWidth > 991) {
                $fullHeight.height($(window).height());
            }
            if (windowWidth > 768) {
                if (plugins.servicesCarousel.length) {
                    equalHeight(".text-icon-carousel > div", ".title", ".text");
                }
            }
            if (windowWidth > 480) {
                if (plugins.servicesBlockAlt.length) {
                    equalHeight(".services-block-alt", ".title", ".text");
                }
            }
            $dropdown.removeClass('opened');
            toggleNavbarMethod(windowWidth);
            mobileClickBanner(windowWidth);
        }))
    })
    $window.on('load', function() {
        var windowWidth = window.innerWidth || $window.width();
        startCarousel();
        $('#loader-wrapper').fadeOut(500);
        if (windowWidth > 768) {
            if (plugins.servicesCarousel.length) {
                equalHeight(".text-icon-carousel > div", ".title", ".text");
            }
        }
        if (windowWidth > 480) {
            if (plugins.servicesBlockAlt.length) {
                equalHeight(".services-block-alt", ".title", ".text");
            }
        }
        if (plugins.googleMapFooter.length) {
            createMap('footer-map', 14, 37.36274700000004, -122.03525300000001)
        }
    });
    function equalHeight(block) {
        var wrapWidth = $(block).parent().width(),
            blockWidth = $(block).width(),
            wrapDivide = Math.floor(wrapWidth / blockWidth),
            cellArr = $(block);
        for (var arg = 1; arg <= arguments.length; arg++) {
            for (var i = 0; i <= cellArr.length; i = i + wrapDivide) {
                var maxHeight = 0,
                    heightArr = [];
                for (var j = 0; j < wrapDivide; j++) {
                    heightArr.push($(cellArr[i + j]).find(arguments[arg]));
                    if (heightArr[j].outerHeight() > maxHeight) {
                        maxHeight = heightArr[j].outerHeight();
                    }
                }
                for (var counter = 0; counter < heightArr.length; counter++) {
                    $(cellArr[i + counter]).find(arguments[arg]).outerHeight(maxHeight);
                }
            }
        }
    }
    function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function() {
            var $this = $(this);
            var $animationDelay = $this.data('delay');
            var $animationType = 'animated ' + $this.data('animation');
            $this.css({
                'animation-delay': $animationDelay,
                '-webkit-animation-delay': $animationDelay
            });
            $this.addClass($animationType).one(animationEndEvents, function() {
                $this.removeClass($animationType);
            });
            if ($this.hasClass('animate')) {
                $this.removeClass('animation');
            }
        });
    }
    function debouncer(func, timeout) {
        var timeoutID, timeout = timeout || 500;
        return function() {
            var scope = this,
                args = arguments;
            clearTimeout(timeoutID);
            timeoutID = setTimeout(function() {
                func.apply(scope, Array.prototype.slice.call(args));
            }, timeout);
        }
    }
    function count(options) {
        var $this = $(this);
        options = $.extend({}, options || {}, $this.data('countToOptions') || {});
        $this.countTo(options);
    }
    function isotopeFilters(gallery) {
        var $gallery = $(gallery);
        if ($gallery.length) {
            var container = $gallery;
            var optionSets = $(".filters-by-category .option-set"),
                optionLinks = optionSets.find("a");
            optionLinks.on('click', function(e) {
                var thisLink = $(this);
                if (thisLink.hasClass("selected")) return false;
                var optionSet = thisLink.parents(".option-set");
                optionSet.find(".selected").removeClass("selected");
                thisLink.addClass("selected");
                var options = {},
                    key = optionSet.attr("data-option-key"),
                    value = thisLink.attr("data-option-value");
                value = value === "false" ? false : value;
                options[key] = value;
                if (key === "layoutMode" && typeof changeLayoutMode === "function") changeLayoutMode($this, options);
                else {
                    container.isotope(options);
                }
                return false
            })
        }
    }
    function slickMobile(carousel, breakpoint, slidesToShow, slidesToScroll) {
        var windowWidth = window.innerWidth || $window.width();
        if (windowWidth < (breakpoint + 1)) {
            carousel.slick({
                mobileFirst: true,
                slidesToShow: slidesToShow,
                slidesToScroll: slidesToScroll,
                infinite: true,
                autoplay: false,
                arrows: false,
                dots: true,
                responsive: [{
                    breakpoint: breakpoint,
                    settings: "unslick",
                }]
            });
        }
    }
    function startCarousel() {
        if (plugins.servicesAltCarousel.length) {
            slickMobile(plugins.servicesAltCarousel, 480, 1, 1);
        }
        if (plugins.servicesCarousel.length) {
            slickMobile(plugins.servicesCarousel, 767, 2, 2);
        }
    }
    function toggleNavbarMethod(windowWidth) {
        var $dropdownLink = $(".dropdown > a, .dropdown-submenu > a");
        var $dropdown = $(".dropdown, .dropdown-submenu");
        var $dropdownCaret = $(".dropdown > a > .ecaret, .dropdown-submenu > a > .ecaret");
        $dropdownLink.on('click.toggleNavbarMethod', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).attr('href');
            if (url) $(location).attr('href', url);
        });
        if (windowWidth < 768) {
            $dropdown.unbind('.toggleNavbarMethod');
            $dropdownCaret.unbind('.toggleNavbarMethod');
            $dropdownCaret.on('click.toggleNavbarMethod', function(e) {
                e.stopPropagation();
                e.preventDefault();
                var $li = $(this).parent().parent('li');
                if ($li.hasClass('opened')) {
                    $li.find('.dropdown-menu').first().stop(true, true).slideUp(0);
                    $li.removeClass('opened');
                } else {
                    $li.find('.dropdown-menu').first().stop(true, true).slideDown(0);
                    $li.addClass('opened');
                }
            })
        }
    }
    function onScrollInit(items, wW) {
        if (wW > 991) {
            if (!$('body').data('firstInit')) {
                items.each(function() {
                    var $element = $(this),
                        animationClass = $element.attr('data-animation'),
                        animationDelay = $element.attr('data-animation-delay');
                    $element.removeClass('no-animate');
                    $element.css({
                        '-webkit-animation-delay': animationDelay,
                        '-moz-animation-delay': animationDelay,
                        'animation-delay': animationDelay
                    });
                    var trigger = $element;
                    trigger.waypoint(function() {
                        $element.addClass('animated').addClass(animationClass);
                        if ($element.hasClass('hoveranimation')) {
                            $element.on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function() {
                                $(this).removeClass("animated").removeClass("animation").removeClass(animationClass);
                            });
                        }
                    }, {
                        triggerOnce: true,
                        offset: '90%'
                    });
                });
                $('body').data('firstInit', true);
            }
        } else {
            items.each(function() {
                var $element = $(this);
                $element.addClass('no-animate')
            })
        }
    }
    function getScrollbarWidth() {
        var outer = document.createElement("div");
        outer.style.visibility = "hidden";
        outer.style.width = "100px";
        outer.style.msOverflowStyle = "scrollbar";
        document.body.appendChild(outer);
        var widthNoScroll = outer.offsetWidth;
        outer.style.overflow = "scroll";
        var inner = document.createElement("div");
        inner.style.width = "100%";
        outer.appendChild(inner);
        var widthWithScroll = inner.offsetWidth;
        outer.parentNode.removeChild(outer);
        return widthNoScroll - widthWithScroll;
    }
    function mobileClickBanner(wW) {
        if (wW < 768) {
            $(".banner-under-slider").on('click', function(e) {
                var $this = $(this);
                var target = $this.find('.action .btn').attr('href');
                if (target) $(location).attr('href', target);
                e.preventDefault();
            })
        } else {
            $(".banner-under-slider").unbind('click');
        }
    }
    function createMap(id, mapZoom, lat, lng) {
        var mapOptions = {
            zoom: mapZoom,
            scrollwheel: false,
            center: new google.maps.LatLng(lat, lng),
            styles: [{
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e9e9e9"
                }, {
                    "lightness": 17
                }]
            }, {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 17
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 29
                }, {
                    "weight": 0.2
                }]
            }, {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 18
                }]
            }, {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }, {
                    "lightness": 16
                }]
            }, {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }, {
                    "lightness": 21
                }]
            }, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#dedede"
                }, {
                    "lightness": 21
                }]
            }, {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "visibility": "on"
                }, {
                    "color": "#ffffff"
                }, {
                    "lightness": 16
                }]
            }, {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "saturation": 36
                }, {
                    "color": "#333333"
                }, {
                    "lightness": 40
                }]
            }, {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f2f2f2"
                }, {
                    "lightness": 19
                }]
            }, {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#fefefe"
                }, {
                    "lightness": 20
                }]
            }, {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#fefefe"
                }, {
                    "lightness": 17
                }, {
                    "weight": 1.2
                }]
            }]
        };
        var mapElement = document.getElementById(id);
        var map = new google.maps.Map(mapElement, mapOptions);
        var image = 'images/map-marker.png';
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map,
            icon: image
        });
    }
})(jQuery);
jQuery(document).ready(function($) 
{
    $('.service-request-page .start-date').datetimepicker({
        format: "dd MM yyyy",
        autoclose: true,
        todayBtn: true,
        minView: 2,
        startView: 2
    });
    $('.service-request-page .end-date').datetimepicker({
        format: "dd MM yyyy",
        autoclose: true,
        todayBtn: true,
        minView: 2,
        startView: 2
    });
    $(document).on('click','.filter-installer-service',function(){
        var admin_url = $('.admin_url').text();
        var start_date = $('.start-date').val();
        var end_date = $('.end-date').val();
        var loader = "<span class='js-loader'><img src='https://dev.tyrehub.com/loading.gif' width='50'/>";
        
        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'filter_order_for_installer_services',
                    start_date: start_date,
                    end_date: end_date,
                },
                beforeSend: function() { 
                        $('.loading-container').html(loader);                       
                        $('.paid-services .completed-order-data').html('');
                    },
                success: function (data)
                {
                    $('.loading-container').html('');
                    $('.paid-services .completed-order-data').html(data);
                }
            });
    });
    $(document).on('click','.update-service-status123', function()
    {  
        var barcode_text = $('.barcode-text').val();
        if(barcode_text != ''){
            $('#after_scan').modal('show');
        }       
        else{
            $('.message').text('Plase enter OR code');
            $('.message').css('color','red');
            $('html, body').animate({
                scrollTop: $(".message").offset().top - 50
            }, 1000);
        }
    });

    $(document).on('click','#car_details_page_save', function()
    {          
       // $('#after_scan').modal('toggle');
        var admin_url = $('.admin_url').text();
        var prd_block = $('.update-service-status').parents('.prd-block');        
        
        var tyre_status = $(prd_block).find('.tyre-status').val();        
        var tyre_installer_id = $(prd_block).find('.tyre-installer').val();
        var barcode_text = $(prd_block).find('.barcode-text').val();
        var user_mobile = $(prd_block).find('.user-mobile-no').val();
        var order_id = $('.order-id').attr('data-id');

        var sub_modal = $('.select-sub-model').val();
        var modal = $('.select-model').val();
        var make = $('.select-car-cmp').val();
         var car_number = $('#car_number').val();
         var user_id = $('#user_id').val();

        var odo_meter = $('#odo_meter').val();
        var tyre_info_id = $('#tyre_info_id').val();
        var serial_number = $('.serial_number').val();
        var tyre_count = $('#tyre_count').val();
        var product_id = $('#product_id').val();
     
        
    var errors = false;
    //$(".errors").remove();
  //refresh error messages on submit
if (make == "" || make ==null){
  jQuery(".select-car-cmp").addClass('errormsg');
       errors= true;
  }else{
    jQuery(".select-car-cmp").removeClass('errormsg');
       errors= false;
  }
//validate name field has entry
 if (modal == "" || modal ==null){
 jQuery(".select-model").addClass('errormsg');
     errors= true;
}else{
  jQuery(".select-model").removeClass('errormsg');
       errors= false;
}
if (sub_modal == "" || sub_modal ==null){
 jQuery(".select-sub-model").addClass('errormsg');
     errors= true;
}else{
  jQuery(".select-sub-model").removeClass('errormsg');
       errors= false;
}
if (car_number == "" || car_number ==null){
 jQuery("#car_number").addClass('errormsg');
     errors= true;
}else{
  jQuery("#car_number").removeClass('errormsg');
       errors= false;
}
if (odo_meter == "" || odo_meter ==null){
 jQuery("#odo_meter").addClass('errormsg');
     errors= true;
}else{
  jQuery("#odo_meter").removeClass('errormsg');
       errors= false;
}
if (barcode_text == "" || barcode_text ==null){
 jQuery(".barcode-text").addClass('errormsg');
     errors= true;
}else{
  jQuery(".barcode-text").removeClass('errormsg');
       errors= false;
}
        var serial_number = [];
         var mainArrayCheck=0;
        var arrayCheck=0;
        $('input[name="serial_number[]"]').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
            serial_number.push($(this).val());
            mainArrayCheck = mainArrayCheck + 1;
        });
        var serialNo='';
       
        $('input[name="serial_number[]"]').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
            if (serial_number[index] == "" || serial_number[index] ==null){
             jQuery("#serial_number_"+index).addClass('errormsg');
                 errors= true;
                  serialNo='';
            }else{
                if(serial_number[index].length==4){
                    jQuery("#serial_number_"+index).removeClass('errormsg');
                   errors= false;
                   serialNo='yes'; 
                   arrayCheck = arrayCheck + 1; 
                }else{
                    jQuery("#serial_number_"+index).addClass('errormsg');
                    errors= true;
                     serialNo='';
                }
              
            }
        });
if(mainArrayCheck != arrayCheck){
    var serialNo ='';
}
if((serialNo == "" || serialNo ==null) || (sub_modal == "" || sub_modal ==null) || (barcode_text == "" || barcode_text ==null) || (odo_meter == "" || odo_meter ==null)|| (car_number == "" || car_number ==null)|| (make == "" || make ==null) || (modal == "" || modal ==null)) {
  errors=true;
}else{
   errors=false;
}
if(errors == true){
    return false;
}else{
  //jQuery('#after_scan').modal('show');
  //$('#cover-spin').show(0);
        var serial_number = [];
        $('input[name="serial_number[]"]').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
            serial_number.push($(this).val());
        });

        var tyre_info_id = [];
        $('input[name="tyre_info_id[]"]').each(function( index ) {
        //console.log( index + ": " + $( this ).text() );
            tyre_info_id.push($(this).val());
        });
        $.ajax({
                  type: "POST", 
                  url: admin_url,
                  data: {
                      action: 'update_installer_status',
                      tyre_installer_id: tyre_installer_id,
                      tyre_status: 'completed',
                       barcode_text: barcode_text,
                       user_mobile: user_mobile,
                       sub_modal : sub_modal,
                        model : modal,
                        make : make,
                        car_number : car_number,
                        user_id : user_id,
                        order_id : order_id,
                        product_id: product_id,
                        odo_meter : odo_meter,
                        serial_number : serial_number,
                        tyre_info_id : tyre_info_id
                  },
                  success: function (data)
                  {
                     data = data.replace(/(\r\n|\n|\r)/gm, "");
                      //  console.log(data);
                       if(data == 'true')
                       {
                        $.ajax({
                              type: "POST", 
                              url: admin_url,
                              data: {
                                  action: 'update_service_status',
                                  order_id: order_id,
                              },
                              success: function (data)
                              {        
                              },
                          }); 
                           
                            $.ajax({
                                      type: "POST", 
                                      url: admin_url,
                                      data: {
                                          action: 'update_order_status',
                                          order_id: order_id,
                                      },
                                      success: function (data)
                                      {  
                                      $('#cover-spin').hide(0);
                                        window.location.href = data;    
                                      },
                                  });
                            $('.message').text('');
                            //$('#installer_modal').modal('show');
                       }
                       else{
                        $('.message').text('*QR code wrong, Please scan proper or enter right code');
                        $('html, body').animate({
                                scrollTop: $(".message").offset().top - 50
                            }, 1000);
                       }        
                  },
              });
}



        
    });
    $(document).on("click","#update-service-status",function(){ //confirm-voucher-update
    //$("#after_scan").modal("toggle");
    var e=$(".admin_url").text();
    var t=$(".update-service-status").parents(".prd-block");
    var voucher_id = $(t).find(".tyre-installer").val();
     r=$(t).find(".barcode-text").val();
     o=$(".order-id").attr("data-id");
     var errors = false;
            var admin_url = $('.admin_url').text();
        var prd_block = $('.update-service-status').parents('.prd-block');        
        
        var tyre_status = $(prd_block).find('.tyre-status').val();        
        var tyre_installer_id = $(prd_block).find('.tyre-installer').val();
        var barcode_text = $(prd_block).find('.barcode-text').val();
        var user_mobile = $(prd_block).find('.user-mobile-no').val();
        var order_id = $('.order-id').attr('data-id');

        var sub_modal = $('.select-sub-model').val();
        var modal = $('.select-model').val();
        var make = $('.select-car-cmp').val();
         var car_number = $('#car_number').val();
         var user_id = $('#user_id').val();

        var odo_meter = $('#odo_meter').val();
        var product_id = $('#product_id').val();
        var services_id = $('#services_id').val();

     
        
    var errors = false;
    //$(".errors").remove();
  //refresh error messages on submit
if (make == "" || make ==null && services_id!=5){
  jQuery(".select-car-cmp").addClass('errormsg');
       errors= true;
  }else{
    jQuery(".select-car-cmp").removeClass('errormsg');
       errors= false;
  }
//validate name field has entry
 if (modal == "" || modal ==null && services_id!=5){
 jQuery(".select-model").addClass('errormsg');
     errors= true;
}else{
  jQuery(".select-model").removeClass('errormsg');
       errors= false;
}
if (sub_modal == "" || sub_modal ==null && services_id!=5){
 jQuery(".select-sub-model").addClass('errormsg');
     errors= true;
}else{
  jQuery(".select-sub-model").removeClass('errormsg');
       errors= false;
}
if (car_number == "" || car_number ==null && services_id!=5){
 jQuery("#car_number").addClass('errormsg');
     errors= true;
}else{
  jQuery("#car_number").removeClass('errormsg');
       errors= false;
}
if (odo_meter == "" || odo_meter ==null && services_id!=5){
 jQuery("#odo_meter").addClass('errormsg');
     errors= true;
}else{
  jQuery("#odo_meter").removeClass('errormsg');
       errors= false;
}
 if (r == "" || r ==null){
 $(".barcode-text").addClass('errormsg');
     errors= true;
}else{
  $(".barcode-text").removeClass('errormsg');
       errors= false;
}


    if((sub_modal == "" || sub_modal ==null && services_id!=5) || (r == "" || r ==null) || (odo_meter == "" || odo_meter ==null && services_id!=5)|| (car_number == "" || car_number ==null && services_id!=5)|| (make == "" || make ==null && services_id!=5) || (modal == "" || modal ==null && services_id!=5)) {
      errors=true;
    }else{
       errors=false;
    }
    if(errors == true){
    return false;
    }else{
        $('#cover-spin').show(0);
     $.ajax({    
                type: "POST", 
                url: e,
                data: {
                    action: 'update_voucher_status',
                    voucher_id:voucher_id,
                    tyre_status:"completed",
                    barcode_text:r,
                    user_mobile: user_mobile,
                    sub_modal : sub_modal,
                    model : modal,
                    make : make,
                    car_number : car_number,
                    user_id : user_id,
                    order_id : order_id,
                    product_id : product_id,
                    odo_meter : odo_meter
                },
                success: function (t)
                {
                   
                   data = t.replace(/(\r\n|\n|\r)/gm, "");
                   
                   if(data==1)
                   {
                        $.ajax({
                            type:"POST",
                            url:e,
                            data:{
                                action:"update_order_status",
                                order_id:o
                            },
                            success: function (t)
                            {
                                //$("#installer_modal").modal("show");
                                $('#cover-spin').hide(0);
                                        window.location.href = data; 
                            }
                        });
                    }
                    else{
                        $('.message').text('*QR code wrong, Please scan proper or enter right code');
                        $('html, body').animate({
                                scrollTop: $(".message").offset().top - 50
                            }, 1000);
                        $('#cover-spin').hide(0);
                    }
                }
            });
        }
});
    $('.scan-barcode').on('click', function () {
       // alert('hii');
       var prd_block = $(this).parents('.prd-block');
        $(prd_block).find('.imageFile').trigger('click');
    });
    $('.qrcode').on('change', function () {
        var prd_block = $(this).parents('.prd-block');
        $(prd_block).find('.decodeBtn').trigger('click');
    });
    $('.imageFile').on('change', function () {
        var prd_block = $(this).parents('.prd-block');
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(prd_block).find('.qrcode').attr('src', e.target.result);
                $(prd_block).find('.decodeBtn').trigger('click');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    $(".decodeBtn").click(function () {
         var prd_block = $(this).parents('.prd-block');
                qrcode.decode($(prd_block).find(".qrcode").attr("src"));               
                 qrcode.callback = logData;
        function logData(data, type) {
            var error = "error";
            if(data.indexOf(error) != -1){
                $('.message').text('Image not proper, Plase try again!');
                $(prd_block).find('.scan-barcode').css('background','red');
            }
            else{
                $('.message').text('');
                $(prd_block).find('input.barcode-text').val(data);
                $(prd_block).find('.scan-barcode').css('background','#288345');
                
                
                $(".update-service-status ").trigger('click');
            }
            
        }
    });
    
    $(document).on('click','.admin.update-service-status', function()
    {
        var modal = $(this).parents('.modal');
        var service_id = $(modal).attr('data-service-id');
        var barcode_text = $(modal).find('input.service-barcode').val();
        var admin_url = $('.admin_url').text();
        var order_id = $(modal).attr('data-order-id');
        $.ajax({
              type: "POST", 
              url: admin_url,
              data: {
                  action: 'admin_service_status_pending',
                  service_id: service_id,
                  barcode_text: barcode_text,
                  order_id: order_id,
              },
              success: function (data)
              {   
                  data = data.replace(/(\r\n|\n|\r)/gm, "");
                       
                       if(data == 'yes')
                       {
                            location.reload();
                       }
                       else{
                            $(modal).find('.message').text('Barcode you entered is wrong!');
                            $(modal).find('.message').css('color','red');
                       }
                
              },
          });
    });
    $(document).on('click','.toggle-menu .fa-bars', function()
    {
        $('.woocommerce-MyAccount-navigation').css('display','block');
        $(this).removeClass('fa-bars');
        $(this).addClass('fa-window-close');
    });
    $(document).on('click','.toggle-menu .fa-window-close', function()
    {
        $('.woocommerce-MyAccount-navigation').css('display','none');
        $(this).removeClass('fa-window-close');
        $(this).addClass('fa-bars');
    });
     setTimeout(function(){
        var pincode = $('.home-delivery-pincode').text();
        var count = $('.home-delivery-pincode').attr('data-count');
       
        if(pincode == '' && count == 0){
            $('#current_pincode').modal('show');
            
            //$('.home-delivery-pincode').attr('data-count','1'); 
        }
       },3000);
    $(document).on('click','#current_pincode .confirm-location',function() 
    {       
        var thisnew = $(this);
        var pincode = $('.current-pincode').val();
        var fullname = $('.current-fullname').val();
        var mobile = $('.current-mobile').val();
        var erorrflag=0;
        if(pincode==''){
          $('.current-pincode').addClass('error');
          var erorrflag = 1; 
          $( "#errorlb" ).remove();
        $( ".current-pincode" ).after('<span style="color:red;" id="errorlb">Please enter pincode!</span>'); 
        }
        
        /*if(fullname==''){
          $('.current-fullname').addClass('error');
           var erorrflag = 1;  
        }*/
        if(mobile==''){
          $('.current-mobile').addClass('error');         
           var erorrflag = 1; 
        }
         if (!validatePhone(mobile)) {
        $('.current-mobile').addClass('error');         
        var erorrflag = 1;
        $( "#errorlb" ).remove();
        $( ".current-mobile" ).after('<span style="color:red;" id="errorlb">Invalid Mobile Number</span>');
        }
        
        if(erorrflag==1){
            return false;
        }else{
        $('#detailsFrm input').removeClass('error'); 
         
        $('#errorlb').fadeOut();
        }
        console.log(pincode);
        var admin_url = $('.admin_url').text();
        $('.confirm-location').fadeOut();
        $('#loding').fadeIn();
        //$(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("");
        $.ajax({
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'save_current_pincode',
                    pincode : pincode,
                    fullname : fullname,
                    mobile : mobile
                },
                success: function (data)
                {
                    $('.confirm-location').fadeIn();
                     $('#loding').fadeOut();
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    if(data == "0"){
                        $(thisnew).parents('.modal-content').find('.message').html("<span style='color:red'><strong>Sorry we don't deliver tyres to your location!</strong></span></br><div style='font-size: 15px;'>We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on</div><p class='phone'>1-800-233-5551</p>Click ok to continue browsing.<button class='close btn btn-invert' data-dismiss='modal'><span>OK</span></button>");
                    }else{
                         $('.confirm-location').fadeIn();
                         $('#loding').fadeOut();
                         $('.modal-backdrop').remove();
                        $(thisnew).parents('.modal-content').find('.message').html('');
                        $('.header-current-location span').text(data);
                        $('#current_pincode').modal('hide');
                        $('#myInput').val(pincode);
                        $('.searchbtn').trigger('click');
                    }  
                                      
                },
            });
    });
 function validatePhone(phoneText) {
    var filter = /^[0-9-+]+$/;
    if (filter.test(phoneText)) {
    return true;
    }
    else {
    return false;
    }
}
    $(document).on('click','.city-section .cityname',function() 
    {   
        $('.city-section div').removeClass('active');
        $(this).addClass('active');
        $("#detailsFrm").fadeIn("slow");
        var modal_content = $(this).parents('.modal-content');
        var pincode = $(this).attr('data-pincode');
           if(pincode==''){
            
            $('.other-city-msg').fadeIn('slow');
            $('#detailsFrm').fadeOut('slow');
            $('#confirmBtn').fadeOut('slow');
            
           }else{
            $('#detailsFrm').fadeIn('slow');
            $('#confirmBtn').fadeIn('slow');
            $('.other-city-msg').fadeOut('slow');
           }
        $(modal_content).find('input.current-pincode').val(pincode);
    });
    $(document).keypress(function(e) {
      if ($("#current_pincode").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#current_pincode button.confirm-location').trigger('click');
      }
    });

    
$(document).on('click','#offline_car_details_save',function()
{
    // $('#after_scan').modal('toggle');

        var admin_url = $('.admin_url').text();        
        var order_id = $('#order_id').val();
        var product_id = $('#product_id').val();
        var sub_modal = $('.select-sub-model').val();
        var modal = $('.select-model').val();
        var make = $('.select-car-cmp').val();
         var car_number = $('#car_number').val();
         var user_id = $('#user_id').val();

        var odo_meter = $('#odo_meter').val();
        var tyre_info_id = $('#tyre_info_id').val();
        var serial_number = $('.serial_number').val();
         var franchise_id = $('#franchise_id').val();
         var product_id = $('#product_id').val(); 
         var services_id = $('#carDetails #services_id').val();


var errors = false;
if (make == "" || make ==null){
  $(".select-car-cmp").addClass('errormsg');
       errors= true;
  }else{
    $(".select-car-cmp").removeClass('errormsg');
       errors= false;
  }
//validate name field has entry
 if (modal == "" || modal ==null){
 $(".select-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-model").removeClass('errormsg');
       errors= false;
}
if (sub_modal == "" || sub_modal ==null){
 $(".select-sub-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-sub-model").removeClass('errormsg');
       errors= false;
}
if (car_number == "" || car_number ==null){
 $("#car_number").addClass('errormsg');
     errors= true;
}else{
  $("#car_number").removeClass('errormsg');
       errors= false;
}
if (odo_meter == "" || odo_meter ==null){
 $("#odo_meter").addClass('errormsg');
     errors= true;
}else{
  $("#odo_meter").removeClass('errormsg');
       errors= false;
}

        if(services_id<=0){
            var serial_number = [];
            var mainArrayCheck=0;
            var arrayCheck=0;
            $('input[name="serial_number[]"]').each(function( index ) {
            //console.log( index + ": " + $( this ).text() );
                serial_number.push($(this).val());
                mainArrayCheck = mainArrayCheck + 1;
            });
            var serialNo='';
            $('input[name="serial_number[]"]').each(function( index ) {
            //console.log( index + ": " + $( this ).text() );
                if (serial_number[index] == "" || serial_number[index] ==null){
                 jQuery("#serial_number_"+index).addClass('errormsg');
                     errors= true;
                     serialNo='';

                }else{
                    if(serial_number[index].length==4){
                        jQuery("#serial_number_"+index).removeClass('errormsg');
                       errors= false;
                       serialNo='yes';
                       arrayCheck = arrayCheck + 1;
                    }else{
                        jQuery("#serial_number_"+index).addClass('errormsg');
                        errors= true;
                        serialNo='';
                    }
                  
                }
            });

        

        }else{
         var serialNo ='yes';
        }
        if(mainArrayCheck != arrayCheck){
            var serialNo ='';
        }

if((serialNo == "") || (sub_modal == "" || sub_modal ==null) || (odo_meter == "" || odo_meter ==null)|| (car_number == "" || car_number ==null)|| (make == "" || make ==null) || (modal == "" || modal ==null)) {
  errors=true;
}else{
   errors=false;
}

if(errors == true){
    return false;
}else{
 $('#cover-spin').show(0);
        if(services_id){
            var serial_number = [];
            $('input[name="serial_number[]"]').each(function( index ) {
                serial_number.push($(this).val());
            });

            var tyre_info_id = [];
            $('input[name="tyre_info_id[]"]').each(function( index ) {
                tyre_info_id.push($(this).val());
            });
        }
        $.ajax({
                  type: "POST", 
                  url: admin_url,
                  data: {
                      action: 'offline_car_details_save',
                        sub_modal : sub_modal,
                        model : modal,
                        make : make,
                        car_number : car_number,
                        user_id : user_id,
                        order_id : order_id,
                        product_id : product_id,
                        services_id : services_id,
                        odo_meter : odo_meter,
                        serial_number : serial_number,
                        tyre_info_id : tyre_info_id,
                        franchise_id : franchise_id,
                        order_id : order_id
                  },
                  success: function (data)
                  { 
                    location.reload();
                    $('#cover-spin').hide(0);
                  },
              });
}
//validate form
});

$(document).on('click','.single_add_to_cart_button',function(e){
	 e.preventDefault();
    $('#cover-spin').show(0);
	var product_id = $('input[name="product_id"]').val();
	var variation_id = $('input[name="variation_id"]').val();
    var quantity = $('.product-qty').val();
    var vehicle_type = $('input[name="vehicle_type"]').val();
	var two_wheel = $('input[name="two_wheel"]').val();
	var admin_url = $('.admin_url').text();
	    $.ajax({
                 type: "POST",
                 url: admin_url,
                 data: {
                     action: "organic_product_details_add_to_cart",
                     product_id: product_id,
                     variation_id:variation_id,
                     quantity:quantity,
                     vehicle_type:vehicle_type,
					 two_wheel:two_wheel
                 },
                 success: function(response) {
                  var jsonData = JSON.parse(response);
                  if(jsonData.status=='notinsert' || jsonData.status=='graterpro'){
                     $('#pro_msg').html(jsonData.msg);
                    if(jsonData.status=='graterpro'){
                      $('#cartlink').show();
                    }else{
                      $('#cartlink').hide();
                    }
                    $('#duplicate_product').modal('show');
                  }else{
                     window.location=jsonData.redirect_url;
                  }
                 
                    
                     $('#cover-spin').hide(0);
                 }
             })
});

$(document).on('click','.organic_order_add_to_cart',function(e){
    e.preventDefault();
    //alert('hello');
    $('#cover-spin').show(0);
    //$thisbutton = jQuery(this);
    var product_id=$(this).attr('data-product_id');
    var variation_id=$(this).attr('data-variation_id');
    var quantity=$(this).attr('data-quantity');
    var vehicle_type=$(this).attr('data-vehicle_type');
   
    var admin_url = $('.admin_url').text();
        
        //alert('asdsadsdas');
         $.ajax({
                 type: "POST",
                 url: admin_url,
                 data: {
                     action: "organic_product_add_to_cart",
                     product_id: product_id,
                     variation_id:variation_id,
                     quantity:quantity,
                     vehicle_type:vehicle_type
                 },
                 success: function(response) {
                  var jsonData = JSON.parse(response);
                  if(jsonData.status=='notinsert' || jsonData.status=='graterpro'){
                     $('#pro_msg').html(jsonData.msg);
                    if(jsonData.status=='graterpro'){
                      $('#cartlink').show();
                    }else{
                      $('#cartlink').hide();
                    }
                    $('#duplicate_product').modal('show');
                  }else{
                     window.location=jsonData.redirect_url;
                  }
                 
                    
                     $('#cover-spin').hide(0);
                 }
             })
    });
});

