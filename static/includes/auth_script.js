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
            if (response['status'] === "ok") {
                let url = "/";
                $(location).attr('href', url);
            } else if (response['status'] === "verification_required") {
                let url = "/?message=VERIFICATE";
                $(location).attr('href', url);
            } else if (response['status'] === 'fail'){
                $('#error-login').show();
                $(".lwa-loading").remove()
            }
        }
    });
});

$('#login_button1').on("click", function() {
    $('#error-login1').hide();
    $.ajax({
        type: "POST",
        url: '/login/',
        data: {
            'login': $('#id_username1').val(),
            'password': $('#id_password1').val(),
            'csrfmiddlewaretoken': $("input[name=csrfmiddlewaretoken]").val(),
        },
        success: function(response) {
            // do something with response
            if (response['status'] === "success") {
                let url = "/";
                $(location).attr('href', url);
            } else if (response['status'] === "verification_required") {
                let url = "/?message=VERIFICATE";
                $(location).attr('href', url);
            } else if (response['status'] === 'fail'){
                $('#error-login1').show();
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
            if (response['status'] === "Success!") {
                let url = "/";
                $(location).attr('href', url);
            } else {
                $('#remember-errors').show();
                $('#remember-errors').html(response["status"]);
            }
        }
    });
});

$('#remember-button1').on("click", function(i) {
    $('#remember-errors1').hide();
    i.preventDefault();
    $.ajax({
        type: "POST",
        url: '/remember/',
        data: $("#remember-form1").serialize(),
        success: function(response) {
            // do something with response
            if (response['status'] === "Success!") {
                let url = "/";
                $(location).attr('href', url);
            } else {
                $('#remember-errors1').show();
                $('#remember-errors1').html(response["status"]);
            }
        }
    });
});