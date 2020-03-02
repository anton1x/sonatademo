import projectController from './../api/models/projectController.js';

import axios from 'axios';
import DLAnimate from './../libs/dl-animate-module.js';


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

        this.pageUpdated(true);
    }

    pageUpdated(is_init, oldVars = {})
    {
        this.selSubMenu(this.getProjectApi().tplVars.selSubMenu);
    }

  
    selSubMenu(v)
    {
        let a = document.querySelector('#header_submenu_wrapper a[data-id="'+v+'"]');

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


}