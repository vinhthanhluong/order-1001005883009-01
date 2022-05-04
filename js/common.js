$(function () {
    "use strict";
    var obj = {
        init: function () {
            this.visual();
            this.toTop();
            this.slickTop();
            this.menu();
        },

        visual: function () {
            if ($('.slick-wrap').length > 0) {
                $('.slick-wrap').slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    // autoplay: true,
                    // autoplaySpeed: 5000,
                    arrows: false,
                    centerMode: false,
                    centerPadding: 0,
                    pauseOnHover: false,
                    fade: false,
                    variableWidth: false,
                });
            }
        },

        toTop: function () {
            var _w = $(window).width();
            $(window).scroll(function () {
                var st = $(window).scrollTop();
                if (st > 20) {
                    $('#totop').fadeIn();
                } else {
                    $('#totop').fadeOut();
                }
            });

            $('#totop').click(function () {
                $('html,body').animate({
                    scrollTop: 0,
                }, 800);
                return false;
            });
        },

        slickTop: function () {
            if ($('#treat-slick').length > 0 && $(window).width() < 751) {
                $('#treat-slick').slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    // autoplay: true,
                    // autoplaySpeed: 5000,
                    arrows: true,
                    centerMode: false,
                    centerPadding: 0,
                    pauseOnHover: false,
                    fade: false,
                    variableWidth: false,
                });
            }
        },

        menu: function (e) {
            $('.menu_icon').click(function () {
                $(this).toggleClass('--active');
                $('#gnavi').toggleClass('--show');
            });


            $(document).mouseup(function (e) {
                var $menu = $('.menu_icon');
                var $gnavi = $('#gnavi');
                if (!$menu.is(e.target) && $gnavi.has(e.target).length === 0) {
                    $('.menu_icon').removeClass('--active');
                    $('#gnavi').removeClass('--show');
                }
            });


            $(window).scroll(function () {
                var st = $(window).scrollTop();
                if (st > 10) {
                    $('#header').addClass('--fixed');
                } else {
                    $('#header').removeClass('--fixed');
                }

                if (st > 10 && $(window).width() > 750) {
                    $('.menu_icon').removeClass('--active');
                    $('#gnavi').removeClass('--show');
                }
                

                if ( $(window).width() <= 750 ) {
                    if (st > 10) {
                        $('.btn-fix').css('transform', 'translateY(0%)');
                    } else{
                        $('.btn-fix').css('transform', 'translateY(100%)');
                    }
                } 



            });
        }

    };

    obj.init();
});