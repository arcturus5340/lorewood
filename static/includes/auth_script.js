$('#login_button').on("click", function() {
    $('#error-login').hide();
    $.ajax({
        type: "POST",
        url: '/login/',
        data: {
            'login': $('#id_username').val(),
            'password': $('#id_password').val(),
            'csrfmiddlewaretoken': $("input[name=csrfmiddlewaretoken]").val(),
        },
        success: function(response) {
            // do something with response
            if (response['result'] === "Success!") {
                let url = "/";
                $(location).attr('href', url);
            } else {
                $('#error-login').show();
                $(".lwa-loading").remove()
            }
        }
    });
});

$(document).ready(function() {
    $(".account").mousemove(function() {
        $(".submenu").show();
    });

    $(".dropdown").mouseleave(function() {
        $(".submenu").hide();
    });

});

$('#remember-button').on("click", function(i) {
    $('#remember-errors').hide();
    i.preventDefault();
    $.ajax({
        type: "POST",
        url: '/remember/',
        data: $("#remember-form").serialize(),
        success: function(response) {
            // do something with response
            if (response['result'] === "Success!") {
                let url = "/";
                $(location).attr('href', url);
            } else {
                $('#remember-errors').show();
                $('#remember-errors').html(response["result"]);
            }
        }
    });
});