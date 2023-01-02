<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>插入代码</title>
<base target="_self"/>
<style type='text/css'>
body {
	font: 14px "Microsoft YaHei", Helvetica, Arial, Lucida Grande, Tahoma, sans-serif;
	background-color: #f1f1f1;
	color: #222;
}
.codeArea {
	margin: 5px;
}

textarea {
	margin-top: 10px;
	width: 100%;
	height: 320px;
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

<form name="becode" action="#">
	<div class="codeArea">
		<label for="lang">显示模式：</label>
		<select id="becode_mode" name="becode_main" style="width: 60px;padding: 3px;">
			<option value="normal" >正常</option>
			<option value="prune">简化</option>
		</select>
		<textarea id="becode_code" autofocus></textarea>
		<p>
			<input type="submit" id="insert" name="insert" value="确定"  class="button button-primary" onclick="insertbecodecode();"/>
			<input type="button" id="cancel" name="cancel" value="取消" class="submitdelete" onclick="javascript:window.parent.tinyMCE.activeEditor.windowManager.close();"/>
		</p>
	</div>
</form>

<script>
	var html = window.parent.tinyMCE.activeEditor.selection.getContent();
	document.getElementById('becode_code').value = html;
	function escapeHtml(text) {
		return text.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/'/g, "'").replace(/</g, "&lt;").replace(/>/g, "&gt;");
	}

	function insertbecodecode() {
		var tagtext;
		var modename_ddb = document.getElementById('becode_mode');
		var modename = modename_ddb.value;
		var html = escapeHtml(document.getElementById('becode_code').value);
		tagtext = '<pre class="code-' + modename + '" >' + html + '</pre>';
		window.parent.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, tagtext);
		window.parent.tinyMCE.activeEditor.windowManager.close();
		return;
	}
</script>
</body>
</html>