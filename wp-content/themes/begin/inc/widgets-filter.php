<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 筛选小工具
class Be_Filter_Widgets {
	function __construct(  ) {
		add_filter( 'admin_head', array( $this, 'filter_script'  )  );
	}

	function filter_script() {
		global $pagenow;

		if( $pagenow != 'widgets.php' ) 
			return;
		?>

		<script>
		(function($) {
			if(!window.Pick) window.Pick = {};

			Pick.FilterWidgets = {
				init: function() {
					$('#available-widgets .widget-holder p:first-child')
						.after('<p class="filter-widget" title="快捷键 (Ctrl-Shift-F)"><label><input id="widget-search" type="text" placeholder="输入关键字，筛选小工具"  style="width: 100%;padding: 6px 12px;border: 1px solid #c3c4c7;" /></label></p>');

					var $search = $('#widget-search');
					var $widget_list = $('#widget-list');

					$('body').on('keyup', function(ev) {
						if(ev.keyCode == 70 && ev.ctrlKey)
							$search.focus();
					});

					$search.on('click', function(ev) {
						ev.stopPropagation();
					}).on('keyup', function(ev) {
						var searchBox = this;
						$widget_list
							.find('.widget').show()
							.find('.widget-title').filter(function() { 
								return $(this).text().toLowerCase().indexOf(searchBox.value.toLowerCase()) == -1; 
							}).parents('.widget').hide();
					});
				}
			}

			$(Pick.FilterWidgets.init);
		})(jQuery);
		</script>
	<?php
	}
}

$GLOBALS['Be_Filter_Widgets'] = new Be_Filter_Widgets();