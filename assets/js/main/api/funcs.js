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
        let html = `<div class="box_alert_wrapper"><div class="text">${text}</div><div class="but"><a href="javascript://" class="button_3" onclick="eApi.getBoxes().getBoxById('alert').close(); return false;">OK</a></div></div>`;

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
                resolve(response);

            }).catch(()=> {
                reject();
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
            //console.log(box_options);
            let box = this.getApi().getBoxes().create(box_options);

        }).catch((e) => {
            console.log('error', e);
        });
        
    }

    windowScrollToBlock(v)
    {
        if (document.getElementById(v) !== null)
        {
            let s = document.getElementById(v).getBoundingClientRect().top + pageYOffset;
            this.windowScrollTo(s);
        }
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


    decodeURIComponentSafe(uri, mod)
	{
		var out = new String(),
			arr,
			i = 0,
			l,
			x;
		typeof mod === "undefined" ? mod = 0 : 0;
		arr = uri.split(/(%(?:d0|d1)%.{2})/);
		for (l = arr.length; i < l; i++) {
			try {
				x = decodeURIComponent(arr[i]);
			} catch (e) {
				x = mod ? arr[i].replace(/%(?!\d+)/g, '%25') : arr[i];
			}
			out += x;
		}
		return out;
    }
    
    parseQueryString(query)
	{
		if (query.length == 0)
		{
			return {};
		}
		var vars = query.split('&');
		var query_string = {};
		for (var i = 0; i < vars.length; i++)
		{
			if (vars[i].length == 0)
			{
				continue;
			}
			var pair = vars[i].split("=");
			if (pair.length == 1)
			{
				pair[1] = true;
			}
			var key = this.decodeURIComponentSafe(pair[0], 1);
			var value = '';
			for (var g = 1; g < pair.length; g++)
			{
				value += (g > 1 ? '=' : '') + this.decodeURIComponentSafe(pair[g], 1);
			}
			
			if (typeof query_string[key] === "undefined")
			{
				query_string[key] = this.decodeURIComponentSafe(value);
			}
			else if (typeof query_string[key] === "string")
			{
				var arr = [query_string[key], this.decodeURIComponentSafe(value, 1)];
				query_string[key] = arr;
			}
			else
			{
				query_string[key].push(this.decodeURIComponentSafe(value, 1));
			}
		}
		return query_string;
    }
    
    encodeUrlComponent(str)
	{
		return encodeURIComponent(str).replace(/%2C/g, ',');
    }

    replaceUrl(vars)
	{
        vars = Object.assign({
            url : false,
            title : false,
            replace_history : true
        }, vars);
        
        if (vars.title == false)
        {
            vars.title = document.title;
        }
        
        if (history.pushState)
        {
            if (vars.url)
            {
                if (vars.replace_history == false)
                {
                    history.pushState({type : 'page', url : vars.url}, vars.title, vars.url);
                }
                else
                {
                    history.replaceState({type : 'page', url : vars.url}, vars.title, vars.url);
                }
            }
        }
    }
    
    getUrlParams()
    {
        var query = document.location.search;
        if (query.length > 0 && query.substring(0,1) == '?')
        {
            query = query.substring(1);
        }
        var result = this.parseQueryString(query);
        
        return result;
    }
    
    setUrlParamsVars(vars)
    {
        vars = Object.assign({
            url : false,
            vars : {},
            replace_history : true
        }, vars);

        var new_vars = vars.vars;
        var new_url = vars.url === false ? (document.location.pathname.length == 0 ? '/' : document.location.pathname) : vars.url;
        
        var c = 0; 
        for (var i in new_vars)
        {
            if (typeof new_vars[i] == 'string' || typeof new_vars[i] == 'number')
            {
                new_url += c == 0 ? '?' : '&';
                new_url += i + '=' + this.encodeUrlComponent(new_vars[i]);
                c++;
            }
            else if (typeof new_vars[i] == 'boolean')
            {
                new_url += c == 0 ? '?' : '&';
                new_url += i;
                c++;
            }
            else if (typeof new_vars[i] == 'object')
            {
                if (new_vars[i].type == 'array')
                {
                    new_vars[i].separator = typeof new_vars[i].separator == 'undefined' ? ',' : new_vars[i].separator;
                    new_url += c == 0 ? '?' : '&';
                    var val = [];
                    for (var g in new_vars[i].values)
                    {
                        val.push(this.encodeUrlComponent(new_vars[i].values[g]));
                    }
                    new_url += i + '=' + val.join(new_vars[i].separator);
                    c++;
                }
            }
        }
        
        new_url += document.location.hash;
        
        this.replaceUrl({
            url : new_url,
            replace_history : vars.replace_history
        });
    }

    addUrlParams(vars)
    {
        vars = Object.assign({
            url : false,
            vars : {},
            remove_existing : false,
            replace_history : true
        }, vars);
        
        var new_vars;
        
        if (vars.remove_existing == true)
        {
            new_vars = vars.vars;
        }
        else
        {
            new_vars = this.getUrlParams();
            for (var i in vars.vars)
            {
                new_vars[i] = vars.vars[i];
            }
        }
        
        this.setUrlParamsVars({url : vars.url, vars : new_vars, replace_history : vars.replace_history});

    }

    removeUrlParams(vars)
    {
        vars = Object.assign({
            vars : {},
            replace_history : true
        }, vars);
        

        var new_vars = this.getUrlParams();
        for (var i in vars.vars)
        {
            if (typeof new_vars[vars.vars[i]] != 'undefined')
            {
                delete new_vars[vars.vars[i]];
            }
        }

        this.setUrlParamsVars({vars : new_vars, replace_history : vars.replace_history});

    }
}