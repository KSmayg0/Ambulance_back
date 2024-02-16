function deleteBtn() {

    $(".deleteDepartment").bind("click", function () {

        let url = "/department_management/delete";
        let departmentId = $(this).attr("data-id");
        let target = '#ajax-content-load';

        ajaxPost(url, target, departmentId);

        return false;

    });

}

function createBtn() {

    $(".saveBtn").bind("click", function () {

        let url = "/department_management/create";
        let target = '#ajax-content-load';
        let departmentName = document.getElementById("depName").value;

        ajaxPost(url, target, departmentName)

        return false;

    });

}

function updateBtn() {

    $(".updateBtn").bind("click", function () {

        let departmentNameUpdate = document.getElementById("depNameEdit").value;
        let id = document.getElementById("depIdEdit").value;

        let url = "/department_management/update";
        let target = '#ajax-content-load';

        let dataToSend = {};
        dataToSend['id'] = id;
        dataToSend['name'] = departmentNameUpdate;

        ajaxPost(url, target, dataToSend);

        return false;

    });

}

function userRoomsSelectLoad() {

    $("#floor").change(function () {

        let url = "/getroomsforselect";
        let floor_id = $(this).find(":selected").data("id");
        let target = '#room-data-load';

        ajaxPostAsyncFalse(url, target, floor_id);

        return false;

    });

}

function userRoomsSelectLoadForUpdate(floor_id, room_id) {

    let url = "/getroomsforselectUpdate";
    let target = "#roomUpdate-data-load";

    let dataToSend = {};
    dataToSend['floor_id'] = floor_id;
    dataToSend['floor_change'] = 0;

    ajaxPostAsyncFalse(url, target, dataToSend);

    $("#roomUpdate").val(room_id);

    $("#floorUpdate").change(function () {

        let floor_select_id = $(this).find(":selected").data("id");

        let dataToSendOnChange = {};
        dataToSendOnChange['floor_id'] = floor_select_id;
        dataToSendOnChange['floor_change'] = 1;

        ajaxPostAsyncFalse(url, target, dataToSendOnChange);

        return false;

    });

}

function userDepartmentsSelectLoad() {

    $('#room').change(function () {

        let url = "/getdepartmentsforselect";
        let target = '#department-data-load';
        let room_id = $(this).find(":selected").data("id");

        ajaxPostAsyncFalse(url, target, room_id);

        return false;

    });

}

function departmentSelectLoadForUpdate(department_id, room_id) {

    let url = "/getdepartmentsforselectUpdate";
    let target = '#departmentUpdate-data-load';

    ajaxPostAsyncFalse(url, target, room_id);

    $("#departmentUpdate").val(department_id);

}

function departmentSelectLoadForUpdateFloorUpdate(room_id) {

    let url = "/getdepartmentsforselectUpdate";
    let target = '#departmentUpdate-data-load';

    ajaxPostAsyncFalse(url, target, room_id);

    departmentSelectLoadForUpdateChangeRoom();

}

function departmentSelectLoadForUpdateChangeRoom() {

    $("#roomUpdate").change(function () {

        let url = "/getdepartmentsforselectUpdate";
        let target = '#departmentUpdate-data-load';
        let room_select_id = $(this).find(":selected").data("id");

        ajaxPostAsyncFalse(url, target, room_select_id);

        return false;

    });

}

function userDepartmentsSelectLoadOnStartUp() {

    let url = "/getdepartmentsforselect";
    let target = '#department-data-load';
    let room_id = $("#room").find(":selected").data("id");

    ajaxPostAsyncFalse(url, target, room_id);

    return false;

}

function createNewUser() {

    $(".saveBtn").bind("click", function () {

        let form = $('#createNewUserForm');
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

        let url = "/user_management/create";
        let target = "#ajax-content-load";

        ajaxPost(url, target, dataToSend);

        return false;

    });

}

function updateUser() {
    $(".updateBtn").bind("click", function () {

        let form = $('#updateUserForm');
        let dataToSend = getFormData(form);

        let fields = document.querySelectorAll('.field-update');
        let error = document.querySelector('.error-update');
        for (let i = 0; i < fields.length; i++) {
            if (!fields[i].value) {
                error.innerHTML = "Введите все поля!";
                return false;
            }else {
                error.innerHTML = "";
            }
        }

        // console.log(dataToSend);
        // return false;
        ajaxPost("/user_management/update",
            "#ajax-content-load",
            dataToSend);

        return false;

    });

}

