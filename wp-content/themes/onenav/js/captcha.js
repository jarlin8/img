/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-02-10 22:22:47
 * @LastEditors: iowen
 * @LastEditTime: 2022-07-08 01:08:16
 * @FilePath: \onenav\js\captcha.js
 * @Description: 图形验证
 */
(function($){ 
    if ($("[canvas-code]").length) {
        canvas_code = {};
        $("[canvas-code]").each(function () {
            var _this = $(this);
            var _id = _this.attr('id'); 
            drawCode(_id, canvas_code);
        });

        $(document).on('click', "[canvas-code]", function () {
            var _this = $(this);
            var _id = _this.attr('id'); 
            drawCode(_id, canvas_code);
        })

        $(document).on('input porpertychange', 'input[name="captcha"]', function () {
            var _this = $(this);
            var _id = _this.attr('canvas-id');
            var val = _this.val().toLowerCase();
            var match_code = _this.siblings('.match-code').children('.key-icon');
            if (val.length > 3) {
                var vcode = canvas_code[_id];
                if (val == vcode) {
                    match_code.html('<i class="iconfont icon-adopt icon-fw text-success"></i>');
                } else {
                    match_code.html('<i class="iconfont icon-close-circle icon-fw text-danger"></i>');
                }
            } else {
                match_code.html('');
            }
        })
    }
})(jQuery);

function drawCode(id, code) {
    var width = $("#" + id).attr("width");
    var height = $("#" + id).attr("height");
    var doc = document.getElementById(id);
    var ctx = doc.getContext("2d");

    doc.width = width;
    doc.height = height;
    const chars = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
    let _code = '';
    for (var i = 0; i <= 3; i++) {
        var char = chars[Math.floor(Math.random() * chars.length)];
        _code += char.toLowerCase();
        var x = width / 5 * (i + 1);
        var y = height/2 + (Math.random() * 10) + 2;
        var deg  = randomNum(-25, 25) * Math.PI / 180;
        ctx.font = "bold " + randomNum(21, 25) + "px SimHei";
        ctx.fillStyle = randomColor();
        /**设置旋转角度和坐标原点**/
        ctx.translate(x, y);
        ctx.rotate(deg); 
        ctx.fillText(char, 0, 0);
        /**恢复旋转角度和坐标原点**/
        ctx.rotate(-deg);
        ctx.translate(-x, -y);
    }
    code[id] = _code;
    for (var i = 0; i <= 5; i++){ 
        ctx.strokeStyle = randomColor();
        ctx.beginPath();
        ctx.moveTo(Math.random() * width, Math.random() * height);
        ctx.lineTo(Math.random() * width, Math.random() * height); 
        ctx.stroke();
    }
    for (var i = 0; i <= 30; i++) {
        ctx.strokeStyle = randomColor();
        ctx.beginPath();
        var x = Math.random() * width;
        var y = Math.random() * height;
        ctx.moveTo(x, y);
        ctx.lineTo(x + 1, y + 1);
        ctx.stroke();
    }
}
function randomNum(minNum,maxNum){ 
    switch(arguments.length){ 
        case 1: 
            return parseInt(Math.random()*minNum+1,10);
        case 2: 
            return parseInt(Math.random()*(maxNum-minNum+1)+minNum,10); 
        default: 
            return 0; 
    } 
} 
function randomColor() {
    return "rgb(" + Math.floor(230 * Math.random() + 20) + "," + Math.floor(190 * Math.random() + 30) + "," + Math.floor(190 * Math.random() + 30) + ")";
}