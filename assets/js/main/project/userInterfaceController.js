import projectController from './../api/models/projectController.js';

import axios from 'axios';
import DLAnimate from './../libs/dl-animate-module.js';
import Cookies from 'js-cookie';

export default class extends projectController {

    constructor(api, projectApi) {
        
        super(api, projectApi);

        this.is_menu_showing = false;
        this.is_menu_showing_block = false;
        this.is_widjet_showing = false;
        this.is_widjet_showing_block = false;
        this.DLAnimate = new DLAnimate();
    }

    init()
    {
        this.sel_sub_menu_value = this.getProjectApi().tplVars.selSubMenu;
        document.querySelectorAll('#menu_list > a').forEach((a) => {
            a.addEventListener('click', () => {
                this.toogleMenu(false);
            });
        });

        this.addCookiesBar();
        this.pageUpdated(true);
    }

    pageUpdated(is_init, oldVars = {})
    {
        this.selSubMenu(this.getProjectApi().tplVars.selSubMenu);

        let url_vars = this.getApi().getFuncs().getUrlParams();
        if (typeof url_vars.scrollToBlock != 'undefined')
        {
            let scroll_block = url_vars.scrollToBlock;
            this.getApi().getFuncs().removeUrlParams({vars : ['scrollToBlock']});

            this.getApi().getFuncs().windowScrollToBlock(scroll_block);
        }
    }

  
    selSubMenu(v)
    {
        let a = document.querySelector('#header_submenu_wrapper a[data-id="'+v+'"]');

        if (a !== null)
        {
            if (v != this.sel_sub_menu_value)
            {
                document.querySelectorAll('#header_submenu_wrapper a').forEach((a) => {
                    a.classList.remove('sel');
                });
                a.classList.add('sel');
                this.sel_sub_menu_value = v;
            }

            if (window.innerWidth <= 1000)
            {
                let sp = window.innerWidth <= 580 ? 15 : 30;
                let c = document.querySelector('#header_submenu_wrapper > div');
                let m = a.getBoundingClientRect().left - c.getBoundingClientRect().left - sp;
                document.querySelector('#header_submenu_wrapper > div').scrollLeft = m;
            }
        }
    }


    checkAnimOnScroll()
    {
        document.querySelectorAll('.item_anim').forEach((i) => {
            i.classList.add('r');
        });

        let f = () => {
            document.querySelectorAll('.item_anim').forEach((i) => {
                if (i.classList.contains('go') == false)
                {
                    let t = i.getBoundingClientRect().top;
                    let b = i.getBoundingClientRect().bottom;
                    if (t - window.innerHeight < -100 && b > 100)
                    {
                        let end = (e) => {
                            console.log()
                            if (e.target == i)
                            {
                                i.removeEventListener('transitionend', end);
                                i.classList.remove('go');
                                i.classList.remove('item_anim');
                                i.classList.remove('r');
                            }
                        };
                        i.addEventListener('transitionend', end);
                        i.classList.add('go');
                    }
                }
            });
        };

        f();

        window.addEventListener('scroll', f.bind(this));
    }

    //Открыть и скрыть главное меню
    toogleMenu(action = null)
    {
        if (this.is_menu_showing_block == false)
        {
            this.is_menu_showing_block = true;
            if (action === true || this.is_menu_showing == false)
            {
                this.is_menu_showing = true;
                document.getElementById('menu_top_wrapper').classList.add('opened');
                this.DLAnimate.show(document.getElementById('menu_top_list'), {
                    name: 'fade',
                    afterEnter : () => {
                        this.is_menu_showing_block = false;
                    }
                });
            }
            else if (action === false || this.is_menu_showing)
            {
                this.is_menu_showing = false;
                document.getElementById('menu_top_wrapper').classList.remove('opened');
                this.DLAnimate.hide(document.getElementById('menu_top_list'), {
                    name: 'fade',
                    afterLeave : () => {
                        this.is_menu_showing_block = false;
                        document.getElementById('menu_top_list').removeAttribute('style');
                    }
                });
            }
        }

        return false;
    }

    addCookiesBar()
    {
        if (Cookies.get('showCookieBar') != 'yes')
        {
            let cookieBar = window.document.createElement('div');
            cookieBar.setAttribute('id', 'cookie_bar_wrapper');
            cookieBar.innerHTML = `<div><div class="text">Мы используем файлы cookie. Продолжив работу с сайтом, вы соглашаетесь с <a href="/upload/media/documents/0001/01/2854a85e4ea8ce9412ee86cd53da65329da320a8.pdf" target="_blank">политикой конфиденциальности</a>.</div><div class="close"><a href="javascript://">&nbsp;</a></div></div>`;
            window.document.body.appendChild(cookieBar);
            cookieBar.querySelector('.close a').addEventListener('click', (e)=> {
                e.preventDefault();
                Cookies.set('showCookieBar', 'yes', { expires: 365});
                window.document.body.removeChild(cookieBar);
            });
        }
    }

    //Список каналов для ТВ тарифа

    showTvPlanChannelsList(id, type = 'plan')
    {
        this.getApi().getFuncs().loadBox((resolve, reject) =>
        {
            //setTimeout(() => {
                axios.get(window.channel_list_callback(id)).then((response) =>
                {
                    let content = `<div class="box_tv_plan_content">
                        <div class="title">`+(type == 'plan' ? 'Тариф' : 'Пакет каналов')+` «${response.data.name}»</div>
                        <div class="count">Количество каналов: <span>${response.data.channels.length}</span></div>
                        <div class="items">`;

                        response.data.channels.forEach((e) => {
                            content += `<div>
                                <div style="background-image:url(${e.image})" title="${e.name}"><div></div></div>
                            </div>`;
                        });

                    content += `
                        </div>
                        <div class="close">
                            <a href="javascript://" class="button_3 close_this_box">Закрыть</a>
                        </div>
                    </div>`;

                    resolve({
                        content : content
                    });

                }).catch(()=> {
                    reject();
                });
            //}, 3000);
        }, {
            animate : true,
            minWidth : 300,
            maxWidth : 805,
            maximaze_width : true,
            close_on_bg_click : true,
            wrapper_extra_classes : 'box_tv_plan_wrapper'
        });

        return false;
    }

    //Кинотеатр

    showTvTheaherInfo(id, title, description, image)
    {
        let content = `<div class="box_tv_theather_content">
            <div class="image" style="background-image:url(${image})"><div></div></div>
            <div class="title">${title}</div>
            `+(description !== null ? `<div class="desc">${description}</div>` : '')+`
            <div class="close"><a href="javascript://" class="button_3 close_this_box">Закрыть</a></div>
        </div>`;

        this.getApi().getBoxes().create({
            animate : true,
            minWidth : 300,
            maxWidth : 600,
            maximaze_width : true,
            close_on_bg_click : true,
            wrapper_extra_classes : 'box_tv_theather_wrapper',
            content : content
        });
    }

    //Перейти к оплате
    goToPayment()
    {
        if ((document.location.pathname.length == 0 || document.location.pathname == '/') == false)
        {
            window.location.href = '/?scrollToBlock=main_6';
        }
        else
        {
            this.getApi().getFuncs().windowScrollToBlock('main_6');
        }

        return false;
    }

}
