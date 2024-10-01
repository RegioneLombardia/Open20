$amos.tooltipComponent = (function tooltipComponent () {
    const tooltipModule = {};

    tooltipModule.init = function () {
        watch();
    };

    function watch () {
        window.addEventListener('touchstart', touchListener, {passive: false});
    }

    // Nei dispositivi touch, inibisce il primo tap per permettere l'apertura
    // del toltip al secondo tap, seguirà la normale procedura prevista
    function touchListener(event) { 
        const $target = $(event.target);
        const elements = [
            $target.parents('#toggle-translate'),
            $target.parents('#toggle-assistance')
        ]; 

        for(i in elements) {
            if(event.cancelable && elements[i].length) {
                if(!elements[i].is('a:focus')) {
                    event.preventDefault();
                    event.stopPropagation();
                    elements[i].focus();
                }
                else {
                    elements[i].blur();
                }
            }
        }
    };

    return tooltipModule;
})();

$amos.ready($amos.tooltipComponent.init());