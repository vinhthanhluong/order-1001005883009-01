$(function () {
    "use strict";
    var obj = {
        init: function () {
            this.visual();
            this.toTop();
        },

        visual: function () {
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
        },

        toTop: function(){
            var _w = $(window).width();
            $(window).scroll(function () { 
                var st = $(window).scrollTop();
                if (st > 20) {
                    $('#totop').fadeIn();
                }else{
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

    };

    obj.init();
});