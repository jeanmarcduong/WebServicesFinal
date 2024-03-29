//global variables
var jwt = '';   //JSON Web token

//This function get called when the sign hash is clicked.
function signin() {
    $('.img-loading, main, .form-signup, #li-signout, #li-signup').hide();
    $('.form-signin, #li-signin').show();
    $("li#li-professor > a, li#li-course > a, li#li-student > a").addClass('disabled');
}

//submit username and password to be verified by the API server
$('form.form-signin').submit(function (e) {

    //temporary code for experimental purpose. Delete all the code.
    // e.preventDefault();
    // jwt = 'jwt';
    // console.log('user signed in');

    //$('.img-loading').show();
    e.preventDefault();
    let username = $('#signin-username').val();
    let password = $('#signin-password').val();
    const url = baseUrl_API + '/employees/authJWT';
//console.log(url);
//console.log(username + password);
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {username: username, password: password},
    }).done(function (data) {
        jwt = data.jwt;
//successfully signed in; enable all links in nav bar, hide signin and show signout links
        $('a.nav-link.disabled').removeClass('disabled'); //enable all links
        $("li#li-signin, form.form-signin").hide(); //hide the signin link and form
        $("li#li-signout").show(); //show the signout link

        showMessage("Sign in Message", "You have successfully signed in. Click on the links in the nav bar to explore the site.");
    }).fail(function (jqXHR, textStatus) {
        console.log(jqXHR);
        showMessage('Sign in Error', JSON.stringify(jqXHR.responseJSON, null,
            4));
    }).always(function () {
    });

});