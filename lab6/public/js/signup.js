//This function get called when the signup hash is clicked.
function signup() {
    $('.img-loading, main, .form-signin, #li-signin').hide();
    $('.form-signup, #li-signup').show();

    //window.location.hash = 'signup';
}

//submit the form to create a user account
$('form.form-signup').submit(function (e) {
    e.preventDefault();
    let name = $('#signup-name').val();
    let username = $('#signup-username').val();
    let role = $('#signup-role').val();
    let password = $('#signup-password').val();

    const url = baseUrl_API + '/employees';
    $.ajax({
        url: url,
        method: 'post',
        dataType: 'json',
        data: {username: username, firstName: name, role: role, password: password}
    }).done(function () {
//show a message after a sussessful login
        showMessage('Signup Message', 'Thanks for signing up. Your account has been created.');
        $('li#li-signin').show();
        $('li#li-signout').hide();
    }).fail(function (jqXHR, textStatus) {
        showMessage('Signup Error', JSON.stringify(jqXHR.responseJSON, null, 4));
    }).always(function () {
        console.log('Signup has Completed.');
    });



});