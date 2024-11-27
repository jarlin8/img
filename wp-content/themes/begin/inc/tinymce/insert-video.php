<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>添加视频</title>
<base target="_self"/>
<style type='text/css'>
body {
	font: 14px "Microsoft YaHei", Helvetica, Arial, Lucida Grande, Tahoma, sans-serif;
	background-color: #f1f1f1;
	color: #222;
}

.mce-window .mce-window-head .mce-title {
	color: #3c434a;
	font-size: 14px !important;
	font-weight: 600;
	line-height: 36px;
	margin: 0;
	padding: 0 36px 0 16px;
}

.bevideo-textarea {
	margin: 5px;
}

textarea {
	margin-top: 10px;
	width: 100%;
	height: 320px;
}

.video-point {
	padding-top: 5px;
}

.button-primary {
	float: right;
	display: inline-block;
	text-decoration: none;
	font-size: 13px;
	line-height: 26px;
	height: 28px;
	margin: 0;
	padding: 0 10px 1px;
	cursor: pointer;
	border-width: 1px;
	border-style: solid;
	-webkit-appearance: none;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	white-space: nowrap;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: #00a0d2;
	border-color: #0073aa;
	-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
	box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
	color: #fff
}

.button-primary.focus,.button-primary.hover,.button-primary:focus,.button-primary:hover {
	background: #0091cd;
	border-color: #0073aa;
	-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
	box-shadow: inset 0 1px 0 rgba(120,200,230,.6);
	color: #fff
}

.submitdelete {
	float: left;
}
</style>
</head>
<body id="link" >

<form name="bevideo" action="#">
	<div class="bevideo-textarea">
		<textarea id="bevideo" autofocus></textarea>
		<div class="video-point">多个视频链接中间用英文半角逗号','隔开</div>
		<p>
			<input type="submit" id="insert" name="insert" value="确定"  class="button button-primary" onclick="insertbevideo();"/>
			<input type="button" id="cancel" name="cancel" value="取消" class="submitdelete" onclick="javascript:window.parent.tinyMCE.activeEditor.windowManager.close();"/>
		</p>
	</div>
</form>

<script>
	var html = window.parent.tinyMCE.activeEditor.selection.getContent();
	document.getElementById('bevideo').value = html;
	function escapeHtml(text) {
		return text.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/'/g, "'").replace(/</g, "&lt;").replace(/>/g, "&gt;");
	}

	function insertbevideo() {
		var tagtext;
		var html = document.getElementById('bevideo').value;
		tagtext = '[be_dplayer type="m3u8dplayer" vid="' + html + '"][/be_dplayer]';
		window.parent.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, tagtext);
		window.parent.tinyMCE.activeEditor.windowManager.close();
		return;
	}
</script>
</body>
</html>