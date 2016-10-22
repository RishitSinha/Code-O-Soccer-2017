/**
 * Created by Rishit on 06-10-2016.
 */

$(document).ready(function() {
    var flag =0;

    // $('#about').fadeOut(0);
    $('#laptop').fadeOut(0);
    $('#info1').fadeOut(0);
    $('.Gio-98').fadeOut(0);
    $('#popup-wrapper').fadeOut(0);

    $('#fullpage').fullpage({
        normalScrollElements: '.normal',
        bigSectionsDestination: top,
        scrollingSpeed: 1000,
        scrollBar: true,
        // scrollOverflow: true,
        navigation: true,

        afterLoad: function(anchorLink, index){
            var loadedSection = $(this);



            if(index == 2){
                flag = 1;
                $('nav').addClass('fixed');
                $('#about').fadeIn(400);
                $('.box').removeClass('boxhover');
                setTimeout(function() {
                    $('#laptop').fadeIn(400);
                }, 1000);
                setTimeout(function() {
                    $('#info1').fadeIn(400);
                    setTimeout(function () {
                        flag = 0;
                    },200);
                }, 1200);
                $('.box').addClass('fixed');
            }
            if(index == 3){
                $('#info1').fadeOut(0);
                setTimeout(function() {
                    $('#info1').fadeOut(0);
                }, 500);
                $('.Gio-98').fadeIn(400);
                setTimeout(function() {
                    $('#info1').fadeOut(0);
                }, 100);
            }
        },

        onLeave: function(index, nextIndex, direction){
            var leavingSection = $(this);

            //after leaving section 2
            if(index == 2){
                if(flag) return false;
                $('#info1').fadeOut(150);
                $('#laptop').fadeOut(150);
                $('.box').addClass('boxhover');
                $('.box').removeClass('fixed');
            }

            if(index == 3){
                $('.Gio-98').fadeOut(150);
            }
            // if(index == 3 && direction == 'up'){
            //     setTimeout(function() {
            //         $('#info1').fadeIn(400);
            //     }, 700);
            // }
            if(index == 3 && direction == 'down'){
                    $('#about').fadeOut(150);
            }
        }

    });

    var curPage = 1;
    var numOfPages = $(".skw-page").length;
    var animTime = 1000;
    var scrolling = false;
    var pgPrefix = ".skw-page-";

    function pagination() {
        scrolling = true;

        $(pgPrefix + curPage).removeClass("inactive").addClass("active");
        $(pgPrefix + (curPage - 1)).addClass("inactive");
        $(pgPrefix + (curPage + 1)).removeClass("active");

        setTimeout(function() {
            scrolling = false;
        }, animTime);
    };

    function navigateUp() {
        if (curPage === 1) {
            $.fn.fullpage.moveTo(5);
            return;
        }
        curPage--;
        pagination();
    };

    function navigateDown() {
        if (curPage === numOfPages) {
            $.fn.fullpage.moveSectionDown();
            return;
        }
        curPage++;
        pagination();
    };

    $(document).on("mousewheel DOMMouseScroll", function(e) {
        e.preventDefault();
        if (scrolling) return;
        if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
            navigateUp();
        } else {
            navigateDown();
        }
    });

    $(document).on("keydown", function(e) {
        if (scrolling) return;
        if (e.which === 38) {
            navigateUp();
        } else if (e.which === 40) {
            navigateDown();
        }
    });

});