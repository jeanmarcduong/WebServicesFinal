var oldHash = '';
var baseUrl_API = "http://localhost/i425/lab6/api"; // you need to fill this variable with your own api url

$(function () {
    //Handle hashchange event; when a click is clicked, invoke an appropriate function
    window.addEventListener('hashchange', function (event) {
        let hash = location.hash.substr(1);  //need to remove the # symbol at the beginning.
        oldHash = event.oldURL.substr(event.oldURL.indexOf('#') + 1);

        if ($("a[href='#" + hash + "'").hasClass('disabled')) {
            showMessage('Signin Error', 'Access is not permitted. Please <a href="index.php#signin">sign in</a> to explore the site.');
            return;
        }

        //set active link
        $('li.nav-item.active').removeClass('active');
        $('li.nav-item#li-' + hash).addClass('active');

        //call appropriate function depending on the hash
        switch (hash) {
            case 'home':
                home();
                break;
            case 'employees':
                showEmployees();
                break;
            case 'details':
                showDetails();
                break;
            case 'menu':
                showMenu();
                break;
            case 'orders':
                showAllOrders();
                break;
            case 'locations':
                showLocations();
                break;
            case 'signin':
                signin();
                break;
            case 'signup':
                signup();
                break;
            case 'signout':
                signout();
                break;
            default:
                home();
        }
    });
    if(jwt == '') {
        //display homepage content and set the hash to 'home'
        home();
        window.location.hash = 'home';
    }
});

// This function sets the content of the homepage.
function home() {
    let _html =
        `<p>This is a single paged application for a bakery. </p>
        
`;

    // Update the section heading, sub heading, and content
    updateMain('Home', 'Welcome to the Bakery Application', _html);
}

// This function updates main section content.
function updateMain(main_heading, sub_heading, section_content) {
    $('main').show();  //show main section
    $('.form-signup, .form-signin').hide(); //hide the sign-in and sign-up forms

    //update section content
    $('div#main-heading').html(main_heading);
    $('div#sub-heading').html(sub_heading);
    $('div#section-content').html(section_content);
}