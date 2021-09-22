/** Constantes */
 const CONF_DATATABLE_ZERORECORDS = "Desculpe - Nada encontrado";
 const CONF_DATATABLE_INFOEMPTY =  "Nenhum dado disponível";
 const CONF_DATATABLE_SEARCH =  "Filtrar";
 const CONF_DATATABLE_PROCESSING = "<div class='progress'><div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='background: #003366' ></div></div><span class='fa-blink'>carregando...</span>";
 const CONF_DATATABLE_LOADING = "<div class='lendoDataTable lendo'><img src='../web/img/lendo.gif' alt='lendo' height='180' /></div>";
 const CONF_DATATABLE_INFOFILTERED =  "(filtrado _MAX_ linha(s))";
 const CONF_DATATABLE_INFO =  "Linhas de _START_ a _END_ do total de _TOTAL_ linhas";
 const CONF_DATATABLE_SLENGTHMENU = "Exibindo _MENU_ linhas por página";
 const CONF_DATATABLE_DECIMAL = ",";
 const CONF_DATATABLE_THOUSANDS = ".";

/** Datatable default */
$.extend( true, $.fn.dataTable.defaults, {
    searching: false,
    ordering: false,
    info: false,
    paging: false
});

/** variables */
var setTime = 500;

$(function($) {
    if(typeof(initializing) !== "undefined") {
        $("#boxe_main, #mask_main").show();
        $("#boxe_main").load("config", function() {
            $("#boxe_main #config-form").append("<button class='button-style mt-3' style='margin-top: 10px' >Save</button>");
            //$("#form-token").find("[name=Senha]").focus();
        }).on("submit", function(e) {
            e.preventDefault();
            var url = "src/Support/Ajax/save.php";
            var dataSet = $("#config-form").serializeArray();
            dataSet.push(
                {
                    name: "act",
                    value: "config"
                }
            );
            let msg = saveForm("connection","add", "null", url);
            if(msg) {
                window.location.reload();
            }
        }).css({
            top: "0",
            "padding": "30px"
        });
    }
    /** authentication */
    $("main form.form-signin").on("submit", function(e) {
        e.preventDefault();
        $("main button").html("<i class='fa fa-sync-alt schedule'></i>");
        var data = $("form.form-signin").serialize();
        var url = "src/public/main.php";

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                if(response === 1) {
                    $(location).attr("href","");
                } else if(response === 2) {
                    var link = "src/Support/Ajax/save.php";
                    var login = $("main form [name=login]").val();
                    var lkToken = "token";
                    $("#boxe_main, #mask_main").show();
                    $("#boxe_main").load(lkToken, function() {
                            $("#form-token").find("[name=Senha]").focus();
                        }).on("submit", function(e) {
                            e.preventDefault();
                            var dataSet = $("form#form-token").serializeArray();
                            dataSet.push(
                                {
                                    name: "act",
                                    value: "login"
                                },
                                {
                                    name: "action",
                                    value: "change"
                                },
                                {
                                    name: "Logon",
                                    value: login
                                }
                            );
                            //console.log(dataSet);
                            if(dataSet[1]["value"] !== dataSet[2]["value"]) {
                                alertLatch("The passwords are different", "var(--cor-warning)");
                            } else if(dataSet[1]["value"] === "") {
                                alertLatch("Invalid blank password", "var(--cor-warning)");
                            } else {
                                if(saveData(link, dataSet, "Salvando")) {
                                    setTimeout(function() {
                                        $("#boxe_main, #mask_main").fadeOut();
                                    }, setTime);
                                }
                            }
                        }).css({
                            top: "20%",
                            "padding": "30px"
                        });
                } else {
                    alertLatch(response, "var(--cor-warning)");
                }
            },
            error: function(error) {
                //alertLatch(error["responseText"], "var(--cor-danger)");
                alertLatch("Please recharge the page", "var(--cor-danger)");
            },
            complete: function(response) {
                setTimeout(function(){
                    $("main button").text("Entrar");
                }, 1300);
            }
        });
    });
    $(document).on("keyup", function(e) {
        e.preventDefault();
        if(e.keyCode === 27) {
            $("#boxe_main, #mask_main, #div_dialogue, #boxe2_main, #mask2_main").hide();
            $("#mask_main").css("z-index","2");
        }
    });
});
