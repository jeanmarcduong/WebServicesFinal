/***********************************************************************************************************
 ******                            Show the Details                                               ******
 **********************************************************************************************************/
//This function shows all employee details. It gets called when a user clicks on the locations link in the nav bar.
function showDetails() {
    console.log('show all employee details');
    const url = baseUrl_API + '/details';
    $.ajax({
        url: url,
        headers: {'Authorization': 'Bearer ' + jwt}
    }).done(function (data) {
        console.log(data)
        displayDetails(data);
    }).fail(function (jqXHR, textStatus) {
        let error = {'code': jqXHR.status,
            'status':jqXHR.responseJson.status};
        showMessage('Error', JSON.stringify(error, null, 4));
    });
}


//Callback function: display all details; The parameter is an array of details objects.
function displayDetails(details) {
    let _html;
    _html = `<div class='content-row content-row-header'>
        <div class='user-name'>Email</div>
        <div class='user-email'> Years Worked </div>
        <div class='user-username'>Employee ID</div>
        </div>`;
    //console.log(locations)
    for (let x in details) {
        let detail = details[x];
        let cssClass = (x % 2== 0) ? 'content-row' : 'content-row content-row-odd';
        _html += `<div id='content-row-${detail.id}' class='${cssClass}'>
            <div class='user-name'>${detail.email}</div>
            <div class='user-email'>${detail.yearsWorked}</div>
            <div class='user-username'>${detail.employeeId}</div>            
            </div>`;
    }
    //Finally, update the page
    updateMain('Details', 'All Employee Details', _html);
}