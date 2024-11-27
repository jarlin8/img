(function() {
	tinymce.create('tinymce.plugins.becode', {
		init: function(ed, url) {
			ed.addCommand('becode',
			function() {
				ed.windowManager.open({
					title: '插入代码',
					file: url + '/insert-code.php',
					width: 500,
					height: 420,
					inline: 1
				},
				{
					plugin_url: url // Plugin absolute URL
				});
			});

			ed.addButton('becode', {
				title: '代码高亮',
				cmd: 'becode',
				icon: 'code'

			});
		},
		createControl: function(n, cm) {
			return null;
		},
		getInfo: function() {
			return null;
		}
	});
	tinymce.PluginManager.add('becode', tinymce.plugins.becode);
})();