/**
 * Created by Rishit on 06-10-2016.
 */

for(var i=0;i<10;i++){
    setInterval(function () {
        var heroHeight = $('header').height();
        var yPosition = $(document).scrollTop();

        if (yPosition <= heroHeight) {
            var effectFactor = yPosition / heroHeight;
            var rotation = effectFactor * (Math.PI / 2 - Math.asin( (heroHeight - yPosition) / heroHeight ));
            $('.hero').css({
                '-webkit-transform': 'rotateX('+rotation+'rad)',
                'transform': 'rotateX('+rotation+'rad)',
            })
                .find('.overlay').css('opacity', effectFactor + 0.8);
        }
        /**
         * Sticky nav-bar
         */
        if (yPosition <= heroHeight) {
            $('nav').removeClass('fixed');
        } else {
            $('nav').addClass('fixed');
        }
    },1);
}


// $(window).on("mousewheel DOMMouseScroll", function(e) {
//
// });

$(".hover").mouseleave(
    function () {
        $(this).removeClass("hover");
    }
);


// ----------------------------------header----------------------------------------------------------

particlesJS("particles-js", {
    "particles": {
        "number": {
            "value": 120,
            "density": {
                "enable": true,
                "value_area": 800
            }
        },
        "color": {
            "value": "#fff"
        },
        "shape": {
            "type": "circle",
            "stroke": {
                "width": 0,
                "color": "#71C3B9"
            },
            "polygon": {
                "nb_sides": 5
            }
        },
        "opacity": {
            "value": 1,
            "random": false,
            "anim": {
                "enable": false,
                "speed": 1,
                "opacity_min": 0.1,
                "sync": false
            }
        },
        "size": {
            "value": 2,
            "random": true,
            "anim": {
                "enable": false,
                "speed": 80,
                "size_min": 0.1,
                "sync": false
            }
        },
        "line_linked": {
            "enable": true,
            "distance": 130,
            "color": "#fff",
            "opacity": 0.4,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 12,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
            }
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {
                "enable": false,
                "mode": "repulse"
            },
            "onclick": {
                "enable": true,
                "mode": "push"
            },
            "resize": true
        },
        "modes": {
            "grab": {
                "distance": 300,
                "line_linked": {
                    "opacity": 1
                }
            },
            "bubble": {
                "distance": 200,
                "size": 80,
                "duration": 2,
                "opacity": 0.8,
                "speed": 3
            },
            "repulse": {
                "distance": 100,
                "duration": 0.4
            },
            "push": {
                "particles_nb": 4
            },
            "remove": {
                "particles_nb": 2
            }
        }
    },
    "retina_detect": true
});

// ------------------------------------------------------------------------------
