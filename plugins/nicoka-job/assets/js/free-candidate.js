jQuery(document).ready(function (n) {
    var container = n(document.body).find(".free-candidate-form");
    container.slideDown("slow");

    if (n(document.body).find("div.infos.success").length >0) {
        container.slideUp("slow");
    }

    if (n(document.body).find("div.infos").length >0) {

        var infos = n(document.body).find("div.infos").offset().top - 200;
        if (infos) {
            setTimeout(function () {
                n(document).scrollTop(infos);
            }, 500);
        }
    }

    // Email must be an email
    n('#email').on('input', function() {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var info = n('#submit-infos .error-email');
        setErrorRules(n(this), re, info);
    });

    // Phone must be 10 digits : 0000000000
    n('#phone1').on('input', function() {
        var re = /0[1-9]\d{8}/;
        var info = n('#submit-infos .error-phone');
        setErrorRules(n(this), re, info);
    });

    function setErrorRules(input, reg, info){
        var submit = n('#form-submit');
        var test=reg.test(input.val());

        if(test){
            input.removeClass("error");
            info.removeClass("error");
            submit.removeAttr('disabled');
        } else{
            input.addClass("error");
            info.addClass("error");
            submit.attr('disabled', 'disabled');
        }
    }
});
     
