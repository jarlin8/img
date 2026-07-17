export const equalColumns = () => {
    let groupName = Array.prototype.slice.call( document.querySelectorAll('[data-match-height]')),
    groupHeights = {};
    for (var item of groupName) {
        var data = item.getAttribute('data-match-height');
        item.style.minHeight = 'auto';
        item.style.height = 'auto';

        if (groupHeights.hasOwnProperty(data)) {
        Object.defineProperty(groupHeights, data, {
            value: Math.max(groupHeights[data], item.offsetHeight),
            configurable: true,
            writable: true,
            enumerable: true
        });
        } else {
            groupHeights[data] = item.offsetHeight;
        }
    }

    var groupHeightsMax = groupHeights;

    Object.getOwnPropertyNames(groupHeightsMax).forEach(function(value) {
        var elementsToChange = document.querySelectorAll(
            "[data-match-height='" + value + "']"
        ),
        elementsLength = elementsToChange.length;

        for (var i = 0; i < elementsLength; i++) {
        elementsToChange[i].style.height =
            Object.getOwnPropertyDescriptor(groupHeightsMax, value).value +
            'px';
        }
    });
}