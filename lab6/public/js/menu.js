/***********************************************************************************************************
 ******                            Show Menu                                                        ******
 **********************************************************************************************************/
//This function shows all posts. It gets called when a user clicks on the Post link in the nav bar.

// Pagination, sorting, and limiting are disabled
function showMenu (offset = 0) {
    console.log('show all menu items');
    //const url = baseUrl_API + '/menu';



    //const url = baseUrl_API + '/messages';therwise, set a default value.
    let limit = ($("#post-limit-select").length) ? $('#post-limit-select option:checked').val() : 5;
    let sort = ($("#post-sort-select").length) ? $('#post-sort-select option:checked').val() : "id:asc";
//construct the request url
    const url = baseUrl_API + '/menu?limit=' + limit + "&offset=" + offset + "&sort=" + sort;



//define AXIOS request
    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(function (response) {
            displayMenu(response.data);
        })
        .catch(function (error) {
            handleAxiosError(error);
        });

}

//Callback function: display all posts; The parameter is a promise returned by axios request.
function displayMenu (response) {
    console.log(response);
    let _html;


    _html =
        "<div class='content-row content-row-header'>" +
        "<div class='post-id'>Item Name</></div>" +
        "<div class='post-body'>Origin</></div>" +
        "<div class='post-create'>Price</div>" +
        "<input id='search-term' placeholder='Enter search terms'>" +
        "<button id='btn-post-search' onclick='searchPosts()'>Search</button></div>" +
        "</div>";
    let item = response.data;
    item.forEach(function(item, x){
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='post-id'>" +
            "<span class='list-key' onclick=showItems(" + item.id + "," + item.price +") title='Get item orders'>" + item.name + "</span>" +
            "</div>" +
            "<div class='post-body'>" + item.origin + "</div>" +
            "<div class='post-create'>" + item.price + "</div>" +
            "</div>" //+
            //"<div class='container post-detail' id='post-detail-" + item.id + "' style='display: none'></div>";
    });

    _html += "<div class='content-row course-pagination'><div>";
//pagination
    _html += paginatePosts(response);
//items per page
    _html += limitPosts(response);
//sorting
    _html += sortPosts(response);
//end the div block
    _html += "</div></div>";
    //Finally, update the page
    updateMain('Menu', 'All Menu Items', _html);
}

