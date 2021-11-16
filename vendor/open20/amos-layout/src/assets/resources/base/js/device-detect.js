/*
 * Device detect: cerca di individuare su quale tipo di device ci troviamo
 * Per il momento individua solo i dispositivi touch in base alle interazioni dell'utente
 * TODO: Da gestire con un dependency resolver
 */
$amos.deviceDetect = (function deviceDetect () {
    $amos.device = {}
    $amos.device.isTouch = false;

    function touchDetect () {
        window.addEventListener('pointerdown', function onFirstPointer(e) {
            $amos.device.isTouch = e.pointerType === "touch";
        }, false);
    }

    return {
        init: touchDetect
    };
})();

$amos.ready($amos.deviceDetect.init());