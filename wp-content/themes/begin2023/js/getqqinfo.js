jQuery(document).ready(function($) {
	var qq = goqq.qqinf;
	$("#fillqq").click(function() {
		var abc = $('#qq').val();
		if (abc) {
			if (!isNaN(abc)) {
				$.ajax({
					url: qq,
					type: "get",
					data: {
						qq: abc
					},
					dataType: "json",
					success: function(data) {
						$("#email").val(abc + '@qq.com');
						$('#comment').focus();
						if (data == null) {
							$("#author").val('QQ游客');
						} else {
							$("#author").val(data[abc][6] == "" ? 'QQ游客': data[abc][6]);
						}
					},
					error: function(err) {
						alert('貌似没有这个QQ号');
						//$("#author").val('QQ游客');
						$("#email").val(abc + '@qq.com');
						$('#comment').focus();
					}
				});
				return true;
			} else {
				alert('您输入的好像不是QQ号码');
				$('#qq').focus();
			}
		} else {
			alert('请输入您的QQ号');
			$('#qq').focus();
		}
	});
});