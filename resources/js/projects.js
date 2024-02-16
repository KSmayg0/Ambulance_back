function getRequestToEdit(object_id, target) {
    console.log("start getRequestToEdit")
    let url = "/requests/getRequestToEdit";
    ajaxPostAsyncFalse(url, target, object_id);
    return false;
}
function moveFromArchiveToWork(object_id, target) {
    let url = "/requests/moveFromArchiveToWork";
    ajaxPostAsyncFalse(url, target, object_id);
    return false;
}
function moveRequestToArchive() {
    $(".moveRequestToArchive").bind("click", function () {

        let request_id = document.getElementById('request_id').value;
        let modalOnLoad = document.getElementById('requestModal');
        modalOnLoad.style.display = "none";

        ajaxPost("requests/deleteRequest",
            "#ajaxProjects",
            request_id);

        return false;

    });
}
function getRequestToWork(request_id, worker_id) {

    let url = "/requests/getRequest";
    let dataToSend = {};
    dataToSend['request_id'] = request_id;
    dataToSend['worker_id'] = worker_id;

    ajaxPostAsyncFalse(url,
        "#ajaxProjects",
        dataToSend);

    return false;
}
function createNewProject() {
    $(".createProject").bind("click", function () {

        let form = $('#createObjectForm');
        let dataToSend = getFormData(form);
        let error = document.getElementById('error');

        console.log(dataToSend);
        if (dataToSend['object_name'] == "") {
            error.innerHTML = "Введите наименование объекта!";
            return false;
        }else {
            error.innerHTML = "";
        }

        if (checkObjNameEmpty(dataToSend['object_name'])) {
            checkKeyAndInsertNullIfEmpty('datePEnd', dataToSend);
            checkKeyAndInsertNullIfEmpty('dateEEnd', dataToSend);
            checkKeyAndInsertNullIfEmpty('dateWEnd', dataToSend);

            let modalOnLoad = document.getElementById('createObject');
            modalOnLoad.style.display = "none";

            ajaxPost("projects/createObject",
                "#ajaxProjects",
                dataToSend);
        } else {
            error.innerHTML = "Такой объект уже существует!";
        }

        return false;

    });
}

function checkObjNameEmpty(object_name) {
    let result234 = false;
    $.ajax ({
        type: "POST",
        async: false,
        url: 'projects/checkObject',
        dataType: "json",
        data: {
            'object_name':object_name
        },
        beforeSend: function(data){

        },
        success: function(data){
            result234 = data;
        }
    });
    return result234;
}

