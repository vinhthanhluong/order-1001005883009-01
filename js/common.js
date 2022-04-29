$(function () {
    "use strict";
    var obj = {
        init: function () {
            this.visual();
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

    };

    obj.init();
});