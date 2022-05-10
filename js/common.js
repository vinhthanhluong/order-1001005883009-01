$(function () {
    "use strict";
    function anchorLink(el) {
        var p = $(el).offset();
        var offsetPC = 120;
        var offsetSP = $(window).width() <= 600 ? 90 : 100;
        if ($(window).width() > 750) {
            $('html,body').animate({ scrollTop: p.top - offsetPC }, 400);
        } else {
            $('html,body').animate({ scrollTop: p.top - offsetSP }, 400);
        }
    }
    var obj = {
        init: function () {
            this.visual();
            this.toTop();
            this.slickTop();
            this.menu();
            this.heightTreat();
            this.staffSlide();
            this.anchorLink();
            this.heightUnder();
        },

        visual: function () {
            if ($('.slick-wrap').length > 0) {
                $('.slick-wrap').slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
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

                if ($('#visual').length) {

                    var offsetVis = $('#visual').offset().top;
                    var heightVis = $('#visual').innerHeight();
                    var visual = offsetVis + heightVis;
                    if (st > visual) {
                        $('#totop').fadeIn();
                    } else {
                        $('#totop').fadeOut();
                    }
                } else {

                    if (st > 20) {
                        $('#totop').fadeIn();
                    } else {
                        $('#totop').fadeOut();
                    }
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
                    autoplay: true,
                    autoplaySpeed: 5000,
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
                $('body').toggleClass('no-scroll');
            });

            $(document).mouseup(function (e) {
                var $menu = $('.menu_icon');
                var $gnavi = $('#gnavi');
                if (!$menu.is(e.target) && $gnavi.has(e.target).length === 0) {
                    $('.menu_icon').removeClass('--active');
                    $('#gnavi').removeClass('--show');
                    $('body').removeClass('no-scroll');
                }
            });

            $(window).scroll(function () {
                var st = $(window).scrollTop();
                if (st > 10) {
                    $('#header').addClass('--fixed');
                } else {
                    $('#header').removeClass('--fixed');
                }

                if ($(window).width() <= 750) {
                    var offsetVis = $('.vis-man').offset().top;
                    var heightVis = $('.vis-man').innerHeight();
                    var visual = offsetVis + heightVis;

                    if (st > visual) {
                        $('.btn-fix').css('transform', 'translateY(0%)');
                    } else {
                        $('.btn-fix').css('transform', 'translateY(100%)');
                    }
                }




            });


        },

        heightTreat: function () {
            var _w = $(window).width();
            if (_w < 751) {
                var item = $('.treat-item .treat-txt');

                function loopH(args) {
                    var _aLoop = [];
                    for (let i = 0; i < args.length; i++) {
                        var element = item[i];
                        var _mHeight = $(element).innerHeight();
                        _aLoop.push(_mHeight)
                    }
                    return _aLoop;
                }

                var arrayHeight = loopH(item);
                var bigNumber = Math.max.apply(Math, arrayHeight)
                $('.treat-item .treat-txt').css('min-height', bigNumber);
            }

        },

        heightUnder: function () {
            var item = $('#und-slick .staff_ul01');
            function loopH(args) {
                var _aLoop = [];
                for (let i = 0; i < args.length; i++) {
                    var element = item[i];
                    var _mHeight = $(element).outerHeight();
                    _aLoop.push(_mHeight)
                }
                return _aLoop;
            }

            var arrayHeight = loopH(item);
            var bigNumber = Math.max.apply(Math, arrayHeight)
            $('#und-slick .staff_ul01').css('min-height', bigNumber);
        },


        staffSlide: function () {
            if ($('#und-slick').length > 0) {
                $('#und-slick').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    variableWidth: true,
                    autoplay: true
                });
            }
        },

        anchorLink: function () {
            $(window).on('load', function () {
                "use strict";
                // ANCHOR FROM OTHER PAGE
                var hash = location.hash;
                if (hash && $(hash).length > 0) {
                    anchorLink(hash);
                }
                // ANCHOR IN PAGE
                $('a[href^="#"]').click(function () {
                    var getID = $(this).attr('href');
                    if ($(getID).length) {
                        anchorLink(getID);
                        // CLOSE SP NAV
                        if ($('body').hasClass('open-nav')) {
                            $('#menu-toggle').trigger('click');
                        }
                        return false;
                    }
                });
            });
        },
    };

    obj.init();
});