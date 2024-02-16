var e = window.matchMedia("(min-width: 1604px)");

function sideMenu() {
    $(".ajaxMainLoad").unbind("click");
    $(".ajaxMainLoad").bind("click", function () {

        var par = $(this).parent();
        $target = $(this).attr("data-target");

        $(".sidebar-item").removeClass("active");
        par.addClass("active");

        $.ajax({
            url: $(this).attr("href"),
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
                //sideMenu();
            }
        });
        return false;
    });
}

function instructionsBtns() {
    $(".ajaxInstructionsLoad").unbind("click");
    $(".ajaxInstructionsLoad").bind("click", function () {

        $target = $(this).attr("data-target");
        $url = $(this).attr("data-url");

        $.ajax({
            url: $url,
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}

function projectsBtns() {
    $(".ajaxProjectsLoad").unbind("click");
    $(".ajaxProjectsLoad").bind("click", function () {

        $target = $(this).attr("data-target");
        $url = $(this).attr("data-url");

        $.ajax({
            url: $url,
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}

function navBarBtns() {
    $(".ajaxNavBar").unbind("click");
    $(".ajaxNavBar").bind("click", function () {

        $target = $(this).attr("data-target");
        $url = $(this).attr("href");

        $.ajax({
            url: $url,
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}

function removeRowspanItem(e) {
    var elementRowspan = ['th1','th2','th3','th4','th5','th6','th7','th8','th9'];
    if (e.matches) {
        for (var x of elementRowspan) {
            document.getElementById(x).setAttribute("rowspan", "2");
        }
    }else {
        for (var x of elementRowspan) {
            document.getElementById(x).removeAttribute("rowspan");
        }
    }
}

function contentLoad() {
    $(".instructionsContentLoad").unbind("click");
    $(".instructionsContentLoad").bind("click", function () {

        $target = $(this).attr("data-target");
        let id = $(this).attr("data-id");
        $.ajax({
            type:"POST",
            url: "/instructions-content",
            dataType: "html",
            data:{"id":id},
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}



function profileDataLoad() {
    $(".dataLoad").unbind("click");
    $(".dataLoad").bind("click", function () {

        $target = $(this).attr("data-target");
        let url = $(this).attr("data-url");
        $.ajax({
            url: url,
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}

function sendVpnConn() {
    $(".sendVpnCon").unbind("click");
    $(".sendVpnCon").bind("click", function () {

        $target = $(this).attr("data-target");
        let url = $(this).attr("data-url");
        $.ajax({
            url: url,
            dataType: "html",
            beforeSend: function (data) {
                $($target).html('');
            },
            success: function (data) {
                $($target).html(data);
            }
        });
        return false;
    });
}

function accordionItems() {
        $(".set > a").on("click", function(){
            if($(this).hasClass('active')){
                $(this).removeClass("active");
                $(this).siblings('.contentAcc').slideUp(200);
                $(".set > a span").removeClass("minus").addClass("plus");
            }else{
                $(".set > a span").removeClass("minus").addClass("plus");
                $(this).find("span").removeClass("plus").addClass("minus");
                $(".set > a").removeClass("active");
                $(this).addClass("active");
                $('.contentAcc').slideUp(200);
                $(this).siblings('.contentAcc').slideDown(200);
            }
        });
}