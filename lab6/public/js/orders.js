/***********************************************************************************************************
 ******                            Show All all orders                                      ******
 **********************************************************************************************************/

//This function gets called when the orders link is clicked
function showAllOrders() {
    console.log('Show all orders');
    const url = baseUrl_API + "/orders?limit=50";
    fetch(url, {
        method: 'GET',
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(checkFetch)
        .then(response => response.json())
        .then(orders => displayAllOrders(orders))
        .catch(err => showMessage("Errors", err)) //display errors
}

//call back function to display all orders
function displayAllOrders(orders, subheading=null) {
    console.log("display all message for the editing purpose")
    console.log(orders)
    // search box and the row of headings
    let _html = `<div style='text-align: right; margin-bottom: 3px'>
<!--            <input id='search-term' placeholder='Enter search terms'> -->
<!--            <button id='btn-post-search' onclick='searchPosts()'>Search</button></div>-->
            <div class='content-row content-row-header'>
            <div class='post-id'>ID</div>
            <div class='post-body'>Customer Name</div>
            <div class='post-image'>Amount</div>
             <div class='post-create'>Item ID</div>
            </div>`;  //end the row

    // content rows
    for (let x in orders) {
        let order = orders[x];
        _html += `<div class='content-row'>
            <div class='post-id'>${order.id}</div>
            <div class='post-body' id='post-edit-body-${order.id}'>${order.customerName}</div> 
            <div class='post-image' id='post-edit-image_url-${order.id}'>${order.amount}</div>
            <div class='post-create' id='post-edit-updated_at-${order.id}'>${order.itemId}</div>`;

        _html += `<div class='list-edit'><button id='btn-post-edit-${order.id}' onclick=editOrder('${order.id}') class='btn-light'> Edit </button></div>
            <div class='list-update'><button id='btn-post-update-${order.id}' onclick=updateOrder('${order.id}') class='btn-light btn-update' style='display:none'> Update </button></div>
            <div class='list-delete'><button id='btn-post-delete-${order.id}' onclick=deleteOrder('${order.id}') class='btn-light'>Delete</button></div>
            <div class='list-cancel'><button id='btn-post-cancel-${order.id}' onclick=cancelUpdateOrder('${order.id}') class='btn-light btn-cancel' style='display:none'>Cancel</button></div>`

        _html += '</div>';  //end the row
    }

    //the row of element for adding a new message

    _html += `<div class='content-row' id='post-add-row' style='display: none'> 
            <div class='post-id post-editable' id='post-new-user_id' contenteditable='true' content="User ID"></div>
            <div class='post-body post-editable' id='post-new-body' contenteditable='true'></div>
            <div class='post-image post-editable' id='post-new-image_url' contenteditable='true'></div>
            <div class='list-update'><button id='btn-add-post-insert' onclick='addOrder()' class='btn-light btn-update'> Insert </button></div>
            <div class='list-cancel'><button id='btn-add-post-cancel' onclick='cancelAddPost()' class='btn-light btn-cancel'>Cancel</button></div>
            </div>`;  //end the row

    // add new message button
    _html += `<div class='content-row post-add-button-row'><div class='post-add-button' onclick='showAddRow()'>+ ADD A NEW ORDER</div></div>`;

    //Finally, update the page
    subheading = (subheading == null) ? 'All Orders' : subheading;
    updateMain('Orders', subheading, _html);
}

/***********************************************************************************************************
 ******                            Search Messages                                                    ******
 **********************************************************************************************************/
// function searchPosts() {
//     console.log('searching for messages');
//     let term = $("#search-term").val();
// //console.log(term);
//     const url = baseUrl_API + "/messages?q=" + term;
//     let subheading = '';
// //console.log(url);
//     if (term == '') {
//         subheading = "All Messages";
//     } else if (isNaN(term)) {
//         subheading = "Messages Containing '" + term + "'"
//     } else {
//         subheading = "Messages whose ID is having" + term;
//     }
// //send the request
//     fetch(url, {
//         method: 'GET',
//         headers: {"Authorization": "Bearer " + jwt}
//     })
//         .then(checkFetch)
//         .then(response => response.json())
//         .then(posts => displayAllPosts(posts))
//         .catch(err => showMessage("Errors", err)) //display errors
// }


/***********************************************************************************************************
 ******                            Edit a Message                                                     ******
 **********************************************************************************************************/

// This function gets called when a user clicks on the Edit button to make items editable
function editOrder(id) {
    //Reset all items
    resetOrder();

    //select all divs whose ids begin with 'post' and end with the current id and make them editable
    $("div[id^='post-edit'][id$='" + id + "']").each(function () {
        $(this).attr('contenteditable', true).addClass('post-editable');
    });

    $("div#post-edit-body-" + id).attr('contenteditable', true).addClass('post-editable');
    $("div#post-edit-image_url-" + id).attr('contenteditable', true).addClass('post-editable');
    $("div#post-edit-updated_at-" + id).attr('contenteditable', true).addClass('post-editable');


    $("button#btn-post-edit-" + id + ", button#btn-post-delete-" + id).hide();
    $("button#btn-post-update-" + id + ", button#btn-post-cancel-" + id).show();
    $("div#post-add-row").hide();
}

//This function gets called when the user clicks on the Update button to update a message record
function updateOrder(id) {
    console.log('update the order whose id is ' + id);
    let data = {};
    data['customerName'] = $("div#post-edit-body-" + id).html();
    data['amount'] = $("div#post-edit-image_url-" + id).html();
    data['itemId'] = $("div#post-edit-updated_at-"  + id).html();
    console.log(data);
    const url = baseUrl_API + "/orders/" + id;
    console.log(url);
    fetch(url, {
        method: 'PATCH',
        headers: {
            "Authorization": "Bearer " + jwt,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(checkFetch)
        .then(() => resetOrder())
        .catch(error => showMessage("Errors", error))
}


//This function gets called when the user clicks on the Cancel button to cancel updating a message
function cancelUpdateOrder(id) {
    showAllOrders();
}

/***********************************************************************************************************
 ******                            Delete a Message                                                   ******
 **********************************************************************************************************/

// // This function confirms deletion of a message. It gets called when a user clicks on the Delete button.
function deleteOrder(id) {
    $('#modal-button-ok').html("Delete").show().off('click').click(function () {
        removePost(id);
    });
    $('#modal-button-close').html('Cancel').show().off('click');
    $('#modal-title').html("Warning:");
    $('#modal-content').html('Are you sure you want to delete the order?');

    // Display the modal
    $('#modal-center').modal();
}

// Callback function that removes a message from the system. It gets called by the deletePost function.
function removePost(id) {
    console.log('remove the order whose id is ' + id);
    let url = baseUrl_API + "/orders/" + id;
    fetch(url, {
        method: 'DElETE',
        headers: {"Authorization": "Bearer " + jwt,},
    })
        .then(checkFetch)
        .then(() => showAllOrders())
        .catch(error => showMessage("Errors", error))
}


/***********************************************************************************************************
 ******                            Add a Message                                                      ******
 **********************************************************************************************************/
// //This function shows the row containing editable fields to accept user inputs.
// // It gets called when a user clicks on the Add New Student link
function showAddRow() {
    resetOrder(); //Reset all items
    $('div#post-add-row').show();
}

//This function inserts a new message. It gets called when a user clicks on the Insert button.
function addOrder() {
    console.log('Add a new order');
    let data = {};

    // $("div[id^='post-new-']").each(function () {
    //     let field = $(this).attr('id').substr(9);
    //     let value = $(this).html();
    //     data[field] = value;
    // });

    data['customerName'] = $("div#post-new-user_id").html();
    data['itemId'] = $("div#post-new-body").html();
    data['amount'] = $("div#post-new-image_url").html();

    // let data_final =  {};
    // data_final['customerName'] = data.customerName;
    // data_final['itemId'] = data.itemId;
    // data_final['amount'] = data.amount;

    console.log(data);
    // console.log('final data');
    // console.log(data_final);

    const url = baseUrl_API + "/orders";
    console.log(url);

    fetch(url, {
        method: 'POST',
        headers: {
            "Authorization": "Bearer " + jwt,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(checkFetch)
        .then(() => showAllOrders())
        .catch(err => showMessage("Errors", err))
}



// This function cancels adding a new message. It gets called when a user clicks on the Cancel button.
function cancelAddPost() {
    $('#post-add-row').hide();
}

/***********************************************************************************************************
 ******                            Check Fetch for Errors                                             ******
 **********************************************************************************************************/
/* This function checks fetch request for error. When an error is detected, throws an Error to be caught
 * and handled by the catch block. If there is no error detetced, returns the promise.
 * Need to use async and await to retrieve JSON object when an error has occurred.
 */
let checkFetch = async function (response) {
    if (!response.ok) {
        await response.json()  //need to use await so Javascipt will until promise settles and returns its result
            .then(result => {
                throw Error(JSON.stringify(result, null, 4));
            });
    }
    return response;
}


/***********************************************************************************************************
 ******                            Reset post section                                                 ******
 **********************************************************************************************************/
//Reset post section: remove editable features, hide update and cancel buttons, and display edit and delete buttons
function resetOrder() {
    // Remove the editable feature from all divs
    $("div[id^='post-edit-']").each(function () {
        $(this).removeAttr('contenteditable').removeClass('post-editable');
    });

    // Hide all the update and cancel buttons and display all the edit and delete buttons
    $("button[id^='btn-post-']").each(function () {
        const id = $(this).attr('id');
        if (id.indexOf('update') >= 0 || id.indexOf('cancel') >= 0) {
            $(this).hide();
        } else if (id.indexOf('edit') >= 0 || id.indexOf('delete') >= 0) {
            $(this).show();
        }
    });
}