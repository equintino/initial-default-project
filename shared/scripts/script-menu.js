function identif(page, logged="Nenhum usuário logado") {
    switch(page) {
        case "home":
            return "<i>Usuário:</i> " + logged;
        case "user":
            return "GERENCIAMENTO DE LOGINS";
        case "shield":
            return "TELAS DE ACESSO";
        case "config":
            return "CONFIGURAÇÃO DO ACESSO AO BANCO DE DADOS";
        default:
            return "CADASTRO DE " + page.toUpperCase();
    }
}

function callScript(name) {
    var registerCall = ["cliente","fornecedor","transportadora"];
    if(registerCall.indexOf(name) !== -1) {
        scriptRegister();
    }
    switch(name) {
        case "user":
            scriptUser();
            break;
        case "shield":
            scriptSecurity();
            break;
        case "config":
            scriptConfig();
            break;
    }
}

$(function() {
    $("#topHeader ul li a").on("click", function(e) {
        e.preventDefault();
        var name = $(this).attr("data-id");
        var li = $(this).closest("li");

        $("#topHeader ul li").removeClass("active");
        li.addClass("active");
        if(name !== "cadastro" && name !== "gerenciamento") {
            $(".loading, #mask_main").show();
            $(".identification").html(identif(name, logged));

            $(".content").load(name, function() {
                callScript(name);
                $(".loading, #mask_main").hide();
            });
        }
    });
    $("#topHeader ul li [data-id=home]").trigger("click");
});
