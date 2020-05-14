

var searchOpen = function () {

	return {
		//main function to initiate the module
		init: function () {

			$('.search-box').on('click', function (e) {
				e.stopPropagation();
			});

			$(document).on('click', '.typed-search-box-shown', function (e) {
                $(this).removeClass("typed-search-box-shown");
                $('.typed-search-box').addClass('d-none');
			});
		}
	};
}();


$(function () {
    $('#category-menu-icon, #category-sidebar').on('mouseover', function (event) {
        $('#hover-category-menu').show();
        $('#category-menu-icon').addClass('active');
    }).on('mouseout', function (event) {
        $('#hover-category-menu').hide();
        $('#category-menu-icon').removeClass('active');
    });

    $('.nav-search-box a').on('click', function(e){
        e.preventDefault();
        $('.search-box').addClass('show');
        $('.search-box input[type="text"]').focus();
    });
    $('.search-box-back button').on('click', function(){
        $('.search-box').removeClass('show');
    });

    $('.all-category-menu a').bind('click', function(e) {
        e.preventDefault(); // prevent hard jump, the default behavior

        var target = $(this).attr("href"); // Set the target as variable

        $('html, body').stop().animate({
                scrollTop: $(target).offset().top - 120
        }, 600, function() {
                // location.hash = target; //attach the hash (#jumptarget) to the pageurl
        });

        return false;
    });




});


// Bootstrap selected
$('.sortSelect').each(function(index, element) {
    $('.sortSelect').select2({
        theme: "default sortSelectCustom"
    });
});
function morebrands(em){
    if($(em).hasClass('on')){
        $(em).removeClass('on');
        $('#brands-collapse-box').removeClass('full');
        $(em).children('i').addClass('fa-plus').removeClass('fa-minus');
        $(em).children('span').html('More');
    }else {
        $(em).addClass('on');
        $('#brands-collapse-box').addClass('full');
        $(em).children('i').removeClass('fa-plus').addClass('fa-minus');
        $(em).children('span').html('Less');
    }
}


