/** Modal */
(function (root, factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
      // AMD
      define(['jquery'], factory);
    } else if (typeof exports === 'object') {
      // Node, CommonJS-like
      module.exports = factory(require('jquery'));
    } else {
      // Browser globals (root is window)
      root.modal = factory(root.jQuery);
    }
}(this, function init($, undefined) {
    return {
        nameModal: $("#boxe_main"),
        mask: $("#mask_main"),
        title: $("#boxe_main #title"),
        message: $("#boxe_main #message"),
        content: $("#boxe_main #content"),
        buttons: $("#boxe_main #buttons"),
        dialogue: $("#div_dialogue"),
        closeEnable: function() {
            var that = this;
            $("#boxe_main .close, #mask_main").on("click", function() {
                that.nameModal.hide();
                that.mask.fadeOut();
                that.content.find("div#content").remove();
                that.dialogue.hide();
                $("#mask_main").css("z-index","2");
            });
        },
        /** @var title, message, content */
        show: function(params) {
            this.closeEnable();
            if(params.title && params.title !== null) this.title.html(params.title).show();
            if(params.message && params.message !== null) this.message.html(params.message).show();
            if(params.content && params.content !== null) this.content.load(params.content).show();
            if(params.buttons && params.buttons !== null) this.buttons.html(params.buttons).show();
            this.mask.fadeIn();
            this.nameModal.fadeIn().css({
                display: "flex",
                top: 0
            });
            return this;
        },
        hide: function() {
            $("#boxe_main, #div_dialogue").hide();
        },
        confirm: function(params) {
            this.dialogue.find("#title").html(params.title).show();
            this.dialogue.find("#message").html(params.message).show();
            this.dialogue.find("#buttons").html("<div align='right'><button class='button cancel' value='0'>Cancela</button><button class='button error' style='margin-left: 3px' value='1'>Confirma</button></div>");
            this.dialogue.fadeIn().css({
                display: "flex"
            });
            $("#mask_main").css({
                "z-index": "4"
            }).show();

            return this.dialogue.find("button").on("click", function() {
                if($(this).val() == 0) {
                    $("#div_dialogue").hide();
                    $("#mask_main").css("z-index","2");
                } else {
                    return $(this).val();
                }
            });
        },
        open: function(params) {
            this.closeEnable();
            if(params.title && params.title !== null) this.title.html(params.title).show();
            if(params.message && params.message !== null) this.message.html(params.message).show();
            if(params.content && params.content !== null) this.content.load(params.content).show();
            this.mask.fadeIn();
            this.nameModal.fadeIn().css({
                display: "flex",
                top: 0
            });
            this.complete();
            return this;
        },
        on: function() {
            this.buttons.find("button").on("click", function() {
                alert("clicou");
            });
        },
        complete: function(params) {
            if(typeof(params) !== "undefined") {
                this.content.on("submit", function(e) {
                    e.preventDefault();
                });
                this.buttons.html(params.buttons).show();
                params.callback();
            }
            return this;
        },
        close: function() {
            $("#mask_main").trigger("click");
        }
    };
}));

/** loading */
var loading = {
    source: "../themes/img/loading.png",
    height: "100%",
    width: "100%",
    show: function(params) {
        var text = (params.text ? params.text : null) ;
        var source = (params.source ? params.source : this.source);
        $("#mask_main").css("z-index","3");
        $("section.loading")
            .css({
                display: "block"
            });
        $(".text-loading").html(text + "...").css({
            display: "block"
        });
        return this;
    },
    hide: function() {
        $(".loading, .text-loading").fadeOut();
        $("#mask_main").css("z-index","1");
    }
};

/** alert message */
var alertLatch = function(text, background) {
    var box = $("#alert").html(text).css("display","none");
    var marginRight = box.width() + 40;
    var css = box.css({
                display: "block",
                overflow: "hidden",
                background: background,
                "margin-right": -marginRight
            });
    css.animate({
            "margin-right": "0"
        }, 1000, function() {
            setTimeout(function() {
                $("#alert").animate({
                    "margin-right": -marginRight
                });
            }, 5000);
        });
    $("#alert").on("click", function() {
        css.animate({
                "margin-right": "0"
            }, 1000, function() {
                setTimeout(function() {
                    $("#alert").animate({
                        "margin-right": -marginRight
                    });
                }, 3000);
            });
    });
};

/** save configuration */
var saveForm = function(act, action, connectionName = null, url = "../Suporte/Ajax/save.php") {
    //$("#boxe_main").on("submit", function(e) {
        //e.preventDefault();
        let success;
        let data = $("#boxe_main form").serialize();
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            async: false,
            data: {
                act: act,
                action: action,
                connectionName: connectionName,
                data: data
            },
            beforeSend: function() {
                loading.show({
                    text: "salvando"
                });
            },
            success: function(response) {
                let background;// = "var(--cor-warning)";
                if(response.indexOf("success") !== -1) {
                    background = "var(--cor-success)";
                    success = true;
                } else {
                    background = "var(--cor-warning)";
                    success = false;
                }
                alertLatch(response, background);
            },
            error: function(error) {
                alertLatch(error, "var(--cor-danger)");
            },
            complete: function() {
                loading.hide();
            }
        });
        if(success) {
            $(".content").load("config", function() {
                callScript("config");
                $("#boxe_main, #mask_main").hide();
            });
        }
        return success;
    //});
};

/**
* @params array screens(access), element, icon One, icon two
*/
var insertCheck = function(screens, element, optionGreen, optionRed) {
    element.find("i").removeClass();
    element.each(function() {
        if(screens == " *" || screens.indexOf($(this).text().trim()) !== -1) {
            $(this).find("i").addClass(optionGreen)
                .css("color","green");
        } else {
            $(this).find("i").addClass(optionRed)
                .css("color","red");
        }
    });
};

/** @return object */
var getScreenAccess = function(element, check, groupName) {
    var access = "";
    element.each(function() {
        if($(this).find("i").hasClass(check)) {
            access += (access.length === 0 ? $(this).text() : "," + $(this).text());
        }
    });
    return {
        access: access,
        name: groupName
    };
};

/**
* @return bool
* @params element, icon One, icon Two
*/
var changeCheck = function(element, optionGreen, optionRed) {
    var currentOption = element.attr("class");
    element.removeClass();
    if(currentOption === optionRed) {
        element.addClass(optionGreen)
            .css("color","green");
    } else {
        element.addClass(optionRed)
            .css("color","red");
    }
    return true;
};

var saveData = function(link, data, msg = "Saving") {
    var success = false;
    $.ajax({
        url: link,
        type: "POST",
        dataType: "JSON",
        context: msg,
        async: false,
        data: data,
        beforeSend: function() {
            $("#mask_main").show();
            loading.show({
                text: msg
            });
        },
        success: function(response) {
            var background;
            if(response.indexOf("success") !== -1) {
                background = "var(--cor-success)";
                success = true;
            } else if(response.indexOf("danger") !== -1) {
                background = "var(--cor-danger)";
            } else {
                background = "var(--cor-warning";
            }
            alertLatch(response, background);
            if(success)$("#mask_main").hide();
            loading.hide();
            $("#mask_main").hide();
        },
        error: function(error) {
            alertLatch("Could not save change", "var(--cor-danger)");
            setTimeout(function() {
                loading.hide();
                $("#mask_main").fadeOut();
            }, setTime);
        },
        complete: function(data) {}
    });
    return success;
};

var sleep = function(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
};

var valReal = function(val) {
    return parseFloat(val.replace(".","").replace(",","."));
}

var moeda = function(val) {
    return parseFloat(val).toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}
