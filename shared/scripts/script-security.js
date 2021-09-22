/** functions */
function checkGroup() {
    var groupName = $(".group fieldset p.active").text();
    $(".screen legend span").text("Grupo: " + groupName).css("float","right");

    if(groupName) {
        /** Mark accessible screens */
        var url = "group/load/" + groupName;
        var screens = security(groupName, url);
        if(screens.success) {
            insertCheck(screens.access, $(".screen span"), "fa fa-check", "fa fa-times");
        }
    }
}

/** Read screen access */
function security(groupName, url = null ) {
    var resp;
    $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        data: { groupName: groupName },
        async: false,
        success: function(response) {
            response.success = true;
            resp = response;
        },
        error: function(error) {
            error.seccces = false;
            resp = error;
        }
    });
    return resp;
};

function scriptSecurity() {
    checkGroup();
    $(".btnAction").on("click", function() {
        var groupName = $(this).text();
        $(".screen legend span").html("Grupo: " + groupName).css("float","right");

        $(".btnAction").removeClass("active");
        $(this).addClass("active");

        /** Mark accessible screens */
        var url = "group/load/" + groupName;
        var screens = security(groupName, url);
        if(screens.success) {
            insertCheck(screens.access, $(".screen span"), "fa fa-check", "fa fa-times");
        }
    });

    var change;
    /* change check */
    $(".screen span").on("click", function() {
        if(!$(".btnAction").hasClass("active")) return;
        change = changeCheck($(this).find("i"), "fa fa-check", "fa fa-times");
    });

    $(".save, .cancel").on("click", function(e) {
        e.preventDefault();
        var btnName = this["innerText"];
        if(btnName === "Adicionar Grupo") {
            var url = "group/register";
            modal.show({
                title: "Cadastro de Grupo",
                message: null,
                content: url
            }).complete({
                buttons: "<button class='button save' >Save</button>",
                callback:function() {
                    $(buttons).on("click", function() {
                        if($(this).text() === "Save") {
                            let newGroup = $("[name=group-name]").val();
                            if(newGroup === "") {
                                return alertLatch("No name has been inserted", "var(--cor-warning)");
                            }
                            var data = {
                                name: newGroup
                            };
                            if(saveData("group/save", data)) {
                                $("#boxe_main").hide();
                                $(".content").load("shield", function() {
                                    scriptSecurity();
                                });
                            }
                        }
                    });
                }
            });
        } else if(btnName === "Excluir Grupo") {
            if(!$(".btnAction").hasClass("active")) return;
            var groupName = $(".group .active").text();
            var url = "group/delete/" + groupName;
            let message = "Deseja realmente excluir o grupo <span style='color:red; font-size: 1.1rem; margin-left: 5px'>" + groupName + "</span>";
            let conf = modal.confirm({
                title: "Exclusão de gropo de acesso",
                message
            });
            conf.on("click", function() {
                if($(this).val() == 1) {
                    data = {};
                    data.name = groupName;
                    saveData(url, data, "Excluding");
                    $(".btnAction.active").remove();
                }
                $("#div_dialogue, #mask_main").hide();
            });
        } else if(btnName === "Gravar") { /** parte ok */
            if(!$(".btnAction").hasClass("active") || typeof(change) === "undefined") return;
            var groupName = $(".group .active").text();
            var security = getScreenAccess($(".screen span"), "fa fa-check", groupName);
            var url = "group/update";
            saveData(url, security);
        }
    });
}
