/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-01-24 21:51:38
 * @LastEditors: iowen
 * @LastEditTime: 2022-06-22 17:23:57
 * @FilePath: \onenav\inc\framework\customize\js\options.js
 * @Description: 
 */
(function ($) {
    $(".csf-section .new").each(function(){
        var str = $(this).parent().data("section-id");
        $(".csf-tab-item a[data-tab-id=\'"+str+"\']").addClass("new-item");
        if(str){
        $(".csf-tab-item a[data-tab-id=\'"+str.split("/")[0]+"\']").addClass("new-item");
        }
    });
    
    $(document).ready(function ($) {
    });
    
    $(document).on("click", ".home-widget-type", function (){ 
        var t = $(this);
        var type = t.find('input:radio:checked').val();
        var query = t.next().find('select').data('chosen-settings');
        query.data.query_args.taxonomy = type;
        t.next().find('select').data('chosen-settings',query);
    });
    $(document).on("click", ".csf-content", function (){ 
        var $wrapper = $('.csf-wrapper.io-option');
        if($wrapper.hasClass('csf-show-all')){
            $wrapper.removeClass('csf-show-all');
        }
    });
    $(document).on("click", ".ajax-submit", function () {
        var _data = {};
        var _this = $(this);
        var form = $(_this.parents(".ajax-form")[0]);
        if (_this.attr("disabled")) {
            return false;
        }
        form.find("input,[ajax-name],[name]").each(function () {
            n = $(this).attr("ajax-name") || $(this).attr("name"), v = $(this).val();
            if (n) {
                _data[n] = v;
            }
        });
        if(_data.action=="get_iotheme_delete_authorization"){
            var r= confirm( "你确定要删除授权吗？" );
            if (r!=true){
                return false;
            }
        }
        return ajax_submit(_this, _data), !1; 
    })
    $(document).on("click", ".ajax-get", function () {
        var _this = $(this);
        var confirm_text = _this.attr('data-confirm');
        if (confirm_text) {
            if (confirm(confirm_text) == true) {
                return ajax_submit(_this, {}), !1;
            } else {
                return !1;
            }
        } else {
            return ajax_submit(_this, {}), !1;
        }
    });
    function ajax_submit(_this, _data, success, notice, e) {
        var form = _this.parents(".ajax-form,ajaxform");
        var _notice = form.find(".ajax-notice");
        var _tt = _this.html();
        var ajax_url = form.attr("ajax-url") || _this.attr("href");
        var spin = '<i class="fa fa-spinner fa-spin fa-fw"></i> '
        var n_type = "warning";
        var n_msg = spin + '正在处理，请稍候...';
        _this.attr("disabled", true);
        if(!_this.hasClass("bnt-svg")) _this.html(spin + "请稍候...");
        if (notice) {
            _notice.html('<div style="padding: 10px;margin: 0;" class="notice"></div>');
            notice = spin + notice;
        }
        _notice.find('.notice').html(notice || n_msg).removeClass('notice-error notice-info').addClass('notice-warning');
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: _data,
            dataType: "json",
            error: function (n) {
                var n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-error"><b>' + "网络异常或者操作失败，请稍候再试！ " + n.status + '|' + n.statusText + '</b></div>';
                _notice.html(n_con);
                _this.attr("disabled", false).removeClass('jb-blue');
                if(!_this.hasClass("bnt-svg")) _this.html("操作失败");
                form.find('.progress').css('opacity', 0).find('.progress-bar').css({
                    'width': '0',
                    'transition': 'width .3s',
                });
            },
            success: function (n) {
                if (n.msg) {
                    n_type = n.error_type || (n.error ? "error" : "info");
                    var n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-' + n_type + '"><b>' + n.msg + '</b></div>';
                    _notice.html(n_con);
                }
                _this.attr("disabled", false);
                if(!_this.hasClass("bnt-svg")) _this.html(n.button || _tt);
                if (n.reload) {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
                $.isFunction(success) && success(n, _this, _data);
            }
        });
    }
    var ajax_url;
    function update_load(){
		$.get(ajax_url,
			{
				'action' : 'io_current_load'
			},
			function (data, textStatus){
				$('#io_current_load').html(data);
				setTimeout(update_load, 10000); // 每10秒更新一次
			}
		);
    }
	if ( $('#io_current_load').length > 0 ){
        ajax_url = $('#io_current_load').data('url');
        update_load();
	}
})(jQuery);