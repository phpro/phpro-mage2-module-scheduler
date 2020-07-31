define(['jquery'], function ($) {
    'use strict';

    $('.tooltip').hover(function (e) {
        var tooltip = $(this).find('.tooltip-content'),
            screenWidth = window.innerWidth,
            screenHeight = window.innerHeight,
            cursorX = e.clientX,
            cursorY = e.clientY,
            tooltipWidth = tooltip.width(),
            tooltipHeight = tooltip.height(),
            offset = 10,
            left = cursorX + offset,
            top = cursorY + offset;

        // We adjust the left position if the tooltip falls out of bounds
        if (screenWidth < (cursorX + offset + tooltipWidth)) {
            left = (cursorX + offset) - ((cursorX + offset + tooltipWidth) - screenWidth)
        }

        // We adjust the top position if the tooltip falls out of bounds
        if (screenHeight < (cursorY + offset + tooltipHeight)) {
            top = (cursorY + offset) - ((cursorY + offset + tooltipHeight) - screenHeight)
        }

        tooltip.css('left', left).css('top', top);
    })
});
