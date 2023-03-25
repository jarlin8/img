/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-03-12 19:12:15
 * @LastEditors: iowen
 * @LastEditTime: 2023-03-25 02:59:34
 * @FilePath: \onenav\iopay\assets\js\iopay-admin.js
 * @Description: 
 */
(function ($) {
    
    $(document).ready(function ($) {
    });
    $(document).on("click", ".clear-order", function () { 

    });

    $(document).on("click", ".add-auto-url", function () {
        var c = $('#io_model_info');
        c.find('.io-model-title').html('添加');
        c.show().find("#auto_action").val("add");
        c.find("#auto_status").attr('checked', 'checked');
        c.find("#auto_check").val("1");
    });
    
    $(document).on("click", ".io-model-close", function () {
        $('#io_model_info').hide();
    });
    
    $(document).on("click", ".ajax-submit", function () {
        var _this = $(this);
        if (_this.attr("disabled")) {
            return false;
        }
        var _data = {};
        var form = $(_this.parents(".ajax-form")[0]);
        _data = form.serialize();
        return ajax_submit(_this, _data), !1; 
    })
    
    function ajax_submit(_this, _data, success, e) {
        var form = _this.parents(".ajax-form");
        var _notice = form.find(".ajax-notice");
        var _tt = _this.html();
        var ajax_url = form.attr("ajax-url") || _this.attr("href");
        var n_type = "warning";
        _this.attr("disabled", true);
        _this.html("请稍候...");
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: _data,
            dataType: "json",
            error: function (n) {
                var n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-error"><b>' + n.msg + n.status + '|' + n.statusText + '</b></div>';
                _notice.html(n_con);
                _this.attr("disabled", false);
            },
            success: function (n) {
                if (n.msg) {
                    n_type = n.error ? "error" : "info";
                    var n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-' + n_type + '"><b>' + n.msg + '</b></div>';
                    _notice.html(n_con);
                }
                _this.attr("disabled", false);
                _this.html(n.button || _tt);
                if (n.reload) {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
                $.isFunction(success) && success(n, _this, _data);
            }
        });
        return false;
    }
})(jQuery);