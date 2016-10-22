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

// -----------------------------------------------------------------------------

// -----------------------------Acordion----------------------------------

(function($) {
    $('.accordion > li:eq(0) a').addClass('active').next().slideDown();

    $('.accordion a').click(function(j) {
        var dropDown = $(this).closest('li').find('p');

        $(this).closest('.accordion').find('p').not(dropDown).slideUp();

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).closest('.accordion').find('a.active').removeClass('active');
            $(this).addClass('active');
        }

        dropDown.stop(false, true).slideToggle();

        j.preventDefault();
    });
})(jQuery);

// ------------------------------------------------------------------------------------



$("#popup").click(function(event){
    event.stopPropagation();
});




function sign_up(){
    var inputs = document.querySelectorAll('.input_form_sign');
    document.querySelectorAll('.ul_tabs > li')[0].className="";
    document.querySelectorAll('.ul_tabs > li')[1].className="active";

    for(var i = 0; i < inputs.length ; i++  ) {
        if(i == 2  ){

        }else{
            document.querySelectorAll('.input_form_sign')[i].className = "input_form_sign d_block";
        }
    }

    setTimeout( function(){
        for(var d = 0; d < inputs.length ; d++  ) {
            document.querySelectorAll('.input_form_sign')[d].className = "input_form_sign d_block active_inp";
        }


    },100 );
    document.querySelector('.link_forgot_pass').style.opacity = "0";
    document.querySelector('.link_forgot_pass').style.top = "-5px";
    document.querySelector('.btn_sign').innerHTML = "SIGN UP";
    setTimeout(function(){

        document.querySelector('.terms_and_cons').style.opacity = "1";
        document.querySelector('.terms_and_cons').style.top = "5px";

    },500);
    setTimeout(function(){
        document.querySelector('.link_forgot_pass').className = "link_forgot_pass d_none";
        document.querySelector('.terms_and_cons').className = "terms_and_cons d_block";
    },450);

};



function sign_in(){
    var inputs = document.querySelectorAll('.input_form_sign');
    document.querySelectorAll('.ul_tabs > li')[0].className = "active";
    document.querySelectorAll('.ul_tabs > li')[1].className = "";

    for(var i = 0; i < inputs.length ; i++  ) {
        switch(i) {
            case 1:
                console.log(inputs[i].name);
                break;
            case 2:
                console.log(inputs[i].name);
            default:
                document.querySelectorAll('.input_form_sign')[i].className = "input_form_sign d_block";
        }
    }

    setTimeout( function(){
        for(var d = 0; d < inputs.length ; d++  ) {
            switch(d) {
                case 1:
                    console.log(inputs[d].name);
                    break;
                case 2:
                    console.log(inputs[d].name);

                default:
                    document.querySelectorAll('.input_form_sign')[d].className = "input_form_sign d_block";
                    document.querySelectorAll('.input_form_sign')[2].className = "input_form_sign d_block active_inp";

            }
        }
    },100 );

    document.querySelector('.terms_and_cons').style.opacity = "0";
    document.querySelector('.terms_and_cons').style.top = "-5px";

    setTimeout(function(){
        document.querySelector('.terms_and_cons').className = "terms_and_cons d_none";
        document.querySelector('.link_forgot_pass').className = "link_forgot_pass d_block";

    },500);

    setTimeout(function(){

        document.querySelector('.link_forgot_pass').style.opacity = "1";
        document.querySelector('.link_forgot_pass').style.top = "5px";


        for(var d = 0; d < inputs.length ; d++  ) {

            switch(d) {
                case 1:
                    console.log(inputs[d].name);
                    break;
                case 2:
                    console.log(inputs[d].name);

                    break;
                default:
                    document.querySelectorAll('.input_form_sign')[d].className = "input_form_sign";
            }
        }
    },1500);
    document.querySelector('.btn_sign').innerHTML = "SIGN IN";
};


window.onload =function(){
    document.querySelector('.cont_centrar').className = "cont_centrar cent_active";
};