function updateModalUser() {

    $(".updateUser").bind("click", function () {

        let user_id = $(this).attr("data-id");

        ajaxPost("/user_management/updateModalLoad",
            "#modal-update-ajax-load",
            user_id);

        let modalUserUpdate = document.getElementById('userModalUpdate');
        modalUserUpdate.style.display = "block";



        window.onclick = function (event) {
            if (event.target == modalUserUpdate) {
                modalUserUpdate.style.display = "none";
            }
        }

        return false;

    });

}

function deleteUser() {

    $(".deleteUser").bind("click", function () {
        //Ищем актуальную строку в таблице.
        //Получаем из нее данные - активен ли пользователь
        let getActualRowActive = $(this).parent().parent().children().find('span');
        //Получаем элемент в который будет выводиться предупреждение в актуальной строке
        let getActualRowAlertArea = $(this).parent().parent().children().find('#delete-alert');
        //Первоначально очищается все предупреждения во всей таблице циклом
        let arrayAlertArea = $(this).parent().parent().parent().children().find('#delete-alert');
        arrayAlertArea.each(function (elem) {
            $(this).html("")
        });
        //Далее основная логика
        //Если Active = 1 - удалять нельзя. Показываем предупреждение.
        if (getActualRowActive.text() == 1) {
            getActualRowAlertArea.html("<span class=\"badge bg-danger\">Нельзя удалить <br> активного пользователя!</span>");
            return false;
        }
        //Если Active = 0 - удалять можно. Удаляем.
        let user_id = $(this).attr("data-id");

        ajaxPost("/user_management/delete",
            "#ajax-content-load",
            user_id);

        return false;

    });

}

function editFloorModalOpen() {
    $(".editFloor").bind("click", function () {
        let floor_id = $(this).attr("data-id");
        let modal = document.getElementById('roomModal');
        let closeBtn = document.getElementById("roomСloseBtn");
        modal.style.display = "block";

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        console.log(floor_id);

        $(".roomSaveBtn").bind("click", function () {
            let newRoomName = document.getElementById("roomName").value;

            if (newRoomName == "") {
                return false;
            }

            let dataToSend = {};
            dataToSend['newRoomName'] = newRoomName;
            dataToSend['floor_id'] = floor_id;

            ajaxPostAsyncFalse("/office/createNewRoom", '#ajax-content-load', dataToSend);

        });



    });
    return false;
}

function editRoomModalOpen() {
    $(".editRoom").bind("click", function () {

        let room_id = $(this).attr("data-id");
        let modal = document.getElementById('roomModalUpdate');
        ajaxPostAsyncFalse("/office/editRoomModal", '#modal-update-ajax-load', room_id);
        modal.style.display = "block";

    });
    return false;
}

function deleteRoomFromFloor() {
    $(".deleteRoom").bind("click", function () {
        let room_id = $(this).attr("data-id");

        if ($(this).parent().children().is('.depart_check')) {
            console.log($(this).parent().children().is('.depart_check'));
        } else {
            ajaxPostAsyncFalse("/office/deleteRoom", '#ajax-content-load', room_id);
        }

    });
    return false;
}

function addDepartmentToRoom() {
    $(".updateRoomInStructureBtn").bind("click", function () {
        let room_id = document.getElementById("room_id_structure").value;
        let department_id = $("#department").find(":selected").data("id");

        let dataToSend = {};
        dataToSend['room_id'] = room_id;
        dataToSend['department_id'] = department_id;

        console.log(room_id);
        console.log(department_id);

        ajaxPost("/office/addDepartmentToRoom", '#ajax-content-load', dataToSend)

    });
    return false;
}

function deleteDepartmentFromRoom() {
    $(".deleteDepartmentFromRoom").bind("click", function () {
        let room_id = document.getElementById("room_id_structure").value;
        let department_id = $(this).attr("data-id");

        let dataToSend = {};
        dataToSend['room_id'] = room_id;
        dataToSend['department_id'] = department_id;

        console.log(room_id);
        console.log(department_id);

        ajaxPost("/office/deleteDepartmentFromRoom", '#ajax-content-load', dataToSend)

    });
    return false;
}
