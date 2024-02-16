function getFormData($form){
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;

}


function ajaxGet(url, target) {

    $.ajax({
        url: url,
        dataType: "html",
        beforeSend: function (data) {
            $(target).html('');
        },
        success: function (data) {
            $(target).html(data);
        }
    });

}
function ajaxGetAsyncFalse(url, target) {

    $.ajax({
        async: false,
        url: url,
        dataType: "html",
        beforeSend: function (data) {
            $(target).html('');
        },
        success: function (data) {
            $(target).html(data);
        }
    });

}

function ajaxPost(url, target, dataToSend) {
    $.ajax ({
        type: "POST",
        url: url,
        dataType: "html",
        data: {
            'dataToSend':dataToSend
        },
        beforeSend: function(data){
            $(target).html('');
        },
        success: function(data){
            $(target).html(data);
        }
    });

}
function ajaxPostAsyncFalse(url, target, dataToSend) {

    $.ajax ({
        async:false,
        type: "POST",
        url: url,
        dataType: "html",
        data: {
            'dataToSend':dataToSend
        },
        beforeSend: function(data){
            $(target).html('');
        },
        success: function(data){
            $(target).html(data);
        }
    });

}

function getCookie(name = '') {
    let cookies = document.cookie;
    let cookiestore = {};

    cookies = cookies.split(";");

    if (cookies[0] == "" && cookies[0][0] == undefined) {
        return undefined;
    }

    cookies.forEach(function(cookie) {
        cookie = cookie.split(/=(.+)/);
        if (cookie[0].substr(0, 1) == ' ') {
            cookie[0] = cookie[0].substr(1);
        }
        cookiestore[cookie[0]] = cookie[1];
    });

    return (name !== '' ? cookiestore[name] : cookiestore);
}
