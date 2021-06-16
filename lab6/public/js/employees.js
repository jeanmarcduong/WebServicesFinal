/***********************************************************************************************************
 ******                            Show Employees                                                    ******
 **********************************************************************************************************/
//This function shows all Employees. It gets called when a user clicks on the Users link in the nav bar.
function showEmployees() {
    console.log('show all the employees');
    const url = baseUrl_API + '/employees';
    $.ajax({
        url: url,
        headers: {'Authorization': 'Bearer ${jwt}'}
    }).done(function (data) {
        console.log(data)
        displayEmployees(data.data);
    }).fail(function (jqXHR, textStatus) {
        let error = {'code': jqXHR.status,
            'status':jqXHR.responseJson.status};
        showMessage('Error', JSON.stringify(error, null, 4));
    });
}


//Callback function: display all Employees; The parameter is an array of Employee objects.
function displayEmployees(employees) {
    let _html;
    _html = `<div class='content-row content-row-header'>
        <div class='user-name'>Username</div>
        <div class='user-email'> Name</div>
        <div class='user-username'>Role</div>
        </div>`;

    for (let x in employees) {
        //console.log(x)
        let employee = employees[x];
        let cssClass = (x % 2== 0) ? 'content-row' : 'content-row content-row-odd';
        _html += `<div id='content-row-${employee.id}' class='${cssClass}'>
            <div class='user-name'>
                <span class='list-key' data-user='${employee.id}' 
                     onclick=showEmployeeDetails('${employee.id}') 
                     title="Get the details of the employee">${employee.username}
                </span>
            </div>
            <div class='user-email'>${employee.firstName}</div>
            <div class='user-username'>${employee.role}</div>            
            </div>`;
    }
    //Finally, update the page
    updateMain('Employees', 'All Employees', _html);
}


/***********************************************************************************************************
 ******                            Show details of employee                               ******
 **********************************************************************************************************/
/* Display posts made by a user. It get called when a user clicks on a user's name in
 * the user list. The parameter is the user's id.
*/
//Display posts made by a user in a modal
function showEmployeeDetails(id) {
    console.log('preview a user\'s all posts');
    const url = baseUrl_API + '/employees/' + id + '/details';
    const name = $("span[data-user='" + id + "']").html();
    console.log(url);
    console.log(name);
    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt}
    }).done(function(data){
        displayEmployeeDetailsPreview(name, data);
    }).fail(function(xaXHR) {
        let error = {'Code': jqXHR.status,
            'Status':jqXHR.responseJSON.status};
        showMessage('Error', JSON.stringify(error, null, 4));
    });
}




// Callback function that displays all posts made by a user.
// Parameters: user's name, an array of Post objects
function displayEmployeeDetailsPreview(user, details) {
    let _html = "<div class='post_preview'>No details were found.</div>";
    console.log(details)
    if (details.length > 0) {
        _html = "<table class='post_preview'>" +
            "<tr>" +
            "<th class='post_preview-body'>Email</th>" +
            "<th class='post_preview-image'>Years Worked</th>" +
            "<th class='post_preview-create'>Employee ID</th>" +
            "</tr>";

        for (let x in details) {
            let detail = details[x];
            _html += "<tr>" +
                "<td class='post_preview-body'>" + detail.email + "</td>" +
                "<td class='post_preview-image'>" + detail.yearsWorked + "</td>" +
                "<td class='post_preview-create'>" + detail.employeeId + "</td>" +

                "</tr>"
        }
        _html += "</table>"
    }

    // set modal title and content
    $('#modal-title').html("Employee Details of " + user);
    $('#modal-button-ok').hide();
    $('#modal-button-close').html('Close').off('click');
    $('#modal-content').html(_html);

    // Display the modal
    $('#modal-center').modal();
}