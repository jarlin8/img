(function($) {
    tinymce.create('tinymce.plugins.dmd_tbk_plugin', {
        init: function(editor, url) {
            editor.addButton('dmd_tbk_plugin', {
                title: "插入淘宝产品", //    鼠标放在按钮上时的提示文字
                image: url + '/tbk.png', //    按钮图标
                cmd: 'dmd_command' //    点击时执行的方法
            });
            editor.addCommand('dmd_command', function() {
                editor.windowManager.open(
                    {
                        title: "插入淘宝产品", //    对话框的标题
                        file: url + '/dmd_tbk.htm', //    放置对话框内容的HTML文件
                        width: 500, //    对话框宽度
                        height: 400, //    对话框高度
                        inline: 1 //    Whether to use modal dialog instead of separate browser window.
                    },{
                    	plugin_url:url
                    }
                );
            });
        }
    });
   tinymce.PluginManager.add('dmd_tbk_plugin', tinymce.plugins.dmd_tbk_plugin);
 
})(jQuery);