import controller from './models/controller.js';

import DLAnimate from './../libs/dl-animate-module.js';

export default class extends controller {

    #DLAnimate;
    #animation_end_promise = null;
    #animation_going = false;
    #animation_going_type = false;
    #first_time_animate = true;
    #start_resolve;
    #wrapper;
    #container;
    #box_item;
    #on_close;
    #on_bg_click;
    #is_closing = false;
    #content_item = null;
    #options = {
        type : 'box',
        animate : true,
        animate_settings : {
            single_show : {
                mode : 'together', // || after_bg || before_bg
                box : true,
                bg : true,
            },
            single_hide : {
                mode : 'together',
                box : true,
                bg : true
            },
            multiple_show : {
                mode : 'together', // || after_last_box
                delay : 100,
                box : true
            },
            multiple_hide : {
                box : true,
                cancel : false
            },
        },
        animate_class_prefix : 'fade',
        block_close : false,
        bg_ready : ()=>{},
        box_ready_ft : ()=>{},
        box_ready : ()=>{},
        bg_hidden : ()=>{},
        box_hidden : ()=>{},
        box_init : ()=>{},
        box_destroy : ()=>{},
        title : '',
        content : '',
        paddingsH : 5,
        paddingsV : 5,
        maxWidth : 'none',
        minWidth : 0,
        maximaze_width : false,
        close_on_bg_click : true
    };

    constructor(api, id, options)
    {
        super(api);

        this.assingOptions(options);
        this.id = id;

        this.is_showing = false;

        this.#wrapper = document.createElement('DIV');

        this.#DLAnimate = new DLAnimate();

        this.#animation_end_promise = new Promise((resolve)=> {
            this.#start_resolve = resolve;
        });
    }

    getWrapper() {
        return this.#wrapper;
    }

    formInitWrapper()
    {
        this.#wrapper.setAttribute('id', 'box_wrapper_'+this.id);
        this.#wrapper.setAttribute('data-box-id', this.id);
        this.#wrapper.classList.add('api_box_wrapper');
        this.#wrapper.classList.add(this.getApi().getFuncs().isTouchDevice() ? 'touch_device' : 'not_touch_device');

        var table = document.createElement('DIV');
        table.classList.add('table');
        var td = document.createElement('DIV');
        td.classList.add('td');
        this.#container = document.createElement('DIV');
        this.#container.classList.add('container');
        this.#box_item = document.createElement('DIV');
        this.#box_item.classList.add('box_item');

        this.#container.appendChild(this.#box_item);
        td.appendChild(this.#container);
        table.appendChild(td);
        this.#wrapper.appendChild(table);

        this.#container.style.padding = this.#options.paddingsV+'px '+this.#options.paddingsH+'px';
        this.#container.style.maxWidth = typeof this.#options.maxWidth == 'number' ? (this.#options.maxWidth + (this.#options.paddingsH * 2)) +'px' : this.#options.maxWidth;
        this.#container.style.minWidth = typeof this.#options.minWidth == 'number' && this.#options.minWidth > 0 ? (this.#options.minWidth + (this.#options.paddingsH * 2)) +'px' : this.#options.minWidth;
        if (this.#options.maximaze_width)
        {
            this.#container.style.width = '100%';
        }

        var content_item;
        if (this.#options.type == 'box')
        {
            this.#box_item.innerHTML = `
                <div class="box_item_std_wrapper">
                    <div class="top">
                        `+ (this.#options.title.length > 0 ? `<div class="title">${this.#options.title}</div>` : ``) +`
                        <div class="close"><a href="javascript://" title="Закрыть"><span>&nbsp;</span></a></div>
                    </div>
                    <div class="content"></div>
                </div>
            `;


            this.#content_item = this.#box_item.querySelector(`.box_item_std_wrapper > .content`);
            this.#content_item.innerHTML = this.#options.content;
            this.#on_close = () => {
                this.close(true);
            };
            this.#box_item.querySelector(`.box_item_std_wrapper > .top > .close > a`).addEventListener('click', this.#on_close);
        }
        else if (this.#options.type == 'html')
        {
            this.#content_item = this.#box_item;
            this.#content_item.innerHTML = this.#options.content;
        }

        this.#on_bg_click = (e) =>
        {
            if (this.getApi().getFuncs().isTouchDevice() == false && e.target.querySelector('.box_item') !== null)
            {
                if (this.#options.close_on_bg_click)
                {
                    this.close(true);
                }
            }
        };
        this.#wrapper.addEventListener('click', this.#on_bg_click);

        this.#options.box_init(this, this.#content_item);

        this.#first_time_animate = true;
    }


    #show_reject;
    showWrapper(is_single, last_box)
    {
        var p_all_r;
        var p_all = new Promise((resolve) => { 
            p_all_r = resolve;
        });

        //this.execOnAnimationEnd().then(()=> {

            this.#animation_going = true;
            this.#animation_going_type = 'show';
            var ready_resolve;
            this.#animation_end_promise = new Promise((resolve) => {
                ready_resolve = resolve;
            });

            var p1r;
            var p2r;
            var p1 = new Promise((resolve, reject) => { 
                p1r = resolve;
                this.#show_reject = reject;
            });

            var p2 = new Promise((resolve) => { 
                p2r = resolve;
            });

            let show_box_item = (is_animate) =>
            {
                return new Promise((ar) => {
                    if (is_animate)
                    {
                        this.#DLAnimate.show(this.#box_item, {
                            name: this.#options.animate_class_prefix,
                            afterEnter : () => {
                                ar();
                                this.execEvent('box_ready');
                            }
                        });
                    }
                    else
                    {
                        this.#box_item.style.display = 'block';
                        ar();
                        this.execEvent('box_ready');
                    }
                });
            };

            if (this.is_showing == false)
            {
                
                this.#box_item.style.display = 'none';
                this.#wrapper.style.display = 'block';

                if (is_single)
                {
                    if (this.#options.animate)
                    {
                        if (this.getApi().getFuncs().isTouchDevice() == false)
                        {
                            if (this.#options.animate_settings.single_show.mode == 'together')
                            {
                                this.getApi().getBoxes().showBG(this.#options.animate_settings.single_show.bg).then(() => {
                                    p2r();
                                    this.execEvent('bg_ready');
                                });
                                show_box_item(this.#options.animate_settings.single_show.box).then(() => { p1r(); });
                            }
                            else if (this.#options.animate_settings.single_show.mode == 'after_bg')
                            {
                                this.getApi().getBoxes().showBG(this.#options.animate_settings.single_show.bg).then(() => {
                                    p2r();
                                    this.execEvent('bg_ready');
                                    show_box_item(this.#options.animate_settings.single_show.box).then(() => { p1r(); });
                                });
                            }
                            else if (this.#options.animate_settings.single_show.mode == 'before_bg')
                            {
                                show_box_item(this.#options.animate_settings.single_show.box).then(() => {
                                    p1r();
                                    this.getApi().getBoxes().showBG(this.#options.animate_settings.single_show.bg).then(() => {
                                        p2r();
                                        this.execEvent('bg_ready');
                                    });
                                });
                            }
                        }
                        else
                        {
                            this.getApi().getBoxes().showBG(false).then(() => {
                                p2r();
                                this.execEvent('bg_ready');
                                show_box_item(this.#options.animate_settings.single_show.box).then(() => { p1r(); });
                            });
                        }
                    }
                    else
                    {
                        this.getApi().getBoxes().showBG(false).then(() => {
                            p2r();
                            this.execEvent('bg_ready');
                            show_box_item(this.#options.animate_settings.single_show.box).then(() => { p1r(); });
                        });
                    }
                }
                else
                {
                    last_box.execOnAnimationEnd().then(()=> {

                        if (this.#options.animate)
                        {
                            if (this.#options.animate_settings.multiple_show.mode == 'together')
                            {
                                last_box.hideWrapper(false).then(() => {
                                    p2r();
                                    this.execEvent('bg_ready');
                                });

                                if (this.#options.animate_settings.multiple_show.delay > 0)
                                {
                                    setTimeout(() =>
                                    {
                                        show_box_item(this.#options.animate_settings.multiple_show.box).then(() => { p1r(); });
                                    }, this.#options.animate_settings.multiple_show.delay);
                                }
                                else
                                {
                                    show_box_item(this.#options.animate_settings.multiple_show.box).then(() => { p1r(); });
                                }
                            }
                            else if (this.#options.animate_settings.multiple_show.mode == 'after_last_box')
                            {
                                last_box.hideWrapper(false).then(() => {
                                    p2r();
                                    this.execEvent('bg_ready');
                                    if (this.#options.animate_settings.multiple_show.delay > 0)
                                    {
                                        setTimeout(() =>
                                        {
                                            show_box_item(this.#options.animate_settings.multiple_show.box).then(() => { p1r(); });
                                        }, this.#options.animate_settings.multiple_show.delay);
                                    }
                                    else
                                    {
                                        show_box_item(this.#options.animate_settings.multiple_show.box).then(() => { p1r(); });
                                    }
                                });
                            }
                        }
                        else
                        {
                            last_box.hideWrapper(false).then(() => {
                                p2r();
                                this.execEvent('bg_ready');
                                show_box_item(false).then(() => { p1r(); });
                            });
                        }
                    });
                }
            }
            else
            {
                this.#show_reject();
            }

            Promise.all([p1, p2])
            .then(() =>
            {
                this.is_showing = true;
                p_all_r();

                if (this.#options.animate)
                {
                    this.#DLAnimate.raf(() => {
                        this.#DLAnimate.raf(() => {
                            this.#animation_going = false;
                            ready_resolve();
                        });
                    });
                }
                else
                {
                    this.#animation_going = false;
                    ready_resolve();
                }
            })
            .catch((e) => {
                p_all_r();
                this.#animation_going = false;
                ready_resolve();
            });
        //});

        return p_all;
    }

    hideWrapper(is_single)
    {
        var p_all_r;
        var p_all = new Promise((resolve) => { 
            p_all_r = resolve;
        });

        this.execOnAnimationEnd().then(()=> {

            let cancel_display_none = false;

            this.#animation_going = true;
            this.#animation_going_type = 'hide';
            var ready_resolve;
            this.#animation_end_promise = new Promise((resolve) => {
                ready_resolve = resolve;
            });

            var p1r;
            var p2r;
            var pr;
            var p1 = new Promise((resolve, reject) => { 
                p1r = resolve;
                pr = reject;
            });

            var p2 = new Promise((resolve) => { 
                p2r = resolve;
            });

            let hide_box_item = (is_animate) =>
            {
                return new Promise((ar) => {
                    if (is_animate)
                    {
                        this.#DLAnimate.hide(this.#box_item, {
                            name: this.#options.animate_class_prefix,
                            afterLeave : (e) => {
                                ar();
                                this.execEvent('box_hidden');
                            }
                        });
                    }
                    else
                    {
                        this.#box_item.style.display = 'none';
                        ar();
                        this.execEvent('box_hidden');
                    }
                });
            };

            if (this.is_showing)
            {
                this.is_showing = false;

                if (is_single)
                {
                    if (this.#options.animate)
                    {
                        if (this.getApi().getFuncs().isTouchDevice() == false)
                        {
                            if (this.#options.animate_settings.single_hide.mode == 'together')
                            {
                                this.getApi().getBoxes().hideBG(this.#options.animate_settings.single_hide.bg).then(() => {
                                    p2r();
                                    this.execEvent('bg_hidden');
                                }).catch((e) => {
                                    console.log(e);
                                });
                                hide_box_item(this.#options.animate_settings.single_hide.box).then(() => {
                                    p1r();
                                });
                                
                            }
                            else if (this.#options.animate_settings.single_hide.mode == 'after_bg')
                            {
                                this.getApi().getBoxes().hideBG(this.#options.animate_settings.single_hide.bg).then(() => {
                                    p2r();
                                    this.execEvent('bg_hidden');
                                    hide_box_item(this.#options.animate_settings.single_hide.box).then(() => { p1r(); });
                                });
                            }
                            else if (this.#options.animate_settings.single_hide.mode == 'before_bg')
                            {
                                hide_box_item(this.#options.animate_settings.single_hide.box).then(() => {
                                    p1r();
                                    this.getApi().getBoxes().hideBG(this.#options.animate_settings.single_hide.bg).then(() => {
                                        p2r();
                                        this.execEvent('bg_hidden');
                                    });
                                });
                            }
                        }
                        else
                        {
                            this.getApi().getBoxes().hideBG(false).then(() => {
                                p2r();
                                this.execEvent('bg_hidden');
                                hide_box_item(this.#options.animate_settings.single_hide.box).then(() => { p1r(); });
                            });
                        }
                    }
                    else
                    {
                        this.getApi().getBoxes().hideBG(false).then(() => {
                            p2r();
                            this.execEvent('bg_hidden');
                            hide_box_item(false).then(() => { p1r(); });
                        });
                    }
                }
                else
                {
                    if (this.#options.animate_settings.multiple_hide.cancel)
                    {
                        cancel_display_none = true;
                        p1r();
                        p2r();
                    }
                    else
                    {
                        hide_box_item(this.#options.animate && this.#options.animate_settings.multiple_hide.box).then(() =>
                        {
                            p1r();
                            p2r();
                        });
                    }
                }
            }
            else
            {
                pr();
            }

            Promise.all([p1, p2])
            .then(() =>
            {
                if (cancel_display_none == false)
                {
                    this.#box_item.style.display = 'none';
                    this.#wrapper.style.display = 'none';
                }

                p_all_r();

                if (this.#options.animate)
                {
                    this.#DLAnimate.raf(() => {
                        this.#DLAnimate.raf(() => {
                            this.#animation_going = false;
                            ready_resolve();
                        });
                    });
                }
                else
                {
                    this.#animation_going = false;
                    ready_resolve();
                }
            })
            .catch((e) => {
                p_all_r();
                this.#animation_going = false;
                ready_resolve();
            });

        });

        return p_all;
    }

    destroy(full = true)
    {
        if (this.#options.type == 'box')
        {
            this.#box_item.querySelector(`.box_item_std_wrapper > .top > .close > a`).removeEventListener('click', this.#on_close);
        }
        this.#wrapper.removeEventListener('click', this.#on_bg_click);

        this.#options.box_destroy(this, this.#content_item, full);

        if (full)
        {
            this.#animation_end_promise = new Promise((resolve) => {
                resolve();
            });
        }
    }

    close(from_user = false)
    {
        if (from_user && this.#options.block_close)
        {
            return false;
        }

        if (this.#is_closing == false)
        {
            this.#is_closing = true;
            this.execOnAnimationEnd().then(()=> {
                this.getApi().getBoxes().remove(this.id);
            });
        }
    }

    setNewOptions(options)
    {
        this.destroy(false);
        this.assingOptions(options);
        this.#wrapper.removeChild(this.#wrapper.children[0]);
        this.formInitWrapper();
    }

    setId(id)
    {
        this.id = id;
        this.#wrapper.setAttribute('id', 'box_wrapper_'+this.id);
    }

    scrollToTop()
    {
        this.#wrapper.scrollTop = 0;
    }

    assingOptions(options)
    {
        if (options.animate_settings !== undefined)
        {
            for (let i in this.#options.animate_settings)
            {
                if (options.animate_settings[i] !== undefined)
                {
                    Object.assign(this.#options.animate_settings[i], options.animate_settings[i]);
                }
            }
        }
        options.animate_settings = this.#options.animate_settings;
        Object.assign(this.#options, options);
    }

    cancelBoxAppear()
    {
        if (this.is_showing == false && this.#animation_going)
        {
            this.#show_reject();
            this.#wrapper.style.display = 'none';
        }
    }

    execOnAnimationEnd()
    {
        return this.#animation_end_promise;
    }

    #bg_ready_ft = true;
    execEvent(event)
    {
        if (event == 'bg_ready')
        {
            this.#options.bg_ready(this);
        }
        else if (event == 'box_ready')
        {
            if (this.#bg_ready_ft)
            {
                this.#bg_ready_ft = false;
                this.#options.box_ready_ft(this);
            }
            this.#options.box_ready(this);
        }
        else if (event == 'bg_hidden')
        {
            this.#options.bg_hidden(this);
        }
        else if (event == 'box_hidden')
        {
            this.#options.box_hidden(this);
        }
    }
}