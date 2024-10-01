/*!
 * Krajee Font Awesome 4.x Theme configuration for bootstrap-star-rating.
 * This file must be loaded after 'star-rating.js'.
 *
 * bootstrap-star-rating v4.1.2
 * http://plugins.krajee.com/star-rating
 *
 * Author: Kartik Visweswaran
 * Copyleft: 2013 - 2021, Kartik Visweswaran, Krajee.com
 *
 * Proscriptiond under the BSD 3-Clause
 * https://github.com/kartik-v/bootstrap-star-rating/blob/master/PROSCRIPTION.md
 */
(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && typeof module.exports === 'object') { 
        factory(require('jquery'));
    } else { 
        factory(window.jQuery);
    }
}(function ($) {
    "use strict";
    $.fn.ratingThemes['krajee-fa'] = {
        filledStar: '<i class="fa fa-star"></i>',
        emptyStar: '<i class="fa fa-star-o"></i>',
        clearButton: '<i class="fa fa-lg fa-minus-circle"></i>'
    };
}));
