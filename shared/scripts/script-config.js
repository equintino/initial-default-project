function scriptConfig() {
    /** Edition of the configuration */
    $("#config .buttons .button").on("click", function(e) {
        e.preventDefault();
        var url = "config/save";
        if($(this).text() === "Adicionar") {
            var content = "config/register";
            modal.show({
                title: "Preencha os dados abaixo:",
                content: content,
            }).complete({
                buttons: "<button class='button save' >Save</button>",
                callback:function() {
                    $(buttons).on("click", function() {
                        if($(this).text() === "Save") {
                            saveForm("connection","add", "null", url);
                        }
                    });
                }
            });
        }
    });
    $("#tab-conf tbody .edition, #tab-conf tbody .delete").on("click", function() {
        var connectionName;
        var title;
        var message;
        var content;
        var tr = $(this).closest("tr");
        connectionName = tr.find("td").eq(1).text();
        if(connectionName.indexOf("*") !== -1) {
            alertLatch("This connection is active", "var(--cor-warning)");
            return false;
        }

        if($(this).hasClass("edition")) {
            var url = "config/update";
            title = "Modo de Edição de (" + connectionName + ")";
            message = null;
            content = "config/edit/" + connectionName;
            modal.show({
                title: title,
                message: message,
                content: content
            }).complete({
                buttons: "<button class='button save' >Save</button>",
                callback:function() {
                    $(buttons).on("click", function() {
                        if($(this).text() === "Save") {
                            let msg = saveForm("connection", "edit", connectionName, url);
                            alertLatch(msg, "var(--cor-success)");
                        }
                    });
                }
            });
        } else if($(this).hasClass("delete")) {
            title = "Modo de Exclusão de (" + connectionName + ")";
            message = "VOCÊ ESTÁ PRESTE A EXCLUIR A CONFIGURAÇÃO: <b style='color: red; margin-left: 5px'>(" + connectionName + ")</b>";
            let conf = modal.confirm({
                title,
                message
            });
            conf.on("click", function() {
                if($(this).val() == 1) {
                    var link = "config/delete/" + connectionName;
                    var data = {
                        connectionName: connectionName,
                        act: "connection",
                        action: "delete"
                    };
                    if(saveData(link, data, "Excluindo")) {
                        $(".content").load("config", function() {
                            callScript("config");
                            $("#div_dialogue").hide();
                        });
                    }
                }
                $("#mask_main").hide();
            });
        } else {
            return false;
        }
    });
}
