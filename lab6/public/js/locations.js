/***********************************************************************************************************
 ******                            Show the locations                                                ******
 **********************************************************************************************************/
//This function shows all locations. It gets called when a user clicks on the locations link in the nav bar.
function showLocations() {
    console.log('show all the Locations');
    const url = baseUrl_API + '/locations';
    $.ajax({
        url: url,
        headers: {'Authorization': 'Bearer ' + jwt}
    }).done(function (data) {
        console.log(data)
        displayLocations(data);
    }).fail(function (jqXHR, textStatus) {
        let error = {'code': jqXHR.status,
            'status':jqXHR.responseJson.status};
        showMessage('Error', JSON.stringify(error, null, 4));
    });
}


//Callback function: display all locations; The parameter is an array of location objects.
function displayLocations(locations) {
    let _html;
    _html = `<div class='content-row content-row-header'>
        <div class='user-name'>Street</div>
        <div class='user-email'> State </div>
        <div class='user-username'>Years Open</div>
        </div>`;
    //console.log(locations)
    for (let x in locations) {
        let location = locations[x];
        let cssClass = (x % 2== 0) ? 'content-row' : 'content-row content-row-odd';
        _html += `<div id='content-row-${location.id}' class='${cssClass}'>
            <div class='user-name'>${location.street}</div>
            <div class='user-email'>${location.state}</div>
            <div class='user-username'>${location.yearsOpen}</div>            
            </div>`;
    }
    //Finally, update the page
    updateMain('Locations', 'All Locations', _html);
}

