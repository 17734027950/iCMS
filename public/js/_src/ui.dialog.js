define("ui.dialog", function(require) {
    var artdialog = require("artdialog");

    return function(options, callback) {
        var defaults = {
                id: 'iCMS-DIALOG',
                title: 'iCMS - 提示信息',
                // width:360,height:150,
                className: 'iCMS_UI_DIALOG',
                backdropBackground: '#333',
                backdropOpacity: 0.5,
                fixed: true,
                autofocus: false,
                quickClose: true,
                lock: true,
                time: null,
                label: 'success',
                icon: 'check',
                api: false,
                width:360,height:120
            },
            timeOutID = null,
            opts = $.extend(defaults, iCMS.CONFIG.DIALOG, options);

        if (opts.follow) {
            opts.fixed = false;
            opts.lock = false;
            opts.skin = 'iCMS_tooltip_popup'
            opts.className = 'ui-popup';
            opts.backdropOpacity = 0;
        }
        var content = opts.content;
        //console.log(typeof content);
        if (content instanceof jQuery) {
            opts.content = content;
        } else if (typeof content === "string") {
            opts.content = __msg(content);
        }
        opts.onclose = function() {
            __callback('close');
        };
        opts.onbeforeremove = function() {
            __callback('beforeremove');
        };
        opts.onremove = function() {
            __callback('remove');
        };
        var d = artdialog(opts);
        if (opts.lock) {
            d.showModal();
            // $(d.backdrop).addClass("ui-popup-overlay").click(function(){
            //     d.close().remove();
            // })
        } else {
            d.show(opts.follow);
            if (opts.follow) {
                //$(d.backdrop).remove();
                // $("body").bind("click",function(){
                //     d.close().remove();
                // })
            }
            //$(d.backdrop).css("opacity","0");
        }
        if (opts.time) {
            timeOutID = window.setTimeout(function() {
                d.destroy();
            }, opts.time);
        }
        d.destroy = function() {
            d.close().remove();
        }

        function __callback(type) {
            window.clearTimeout(timeOutID);
            if (typeof(callback) === "function") {
                callback(type);
            }
        }

        function __msg(content) {
            return '<table class=\"ui-dialog-table\" align=\"center\"><tr><td valign=\"middle\">' +
            '<div class=\"iPHP-msg\">' +
            '<span class=\"label label-' + opts.label + '\">' +
            '<i class=\"fa fa-' + opts.icon + '\"></i> ' +
            content +
            '</span></div>' +
            '</td></tr></table>';
        }
        return d;
    }
});
