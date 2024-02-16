function deleteBtn1() {
    $(".deleteGroup").bind("click", function () {

        let url = "/group_management/delete";
        let groupId = $(this).attr("data-id");
        let target = '#ajax-content-load';

        ajaxPost(url, target, groupId);

        return false;
    });
}

function createBtn1() {
    $(".saveBtn").bind("click", function () {

        let url = "/group_management/create";
        let target = '#ajax-content-load';
        let groupName = document.getElementById("groupItemName").value;

        ajaxPost(url, target, groupName)

        return false;

    });
}

function updateBtn1() {
    $(".updateBtn").bind("click", function () {

        let groupNameUpdate = document.getElementById("groupNameEdit").value;
        let id = document.getElementById("groupIdEdit").value;

        let url = "/group_management/update";
        let target = '#ajax-content-load';

        let dataToSend = {};
        dataToSend['id'] = id;
        dataToSend['name'] = groupNameUpdate;

        ajaxPost(url, target, dataToSend);

        return false;

    });
}