<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 图片滑块
function baslider_popup_on_select() {
?>
<div id="baslider_select" style="display:none;">
	<div class="wrap">
		<table class="form-table batable">
			<tr valign="top">
				<th scope="row" width="25%">悬停滑动</th>
				<td width="75%">
					<select id="ba_sc_hover" name="ba_sc_hover" class="widefat" style="width: 120px;">
						<option value="true" selected>是</option>
						<option value="false">否</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">滑块距左</th>
				<td width="75%">
					<select id="ba_sc_offset" name="ba_sc_offset" class="widefat" style="width: 120px;">
						<option value="0.1">0.1</option>
						<option value="0.2">0.2</option>
						<option value="0.3">0.3</option>
						<option value="0.4">0.4</option>
						<option value="0.5" selected>0.5</option>
						<option value="0.6">0.6</option>
						<option value="0.7">0.7</option>
						<option value="0.8">0.8</option>
						<option value="0.9">0.9</option>
						<option value="1">1.0</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">滑动方向</th>
				<td width="75%">
					<select id="ba_sc_direction" name="ba_sc_direction" class="widefat" style="width: 120px;">
						<option value="default" selected>水平</option>
						<option value="vertical">垂直</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">对齐方式</th>
				<td width="75%">
					<select id="ba_sc_align" name="ba_sc_align" class="widefat" style="width: 120px;">
						<option value="center" selected>中</option>
						<option value="right">右</option>
						<option value="left">左</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">滑块宽度</th>
				<td width="75%">
					<input type="number" id="ba_sc_width" name="ba_sc_width" value="" placeholder="默认: 100" class="widefat" style="width: 120px;"><br/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">手动图片</th>
				<td width="75%">
					<select id="ba_sc_external" name="ba_sc_external" class="widefat" style="width: 120px;">
						<option value="true">是</option>
						<option value="false" selected>否</option>
					</select><br/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">手动左图</th>
				<td width="75%">
					<input type="text" id="ba_before_img" name="ba_before_img" value="" placeholder="图片地址" class="widefat"><br/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">手动右图</th>
				<td width="75%">
					<input type="text" id="ba_after_img" name="ba_after_img" value="" placeholder="图片地址" class="widefat"><br/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">左图文字</th>
				<td width="75%">
					<input type="text" id="ba_sc_before_caption" name="ba_sc_before_caption" value="" placeholder="文字" class="widefat"><br/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" width="25%">右图文字</th>
				<td width="75%">
					<input type="text" id="ba_sc_after_caption" name="ba_sc_after_caption" value="" placeholder="文字" class="widefat"><br/><br/>
				</td>
			</tr>
		</table>
		<a href="#" id="insert-ba-sc" class="button button-primary button-large">插入滑块</a>
		<input type="hidden" id="ba_img1" name="ba_img1">
		<input type="hidden" id="ba_img2" name="ba_img2">
		<!-- <img src="" class="timg-before" /> -->
	</div>
</div>

<?php
}
add_action( 'admin_footer','baslider_popup_on_select' );


function baslider_js() { ?>
<script>
// 图片滑块
jQuery(function($) {
	$(document).ready(function() {
		//$('#insert-ba-media').click(open_media_window);
		$('#insert-ba-sc').click(insert_baslider_shortcode);
		//$('.mce-ba-mce-btn button').click(open_media_window);
	});

	$(document).on('click', '.mce-ba-mce-btn',
	function() {
		open_media_box();
	});

	function open_media_box() {
		if (this.mediaUploader === undefined) {
			this.mediaUploader = wp.media({
				title: '选择两张图片',
				library: {
					type: 'image'
				},
				multiple: 'add',
				button: {
					text: '设置滑块'
				}
			});

			var self = this;
			this.mediaUploader.on('select',
			function() {
				var selection = self.mediaUploader.state().get('selection');
				if (selection.length !== 2) {
					alert("请选择两张图片");
					return;
				}

				var first = selection.first().toJSON();
				var last = selection.last().toJSON();
				var im = selection.toJSON();

				var ids = selection.pluck('id');
				$('#ba_img1').val(ids[0]);
				$('#ba_img2').val(ids[1]);

				tb_show("图片滑块设置", "#TB_inline?height=580&amp;width=600&amp;inlineId=baslider_select");
			});
		}

		this.mediaUploader.open();
	}

	function open_media_window() {
		if (this.window === undefined) {
			this.window = wp.media({
				title: '选择两张图片',
				library: {
					type: 'image'
				},
				multiple: 'add',
				button: {
					text: '设置滑块'
				}
			});

			var self = this; // Needed to retrieve our variable in the anonymous function below
			this.window.on('select',
			function() {
				var first = self.window.state().get('selection').first().toJSON();
				var last = self.window.state().get('selection').last().toJSON();
				var im = self.window.state().get('selection').toJSON();

				if (im.length != 2) {
					alert("请选择两张图片");
					return false;
				}

				$('#ba_img1').val(first.id);
				//$('img.timg-before').attr("src", first.url);
				$('#ba_img2').val(last.id);
				tb_show("图片滑块设置", "#TB_inline?height=678&amp;width=600&amp;inlineId=baslider_select");
				//wp.media.editor.insert('[twenty20 img1="' + first.url + '" img2="' + last.url + '" width="100%" direction="horizontal" offset="0.5"]');
			});
		}

		this.window.open();
		return false;
	}

	function insert_baslider_shortcode() {
		var img1 = $('#ba_img1').val();
		var img2 = $('#ba_img2').val();
		var pic1 = $('#ba_before_img').val();
		var pic2 = $('#ba_after_img').val();
		var before = $('#ba_sc_before_caption').val();
		var after = $('#ba_sc_after_caption').val();
		var twidth = $('#ba_sc_width').val();
		var direction = $('#ba_sc_direction').val();
		var hover = $('#ba_sc_hover').val();
		var external = $('#ba_sc_external').val();
		var offset = $('#ba_sc_offset').val();
		var align = $('#ba_sc_align').val();

		if (before == null || before == '') {
			before = '';
		} else {
			before = ' before="' + before + '"';
		}
		if (after == null || after == '') {
			after = '';
		} else {
			after = ' after="' + after + '"';
		}
		if (align == null || align == '') {
			align = '';
		} else {
			align = ' align="' + align + '"';
		}
		if (direction == 'default') {
			direction = '';
		} else {
			direction = ' direction="' + direction + '"';
		}
		if (hover == 'false') {
			hover = '';
		} else {
			hover = ' hover="' + hover + '"';
		}
		if (external == 'false') {
			external = '';
			img1  = ' img1="' + img1 + '"';
			img2  = ' img2="' + img2 + '"';
		} else {
			external = ' external="' + external + '"';
			img1  = ' img1="' + pic2 + '"';
			img2  = ' img2="' + pic2 + '"';
		}
		if (twidth == '' || twidth == null) {
			twidth = '';
		} else {
			twidth = ' width="' + twidth + '"';
		}
		wp.media.editor.insert('[moveslider '+ img1 + img2 + twidth + direction + ' offset="' + offset + '"' + align + before + after + hover + external + ']');
		return false;
	}
});
</script>
<?php };
add_action( 'admin_footer', 'baslider_js' );