function searchPosts() {
    console.log('searching for messages');
    let term = $("#search-term").val();
//console.log(term);
    const url = baseUrl_API + "/menu?q=" + term;
    let subheading = '';
//console.log(url);
    if (term == '') {
        subheading = "All Messages";
    } else if (isNaN(term)) {
        subheading = "Messages Containing '" + term + "'"
    } else {
        subheading = "Messages whose ID is having" + term;
    }
//send the request
    fetch(url, {
        method: 'GET',
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(checkFetch)
        .then(response => response.json())
        .then(menu => displayMenu(menu))
        .catch(err => showMessage("Errors", err)) //display errors
}




/***********************************************************************************************************
 ******                            Show Comments made for a message                                   ******
 **********************************************************************************************************/
/* Display all comments. It get called when a user clicks on a message's id number in
 * the message list. The parameter is the message id number.
*/
function showItems(number, price) {
    console.log('get a menus\' item\'s orders');
    console.log(price)
    let url = baseUrl_API + '/menu/' + number + '/orders';
    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(function (response) {
//console.log(response.data);
            displayOrders(number, response.data, price);
        })
        .catch(function (error) {
            handleAxiosError(error);
        });
}




// Callback function that displays all posts made by a user.
// Parameters: user's name, an array of Post objects
function displayOrders(number, response, price) {
    let _html = "<div class='post_preview'>No menu orders were found.</div>";
    console.log(response)
    if (response.length > 0) {
        _html = "<table class='post_preview'>" +
            "<tr>" +
            "<th class='post_preview-body'>Customer Name</th>" +
            "<th class='post_preview-image'>Amount</th>" +
            "<th class='post_preview-create'>Total</th>"
            "</tr>";

        for (let x in response) {
            let order = response[x];
            _html += "<tr>" +
                "<td class='post_preview-body'>" + order.customerName + "</td>" +
                "<td class='post_preview-image'>" + order.amount + "</td>" +
                "<td class='post_preview-create'>"+ "$" + order.amount * price + "</td>" +

                "</tr>"
        }
        _html += "</table>"
    }

    // set modal title and content
    $('#modal-title').html("Menu Item Orders");
    $('#modal-button-ok').hide();
    $('#modal-button-close').html('Close').off('click');
    $('#modal-content').html(_html);

    // Display the modal
    $('#modal-center').modal();
}



/***************************************************************************
 *************************
 ********* This function handles errors occurred by an
 AXIOS request. **********
 ****************************************************************************
 ***********************/
function handleAxiosError(error) {
    let errMessage;
    if (error.response) {
// The request was made and the server responded with a status code of 4xx or 5xx
        errMessage = {"Code": error.response.status, "Status":
            error.response.data.status};
    } else if (error.request) {
// The request was made but no response was received
        errMessage = {"Code": error.request.status, "Status":
            error.request.data.status};
    } else {
// Something happened in setting up the request that triggered an
        error
        errMessage = JSON.stringify(error.message, null, 4);
    }
    showMessage('Error', errMessage);
}


// Callback function that displays all details of a course.
// Parameters: course number, a promise
// function displayOrders(number, response) {
//     let _html = "<div class='content-row content-row-header'>Menu Item Orders</div>";
//     let orders = response.data;
//     //console.log(number);
//
//     orders.forEach(function(order, x){
//         _html +=
//             "<div class='post-detail-row'><div class='post-detail-label'>ID</div><div class='post-detail-field'>" + order.id + "</div></div>" +
//             "<div class='post-detail-row'><div class='post-detail-label'>Customer Name</div><div class='post-detail-field'>" + order.customerName + "</div></div>" +
//             "<div class='post-detail-row'><div class='post-detail-label'>Amount</div><div class='post-detail-field'>" + order.itemId + "</div></div>" +
//             "<div class='post-detail-row'><div class='post-detail-label'>Item ID</div><div class='post-detail-field'>" + order.amount + "</div></div>";
//     });
//     console.log(orders);
//     $('#order-detail-' + number).html(_html);
//     $("[id^='order-detail-']").each(function(){   //hide the visible one
//         $(this).not("[id*='" + number + "']").hide();
//     });
//
//     $('#order-detail-' + number).toggle();
// }


/*******************************************************************************
 *********************
 ********* Paginating, sorting, and limiting courses
 **********
 ********************************************************************************
 *******************/
//paginate all messages
function paginatePosts(response) {
//calculate the total number of pages
    let limit = response.limit;
    let totalCount = response.totalCount;
    let totalPages = Math.ceil(totalCount / limit);
//determine the current page showing
    let offset = response.offset;
    let currentPage = offset / limit + 1;
//retrieve the array of links from response json
    let links = response.links;
//convert an array of links to JSON document. Keys are "self", "prev", "next", "first", "last"; values are offsets.
    let pages = {};

//extract offset from each link and store it in pages
    links.forEach(function (link) {
        let href = link.href;
        let offset = href.substr(href.indexOf('offset') + 7);

        pages[link.rel] = offset;
    });
    if (!pages.hasOwnProperty('prev')) {
        pages.prev = pages.self;
    }
    if (!pages.hasOwnProperty('next')) {
        pages.next = pages.self;
    }
//generate HTML code for links
    let _html = `Showing Page ${currentPage} of
${totalPages}&nbsp;&nbsp;&nbsp;&nbsp;
<a href='#course' title="first page"
onclick='showMenu(${pages.first})'> << </a>
<a href='#course' title="previous page"
onclick='showMenu(${pages.prev})'> < </a>
<a href='#course' title="next page"
onclick='showMenu(${pages.next})'> > </a>
<a href='#course' title="last page"
onclick='showMenu(${pages.last})'> >> </a>`;
    return _html;
}

//limit messages
function limitPosts(response) {
//define an array of courses per page options
    let postsPerPageOptions = [5, 10, 20];
//create a selection list for limiting courses
    let _html = `&nbsp;&nbsp;&nbsp;&nbsp; Items per page:<select id='post-limit-select' onChange='showMenu()'>`;
    postsPerPageOptions.forEach(function (option) {
        let selected = (response.limit == option) ? "selected" : "";
        _html += `<option ${selected} value="${option}">${option}</option>`;
    });
    _html += "</select>";
    return _html;
}

//sort messages
function sortPosts(response) {
//create selection list for sorting
    let sort = response.sort;
//sort field and direction: convert json to a string then remove {, }, and "
    let sortString = JSON.stringify(sort).replace(/["{}]+/g, "");
    console.log(sortString);
//define a JSON containing sort options
    let sortOptions = {
        "id:asc": "First Menu Item ID -> Last Menu Item ID",
        "id:desc": "Last Menu Item ID -> First Menu Item ID",
        "origin:asc": "Origin A -> Z",
        "origin:desc": "Origin Z -> A"
    };
//create the selection list
    let _html = "&nbsp;&nbsp;&nbsp;&nbsp; Sort by: <select id='post-sort-select'" + "onChange='showMenu()'>";
    for (let option in sortOptions) {
        let selected = (option == sortString) ? "selected" : "";
        _html += `<option ${selected} value='${option}'>${sortOptions[option]}
</option>`;
    }
    _html += "</select>";
    return _html;
}