(function ($) {

    "use strict";

    $.fn.iframeTabs = function (options) {
        this.tab = '<li class="tab-menus"><a href="javascript:void(0);" data-toggle="tab"> <span class="title hidden-xs">tabName</span> <span class="close" href="#">×</span> </a> </li>';
        this.data = {
            pageStack: {
                "/report": "数据报告"
            }
        };

        this.iframes = $('#' + options.iframes);

        this.init = function () {
            const iframe = this.iframes.find("iframe");
            $(document).ready(function () {
                $(window).resize(function () {
                    const height = $(window).height() - 150;
                    iframe.height(height);
                });
                $(window).resize();
            });
        };

        this.linkHookFromTabs = function () {
            const that = this;
            $(document).find("a[iframe]").on('click', function () {
                const href = $(this).attr("href");
                that.openTabPage($(this).text(), href);
                return false;
            })
        };

        this.openTabPage = function (title, href) {
            title = title.trim();

            if (this.data.pageStack.hasOwnProperty(href)) {
                return;
            }
            const that = this;
            // 入
            this.data.pageStack[href] = title;
            // UI
            const tab = $(this.tab);
            var hashName = this.hashStr(href);
            tab.find("a").attr("href", "#" + hashName);
            tab.data("url", href);
            tab.find(".title").text(title);
            // 鼠标右键
            tab.on('contextmenu', function () {

            });
            this.append(tab);
            // 关闭
            tab.find(".close").on("click", function () {
                const url = $(this).parent().parent().data("url");
                that.deletePageStack(url, hashName);
                $(this).parent().parent().prev().find('a').trigger('click');
                $(this).parent().parent().remove();
            });
            // ifarme 载入页面
            this.iframes.append($('<div class="tab-pane" id="' + hashName + '"> <iframe width="100%" height="100%" frameborder="0" scrolling="y" src="' + href + '"></iframe> </div>'));
            this.init();
            tab.find("a").trigger("click");
        };

        this.deletePageStack = function (url, hashName) {
            for (const item in this.data.pageStack) {
                if (url === item) {
                    delete this.data.pageStack[item];
                    break;
                }
            }
            this.iframes.find("#" + hashName).remove();
        };

        this.hashStr = function (input) {
            var I64BIT_TABLE =
                'abcdefghijklmnopqrstuvwxyz0123456789'.split('');
            var hash = 5381;
            var i = input.length - 1;
            if (typeof input == 'string') {
                for (; i > -1; i--)
                    hash += (hash << 32) + input.charCodeAt(i);
            } else {
                for (; i > -1; i--)
                    hash += (hash << 32) + input[i];
            }
            var value = hash & 0x7FFFFFFF;

            var retValue = '';
            do {
                retValue += I64BIT_TABLE[value & 0x3F];
            }
            while (value >>= 6);
            return retValue;
        };

        this.init();
        this.linkHookFromTabs();

    }
})(jQuery);