function updateRequest() {
    $(".updateRequest").bind("click", function () {

        let form = $('#updateObject');
        let dataToSend = getFormData(form);
        console.log(typeof(dataToSend));
        // костыли для статического установления null значений
        // dataToSend.worker_id = "";
        // dataToSend["status_id"] = 1;

        let modalOnLoad = document.getElementById('requestModal');
        modalOnLoad.style.display = "none";

        ajaxPost("/requests/updateRequest",
            "#ajaxProjects",
            dataToSend);

        return false;

    });
}
function createDate() {
    let datePStart = document.getElementById('datePStart');
    let dateEStart = document.getElementById('dateEStart');
    let dateWStart = document.getElementById('dateWStart');
    //console.log(dataPEnd);
    datePStart.addEventListener('change', function (e) {
        let dataPEnd = document.querySelector(".datePEnd");
        let dateStart = e.target.value;
        datePStart = new Date(dateStart);
        let minDate;

            if (datePStart.value != "") {
                if (dateStart != "") {
                    minDate = datePStart.setDate(datePStart.getDate() + 1);
                    minDate = new Date(minDate);
                    //console.log(minDate);
                    let isoDate = minDate.toISOString();
                    isoDate = isoDate.substr(0, 10);

                    dataPEnd.removeAttribute("disabled");
                    dataPEnd.setAttribute("value", isoDate);
                    dataPEnd.setAttribute("min", isoDate);
                }else {
                    dataPEnd.setAttribute("disabled", "true");
                    dataPEnd.removeAttribute("value");
                }
            }
    });

    dateEStart.addEventListener('change', function (e) {
        let dateEEnd = document.querySelector(".dateEEnd");
        let dateStart = e.target.value;
        dateEStart = new Date(dateStart);
        let minDate;

        if (dateEStart.value != "") {
            if (dateStart != "") {
                minDate = dateEStart.setDate(dateEStart.getDate() + 1);
                minDate = new Date(minDate);
                //console.log(minDate);
                let isoDate = minDate.toISOString();
                isoDate = isoDate.substr(0, 10);

                dateEEnd.removeAttribute("disabled");
                dateEEnd.setAttribute("value", isoDate);
                dateEEnd.setAttribute("min", isoDate);
            }else {
                dateEEnd.setAttribute("disabled", "true");
                dateEEnd.removeAttribute("value");
            }
        }
    });

    dateWStart.addEventListener('change', function (e) {
        let dateWEnd = document.querySelector(".dateWEnd");
        let dateStart = e.target.value;
        dateWStart = new Date(dateStart);
        let minDate;

        if (dateWStart.value != "") {
            if (dateStart != "") {
                minDate = dateWStart.setDate(dateWStart.getDate() + 1);
                minDate = new Date(minDate);
                //console.log(minDate);
                let isoDate = minDate.toISOString();
                isoDate = isoDate.substr(0, 10);

                dateWEnd.removeAttribute("disabled");
                dateWEnd.setAttribute("value", isoDate);
                dateWEnd.setAttribute("min", isoDate);
            }else {
                dateWEnd.setAttribute("disabled", "true");
                dateWEnd.removeAttribute("value");
            }
        }
    });

}
 function dateUpdateP() {
     $(".datePStartUpdate").bind("change", function () {
         if ($(this).val() != "") {
             let actualStartDate = new Date($(this).val());

             $("#datePEndUpdate").attr('min', (new Date(actualStartDate.setDate(actualStartDate.getDate() + 1))).toISOString().split('T')[0]);

             $("#datePEndUpdate").val((new Date(actualStartDate.setDate(actualStartDate.getDate())).toISOString().split('T')[0]));
         }
         if ($("#datePStartUpdate").val() == "") {
             $("#datePEndUpdate").removeAttr("min");
         }
     });
 }

 function dateUpdateE() {
     $(".dateEStartUpdate").bind("change", function () {
         if ($(this).val() != "") {

             let actualStartDate = new Date($(this).val());

             $("#dateEEndUpdate").attr('min', (new Date(actualStartDate.setDate(actualStartDate.getDate()  + 1))).toISOString().split('T')[0]);

             $("#dateEEndUpdate").val((new Date(actualStartDate.setDate(actualStartDate.getDate())).toISOString().split('T')[0]));
         }
         if ($("#dateEStartUpdate").val() == "") {
             $("#dateEEndUpdate").removeAttr("min");
         }
     });
 }

function dateUpdateW() {
    $(".dateWStartUpdate").bind("change", function () {
        if ($(this).val() != "") {

            let actualStartDate = new Date($(this).val());

            $("#dateWEndUpdate").attr('min', (new Date(actualStartDate.setDate(actualStartDate.getDate()  + 1))).toISOString().split('T')[0]);

            $("#dateWEndUpdate").val((new Date(actualStartDate.setDate(actualStartDate.getDate())).toISOString().split('T')[0]));
        }
        if ($("#dateWStartUpdate").val() == "") {
            $("#dateWEndUpdate").removeAttr("min");
        }
    });
}

function moveObjectToWork() {
    $(".moveObjectToWork").bind("click", function () {

        let object_id = document.getElementById('object_id').value;
        let modalOnLoad = document.getElementById('objectModal');
        modalOnLoad.style.display = "none";

        ajaxPost("projects/moveObjectToWork",
            "#ajaxProjects",
            object_id);

        return false;

    });
}


const checkKeyAndInsertNullIfEmpty = (key, array) => {
    if (key in array) {
        return true;
    } else {
        array[key]= "";
    }
    return false;
}