$(document).ready(function() {
    searchOpen.init();
    

    $(".xzoom-link").on("click",function(e){
        e.preventDefault();
        var img_url = $(this).attr("href");
        $(".xzoom").attr("src",img_url);
    });

    $('.tagsInput').tagsinput('items');

	// $('.summernote').summernote({
    //     height: 500,
    //     popover: {
    //         image: [],
    //         link: [],
    //         air: []
    //     }
    // });

	$('.editor').each(function(el){

        var $this = $(this);
        var buttons = $this.data('buttons');
        buttons = !buttons ? "bold,underline,italic,hr,|,ul,ol,|,align,paragraph,|,image,table,link,undo,redo" : buttons;

		var editor = new Jodit(this, {
            "uploader": {
                "insertImageAsBase64URI": true
            },
            "toolbarAdaptive": false,
            "defaultMode": "1",
            "toolbarSticky": false,
            "showXPathInStatusbar": false,
            "buttons": buttons,
			"language": 'en',
			"i18n": {
				"en": {
				   'Type something': 'Начните что-либо вводить',
				   'Words: %d': 'Слова: %d',
				   "Chars: %d": 'Cимволы: %d',
				}
			}
			
        });
    });


	
    $(".all-products").on("click",function(){
        $(".navbar-menu").toggle();
    });

	

    $(".menu-logo-icon").on("click",function(){
        $(".mobile-navbar-menu").animate({'left': "0px"});
    });
    $(".menu-close-icon").on("click",function(){
        $(".mobile-navbar-menu").animate({'left': "-280px"});
    });
	

    $(".mobile-info-btn").on("click",function(){
        $(".mobile-info").toggle();
    });

    $(".mobile-search-button").on("click",function(){
        $(".mobile-search-box").toggle();
        $(".search-black-layer").toggle();
    });
    $(".search-black-layer").on("click",function(){
        $(".mobile-search-box").hide();
        $(this).hide();
    });

    $("body").on('click', function(e){
        var navbar = $(".navbar-menu");
        var mobile_navbar = $(".mobile-navbar-menu");
        if (!navbar.is(e.target) && navbar.has(e.target).length === 0 && !$(e.target).hasClass("all-products")) {
            $(".navbar-menu").hide();
        }
        if (!mobile_navbar.is(e.target) && mobile_navbar.has(e.target).length === 0 && !$(e.target).hasClass("menu-logo-icon") && !$(e.target).hasClass("menu-icon-bars")) {
        	$(".mobile-navbar-menu").animate({'left': "-280px"});
        }
    });

    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
    if ($('.slick-carousel').length > 0) {
        $('.slick-carousel').each(function() {
            var $this = $(this);

            var slidesRtl = false;

            var slidesPerViewXs = $this.data('slick-xs-items');
            var slidesPerViewSm = $this.data('slick-sm-items');
            var slidesPerViewMd = $this.data('slick-md-items');
            var slidesPerViewLg = $this.data('slick-lg-items');
            var slidesPerViewXl = $this.data('slick-xl-items');
            var slidesPerView = $this.data('slick-items');

            var slidesCenterMode = $this.data('slick-center');
            var slidesArrows = $this.data('slick-arrows');
            var slidesDots = $this.data('slick-dots');
            var slidesRows = $this.data('slick-rows');
            var slidesAutoplay = $this.data('slick-autoplay');

            slidesPerViewXs = !slidesPerViewXs ? slidesPerView : slidesPerViewXs;
            slidesPerViewSm = !slidesPerViewSm ? slidesPerView : slidesPerViewSm;
            slidesPerViewMd = !slidesPerViewMd ? slidesPerView : slidesPerViewMd;
            slidesPerViewLg = !slidesPerViewLg ? slidesPerView : slidesPerViewLg;
            slidesPerViewXl = !slidesPerViewXl ? slidesPerView : slidesPerViewXl;
            slidesPerView = !slidesPerView ? 1 : slidesPerView;
            slidesCenterMode = !slidesCenterMode ? false : slidesCenterMode;
            slidesArrows = !slidesArrows ? true : slidesArrows;
            slidesDots = !slidesDots ? false : slidesDots;
            slidesRows = !slidesRows ? 1 : slidesRows;
            slidesAutoplay = !slidesAutoplay ? false : slidesAutoplay;

            if ($('html').attr('dir') === 'rtl') {
                slidesRtl = true
            }

            $this.slick({
                slidesToShow: slidesPerView,
                autoplay: slidesAutoplay,
                dots: slidesDots,
                arrows: slidesArrows,
                infinite: true,
                rtl: slidesRtl,
                rows: slidesRows,
                centerPadding: '0px',
                centerMode: slidesCenterMode,
                speed: 300,
                prevArrow: '<button type="button" class="slick-prev"><span class="prev-icon"></span></button>',
                nextArrow: '<button type="button" class="slick-next"><span class="next-icon"></span></button>',
                responsive: [
                    {
                        breakpoint: 1500,
                        settings: {
                            slidesToShow: slidesPerViewXl,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: slidesPerViewLg,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: slidesPerViewMd,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: slidesPerViewSm,
                            dots: true,
                            arrows: false,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: slidesPerViewXs,
                            dots: true,
                            arrows: false,
                        }
                    }
                ]
            });
        });
    }


    // color select select2
	$('.color-var-select').select2({
        templateResult: colorCodeSelect,
        templateSelection: colorCodeSelect,
        escapeMarkup: function(m) { return m; }
    });
    function colorCodeSelect(state) {
        var colorCode = $(state.element).val();
        if (!colorCode) return state.text;
        return  "<span class='color-preview' style='background-color:"+colorCode+";'></span>" + state.text;
    }

});
$(window).on('load', function() {
    
});

$(window).scroll(function() {
    var scrollDistance = $(window).scrollTop();
    $('.sub-category-menu.active').each(function(i) {
            if (($(this).position().top + 120) <= scrollDistance) {
                $('.all-category-menu li.active').removeClass('active');
                $('.all-category-menu li').eq(i).addClass('active');
            }
    });		

    var b = $(window).scrollTop();
    
    if( b > 120 ){		
        $(".logo-bar-area").addClass("sm-fixed-top");	
    } else {
        $(".logo-bar-area").removeClass("sm-fixed-top");
    }
		
}).scroll();

$(document).ajaxComplete(function(){
    $('.selectpicker').each(function(index, element) {
        $('.selectpicker').select2({

        });
    });
});



var StickyElement = function(node){
    var doc = $(document), 
    fixed = false,
    anchor = node.find('.sticky-anchor'),
    content = node.find('.sticky-content'); 
    var onScroll = function(e){
        var docTop = doc.scrollTop(),
        anchorTop = anchor.offset().top;
        if(docTop > anchorTop){
            if(!fixed){
                anchor.height(content.outerHeight());
                content.addClass('fixed');        
                fixed = true;
            }
		} else {
			if(fixed){
				anchor.height(0);
				content.removeClass('fixed'); 
				fixed = false;
			}
		}
    };
    $(window).on('scroll', onScroll);
};
var sticky = new StickyElement($('.sticky-element'));
