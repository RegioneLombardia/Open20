$(document).ready(function () {
    //inizializza la dashboard
    // Dashboard.init('primary', '.bk-sortableIcon', $('#saveDashboardUrl').val());
//console.log("ciao");
    IntroSlideshow = {
        id: 'amos-slideshow',
        selector: '.introduzioneSlideshow',
        cookie: Cookie,
        intro: Introduzione,
        show: function () {

            $(this.selector).modal({
                /*type: 'html',*/
                // maxWidth: 800,
                // maxHeight: 600,
                // fitToView: false,
                // width: '70%',
                // height: 'auto',
                // autoSize: false,
                // closeClick: false,
                // loop: false

            });

            $(this.selector).first().trigger('click');

            this.setIntroShow(this.id);

        },
        init: function () {

        },
        setIntroShow: function (tipo_introduzione) {
            var cookie_val = this.cookie.getCookie(this.intro.cookie_name);
            if (!cookie_val) {
                cookie_val = {};
            }
            else {
                cookie_val = JSON.parse(cookie_val);
            }
            cookie_val[tipo_introduzione] = true;
            this.cookie.setCookie(this.intro.cookie_name, JSON.stringify(cookie_val), 365 * 10);
        },
        isIntroShow: function (tipo_introduzione) {
            var cookie_val = this.cookie.getCookie(this.intro.cookie_name);
            if (!cookie_val) {
                return false;
            }
            else {
                cookie_val = JSON.parse(cookie_val);
                return (cookie_val[tipo_introduzione] && (cookie_val[tipo_introduzione] == true || cookie_val[tipo_introduzione] == "true") ? true : false);
            }
        }
    };
    // if (!IntroSlideshow.isIntroShow(IntroSlideshow.id)) {
        IntroSlideshow.show();
    // }

});