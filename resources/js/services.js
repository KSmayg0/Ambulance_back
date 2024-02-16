function serviceBtns() {
    $(".ajaxServiceLoad").unbind("click");
    $(".ajaxServiceLoad").bind("click", function () {
        let target = $(this).attr("data-target");
        let url = $(this).attr("data-url");
        ajaxGet(url, target);
        return false;
    });
}

function serviceContentLoad(buttonSelector) {
    $(buttonSelector).unbind("click");
    $(buttonSelector).bind("click", function () {

        let target = $(this).attr("data-target");
        let url = $(this).attr("data-url");
        let id = $(this).attr("data-id");
        ajaxPost(url, target, id);
        return false;
    });
}

function editPermissionSave() {
    $(".editPermissionSave").bind("click", function () {
        // let objectSelect = document.getElementById()
        let form = $('#editPermission');
        let dataToSend = getFormData(form);

        let modalOnLoad = document.getElementById('permissionEditModal');
        modalOnLoad.style.display = "none";
        console.log(dataToSend);
        ajaxPostAsyncFalse("services/perms/editPermission",
            "#permissions_content",
            dataToSend);

        return false;

    });
}
function editMailSave() {
    $(".editMailSave").bind("click", function () {
        // let objectSelect = document.getElementById()
        let form = $('#editMail');
        let dataToSend = getFormData(form);

        let modalOnLoad = document.getElementById('mailEditModal');
        modalOnLoad.style.display = "none";
        console.log(dataToSend);
        ajaxPostAsyncFalse("services/mail/editMail",
            "#mail_content",
            dataToSend);

        return false;

    });
}

function onLoadPermissionContent(year_id, permission_entity_id) {
    let dataToSend = {};
    dataToSend['year_id'] = year_id;
    dataToSend['permission_entity_id'] = permission_entity_id;
    ajaxPostAsyncFalse('services/perms/getPermsByYearId', '#permissions_content', dataToSend);
}

function onLoadMailContent(year_id, mail_entity_id) {
    let dataToSend = {};
    dataToSend['year_id'] = year_id;
    dataToSend['mail_entity_id'] = mail_entity_id;
    ajaxPostAsyncFalse('services/mail/getMailsByYearId', '#mail_content', dataToSend);
}

function loadPermissionContentOnClick() {
    $(".loadPermissionContent").bind("click", function () {

        let year_id = $(this).attr("data-year_id");
        let target = $(this).attr("data-target");
        let permission_entity_id = document.getElementById('permission_entity_id').value;

        let dataToSend = {};
        dataToSend['year_id'] = year_id;
        dataToSend['permission_entity_id'] = permission_entity_id;

        ajaxPostAsyncFalse("services/perms/getPermsByYearId",
            target,
            dataToSend);

        return false;

    });
}

function loadMailContentOnClick() {
    $(".loadMailContent").bind("click", function () {

        let year_id = $(this).attr("data-year_id");
        let target = $(this).attr("data-target");
        let mail_entity_id = document.getElementById('mail_entity_id').value;

        let dataToSend = {};
        dataToSend['year_id'] = year_id;
        dataToSend['mail_entity_id'] = mail_entity_id;
        console.log(dataToSend);
        ajaxPostAsyncFalse("services/mail/getMailsByYearId",
            target,
            dataToSend);

        return false;

    });
}

function getPermission() {
    $(".getPermission").bind("click", function () {

        let form = $('#createPermission');
        let dataToSend = getFormData(form);
        let fields = document.querySelectorAll('.field');
        let error = document.querySelector('.error');
        for (let i = 0; i < fields.length; i++) {
            if (!fields[i].value) {
                error.innerHTML = "Введите все поля!";
                return false;
            }else {
                error.innerHTML = "";
            }
        }
        let modalOnLoad = document.getElementById('permissionModal');
        modalOnLoad.style.display = "none";

        ajaxPostAsyncFalse("services/perms/createPermission",
            "#perms_content_load",
            dataToSend);

        return false;

    });
}
function getMail() {
    $(".getMail").bind("click", function () {

        let form = $('#createMail');
        let dataToSend = getFormData(form);
        console.log(dataToSend);
        let error = document.querySelector('.error');
            if (dataToSend['newMailDescription'] == "") {
                error.innerHTML = "Введите все поля!";
                return false;
            }else {
                error.innerHTML = "";
            }

        let modalOnLoad = document.getElementById('mailModal');
        modalOnLoad.style.display = "none";

        ajaxPostAsyncFalse("services/mail/createMail",
            "#mail_content_load",
            dataToSend);

        return false;

    });
}

function getSpecialMail() {
    $(".special_getMail").bind("click", function () {

        let form = $('#createSpecialMail');
        let dataToSend = getFormData(form);
        let error = document.getElementById("specialMailError");

        if (dataToSend['specialMailDescription'] == "") {
            error.innerHTML = "Введите все поля!";
            return false;
        }else {
            error.innerHTML = "";
        }

        if(checkMailAlreadyExist(dataToSend['mailId'], dataToSend['mail_entity_id'] )){
            error.innerHTML = "Вставить после данного номера нельзя!";
            return false;
        } else {
            error.innerHTML = "";
        }

        let modalOnLoad = document.getElementById('mailSpecialModal');
        modalOnLoad.style.display = "none";

        ajaxPostAsyncFalse("services/mail/createSpecialMail",
            "#mail_content_load",
            dataToSend);

        return false;

    });
}

function checkMailAlreadyExist(mail_id, mail_entity_id) {
    let checkResult = true;
    $.ajax ({
        type: "POST",
        async: false,
        url: 'services/mail/checkMailAlreadyExist',
        dataType: "json",
        data: {
            'mail_id':mail_id,
            'mail_entity_id':mail_entity_id
        },
        beforeSend: function(data){

        },
        success: function(data){
            checkResult = data;
        }
    });
    return checkResult;
}