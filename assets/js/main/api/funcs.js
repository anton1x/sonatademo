import controller from './models/controller.js';
import axios from 'axios';

export default class extends controller {

    constructor(api) {
        
        super(api);
    }

    //Детекция тачпад-устройств
    #is_touch_device = null
    isTouchDevice()
    {
        if (this.#is_touch_device === null)
        {
            this.#is_touch_device = (() =>
            {
                let prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
                let mq = function(query) {
                    return window.matchMedia(query).matches;
                };
                if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
                    return true;
                }
                let query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
                return mq(query);
            })();
        }

        return this.#is_touch_device;
    }

    //Стандартный загрузчик
    getContentForLoader()
    {
        return `<div class="loader_box"><div></div></div>`;
    }

    //Алерт
    alert(text, options = {})
    {
        let html = `<div style="padding:20px 0 0 0; font-size:18px;">${text}</div><div style="font-size:0; text-align:center; padding-top:30px;"><a href="javascript://" class="button_2" onclick="eApi.getBoxes().getBoxById('alert').close(); return false;">OK</a>`;

        options = Object.assign({
            id : 'alert',
            animate : true,
            minWidth : 300,
            maxWidth : 760,
            close_on_bg_click : false
        }, options);

        options.content = html;

        this.getApi().getBoxes().create(options);
    }

    //Загрузить данные на сервер
    sendData(url, data = {})
    {
        return new Promise((resolve, reject) =>
        {
            axios.post(url, data).then((response) =>
            {
                if (response.data.error == 0)
                {
                    resolve(response.data.settings);
                }
                else
                {
                    reject({code : -1, error_list : response.data.error_list, error_extra_list : response.data.error_extra_list}); 
                }

            }).catch(()=> {
                reject({code : -100, error_list : [], error_extra_list : []});
            });
        });
    }

    //Загрузить и отобразить в окне
    loadBox(url, box_options = {})
    {
        let is_loaded = false;
        let loader_box = null;

        box_options = Object.assign({
            box_ready_ft : () => {
                loader_box.close();
            },
        }, box_options);

        let p1 = new Promise((resolve, reject) =>
        {
            if (typeof url == 'string')
            {

            }
            else if (typeof url == 'function')
            {
                url((params) => {
                    is_loaded = true;
                    resolve(params);
                }, () => {
                    reject();
                });
            }
        });

        p1.catch(() => {
            if (loader_box !== null)
            {
                loader_box.close();
            }
        });

        let p2 = new Promise((resolve)=>
        {
            loader_box = this.getApi().getBoxes().create({
                type : 'html',
                block_close : true,
                animate : true,
                animate_settings : {
                    single_show : {
                        mode : 'after_bg'
                    },
                    multiple_hide : {
                        cancel : true
                    }
                },
                animate_class_prefix : 'loader',
                bg_ready : (c) => {
                    if (is_loaded)
                    {
                        c.cancelBoxAppear();
                    }
                    resolve();
                },
                content : this.getContentForLoader()
            });

        });

        
        Promise.all([p1, p2]).then((result) =>
        {
            if (typeof result[0] == 'object')
            {
                box_options = Object.assign(box_options, result[0]);
            }
            let box = this.getApi().getBoxes().create(box_options);

        }).catch((e) => {
            console.log('error', e);
        });
        
    }

    windowScrollTo(v)
    {
        window.scrollTo({ top: v, behavior: 'smooth' });
    }

    nextAnimFrame(f)
    {
        let raf = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        if (raf)
        {
            raf(() => {
                raf(() => {
                    f();
                });
            });
        }
        else
        {
            f();
        }
    }

    addMouseEnterEvents(item)
    {
        var s = "onmouseenter" in window;
        if (!s)
        {
            item.addEventListener('mouseover', function(event) {    
                if (!event.relatedTarget || (event.relatedTarget !== this && !(this.compareDocumentPosition(event.relatedTarget) & Node.DOCUMENT_POSITION_CONTAINED_BY))) {
                    this.dispatchEvent(new Event('mouseenter'));
                }
            });
            
            item.addEventListener('mouseout', function(event) {    
                if (!event.relatedTarget || (event.relatedTarget !== this && !(this.compareDocumentPosition(event.relatedTarget) & Node.DOCUMENT_POSITION_CONTAINED_BY))) {
                    this.dispatchEvent(new Event('mouseleave'));
                }
            });
        }
    }

    checkIsEmail(email)
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    numberFormat(number, decimals, dec_point, thousands_sep)
    {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k).toFixed(prec);
        };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3)
        {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec)
        {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

}