import controller from './models/controller.js';

export default class extends controller {

    constructor(api) {
        
        super(api);
    }

    showCaptcha(t, onReady) {

        let html = `<div id="box_captcha_wrapper"><div class="text">${t}</div><div class="captcha"><div><div id="recovery_google_recaptcha"></div></div></div>`;
        let gc_init = false;

        let options = {
            id : 'captcha',
            animate : true,
            minWidth : 300,
            maxWidth : 760,
            close_on_bg_click : false,
            content : html,
            box_destroy : () => {
                if (google_token !== false)
                {
                    onReady(google_token);
                }
            }
        };

        let box = this.getApi().getBoxes().create(options);

        let google_token = false;

        gCR.exec(() => {
            grecaptcha.render('recovery_google_recaptcha',
            {
                'sitekey' : window._initSettings.recaptha_key,
                'size' : (window.innerWidth > 520 ? 'normal' : 'compact'),
                'callback' : function(token)
                {
                    google_token = token;
                    box.close();
                }
            });
            gc_init = true;
        });
    }
}