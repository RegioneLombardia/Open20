$(document).ready(function () {
    setTimeout(function () {
        /*
        add timeout because some graphic widget finish to load infos after
        masonry height calc
         */
        if ($('.grid').length) {
            $('.grid').isotope({
                layoutMode: 'packery',
                itemSelector: '.grid-item',
                packery: {
                    gutter:5
                }
            });
        }

    }, 500);
});
