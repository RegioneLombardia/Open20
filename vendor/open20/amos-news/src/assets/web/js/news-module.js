$amos.newsModule = (function newsModule () {
    const newsModule = {};

    newsModule.init = function () {
        watch();
    };

    function watch () {
        $(document).on('initialized.owl.carousel, refreshed.owl.carousel', recalculateTop);
    }

    /* Per ogni gruppo di items sulla stessa cella da tre (solo su desktop quindi)
     * calcola, sulla base dell'item più alto, lo spazio disponibile sopra 
     * ogni .abstract e allinea tutti gli altri di conseguenza.
     */
    function recalculateTop () {
        const $items = $('#newsOwlCarousel .owl-item');

        $items.each(function () {
            $abstracts = $(this).find('.abstract');
            $abstracts.css('top', 'auto');

            var maxHeight = Math.max.apply(null, $abstracts.map(function () {
                return $(this).outerHeight();
            }).get());

            $abstracts.css('top', $(this).height() - maxHeight);
        });
    }

    return newsModule;
})();