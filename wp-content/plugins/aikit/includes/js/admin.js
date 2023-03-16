"use strict";

// on document load
document.addEventListener('DOMContentLoaded', function() {
    // max token multiplier option
    var slider = document.getElementById("aikit_setting_openai_max_tokens_multiplier");
    var output = document.getElementById("aikit_setting_openai_max_tokens_multiplier_value");

    slider.oninput = function() {
        var multiplier = this.value;
        multiplier = multiplier / 10;
        output.innerHTML = (1 + multiplier) + 'x';
    }

    slider.oninput();

    // autocomplete text background color option
    var colorDropdown = document.getElementById("aikit_setting_autocompleted_text_background_color");

    colorDropdown.onchange = function() {
        var color = this.value;
        colorDropdown.style = 'width: 150px; background-color: ' + color;
    }

    colorDropdown.onchange();
});
