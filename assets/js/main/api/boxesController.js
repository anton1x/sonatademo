import controller from './models/controller.js';
import boxController from './boxController.js';

import DLAnimate from './../libs/dl-animate-module.js';

export default class extends controller {

    #window_height = 0;
    #boxes = [];
    #id_counter = 0;
    #boxes_container;
    #boxes_bg;
    #bg_showing = false;
    #hide_bg_animation = false;
    #hide_bg_canceled = false;
    #hiding_bg_timeout = 0;
    #body_scroll_value = 0;
    #DLAnimate;

    constructor(api) {
        
        super(api);

        window.addEventListener('resize', () => {
            this.makeMobileDeviceFixes();
        });
        this.makeMobileDeviceFixes();

        this.#boxes_container = document.createElement('DIV');
        this.#boxes_bg = document.createElement('DIV');
        this.#boxes_bg.setAttribute('id', 'boxes_container_bg');
        this.#boxes_bg.style.display = 'none';
        this.#boxes_container.appendChild(this.#boxes_bg);
        document.body.appendChild(this.#boxes_container);

        this.#DLAnimate = new DLAnimate();

        document.addEventListener('keydown', (e) => {
            e = e || window.event;
            let isEscape = false;
            if ("key" in e) {
                isEscape = (e.key === "Escape" || e.key === "Esc");
            } else {
                isEscape = (e.keyCode === 27);
            }
            if (isEscape && this.#boxes.length > 0)
            {
                this.#boxes[(this.#boxes.length - 1)].close(true);
            }
        });
    }

    makeMobileDeviceFixes()
    {
        if (this.getApi().getFuncs().isTouchDevice())
        {
            if (this.#window_height != window.innerHeight)
            {
                this.#window_height = window.innerHeight;
                let vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }
        }
    }

    isBoxSingle() {
        return this.#boxes.length > 1 ? false : true;
    }

    getBoxById(id) {
        return this.#boxes.find(box => box.id === id);
    }

    create(options = {}) {

        options = Object.assign({
            update_if_exists : true,
            recreation : true
        }, options);

        let id = (this.#id_counter++).toString();
        if (options.id !== undefined)
        {
            let check_box = this.getBoxById(options.id);
            if (options.update_if_exists && check_box !== undefined)
            {
                if (options.recreation)
                {
                    var box_copy = new boxController(this.getApi(), options.id+'_new_copy', options);
                    this.#boxes.push(box_copy);
                    this.#boxes_container.appendChild(box_copy.getWrapper());
                    box_copy.formInitWrapper();

                    box_copy.showWrapper(false, check_box).then(() => {
                        this.removeFromList(check_box.id);
                        box_copy.setId(options.id);
                    });

                    return box_copy;
                }
                else
                {
                    check_box.setNewOptions(options);
                    this.toTop(check_box.id);
                    return check_box;
                }
            }
            else if (options.update_if_exists == false && check_box !== undefined)
            {
                return undefined;
            }

            id = options.id;
            delete options.id;
        }
        delete options.recreation;
        delete options.update_if_exists;

        var box = new boxController(this.getApi(), id, options);
        this.#boxes.push(box);
        this.#boxes_container.appendChild(box.getWrapper());
        box.formInitWrapper();

        if (this.isBoxSingle())
        {
            this.makeDocumentProperites();
            box.showWrapper(true).then(() => {
                //All ready
            });
        }
        else
        {
            box.showWrapper(false, this.#boxes[this.#boxes.length - 2]).then(() => {
                //All ready
            });
        }

        return box;
    }

    makeDocumentProperites()
    {
        if (this.isBoxSingle())
        {
            if (this.getApi().getFuncs().isTouchDevice() == false)
            {
                this.#body_scroll_value = document.documentElement.scrollTop;
                for (let i of document.getElementsByClassName('fixed_bc'))
                {
                    i.style.marginTop = this.#body_scroll_value+'px';
                    i.style.position = 'absolute';
                }
                for (let i of document.getElementsByClassName('fixed_bc_left'))
                {
                    i.customLeft = i.getBoundingClientRect().left;
                }
                document.documentElement.classList.add('boxed');
                document.getElementById('all').classList.add('boxed');
                this.#boxes_bg.classList.add('not_touch_device');
                for (let i of document.getElementsByClassName('fixed_bc_left'))
                {
                    let dif = i.getBoundingClientRect().left - i.customLeft;
                    i.style.marginRight = dif+'px';
                }
            }
            else
            {
                this.#body_scroll_value = document.scrollingElement ? document.scrollingElement.scrollTop : window.document.body.scrollTop;
                document.body.classList.add('lock_position');
                this.#boxes_bg.classList.add('touch_device');
            }
        }
    }

    clearDocumentProperites()
    {
        if (this.isBoxSingle())
        {
            if (this.getApi().getFuncs().isTouchDevice() == false)
            {
                document.documentElement.classList.remove('boxed');
                document.getElementById('all').classList.remove('boxed');
                this.#boxes_bg.classList.remove('not_touch_device');

                for (let i of document.getElementsByClassName('fixed_bc'))
                {
                    i.style.marginTop = 0;
                    i.style.position = 'fixed';
                }
                for (let i of document.getElementsByClassName('fixed_bc_left'))
                {
                    i.style.marginRight = 0;
                }
            }
            else
            {
                if (document.scrollingElement)
                {
                    document.scrollingElement.scrollTop = this.#body_scroll_value;
                }
                else
                {
                    window.document.body.scrollTop = this.#body_scroll_value;
                }
                document.body.classList.remove('lock_position');
                this.#boxes_bg.classList.remove('touch_device');
            }
        }
    }

    getBoxBluring()
    {
        return document.getElementsByClassName('box_bluring');
    }

    showBG(is_animate)
    {
        return new Promise((resolve) => {
            if (this.isBoxSingle())
            {
                if (this.getApi().getFuncs().isTouchDevice() == false)
			    {
                    for (let i of this.getBoxBluring())
                    {
                        if (is_animate)
                        {
                            i.classList.add('animate');
                        }
                        i.classList.add('go');
                    }

                    if (is_animate)
                    {
                        this.#DLAnimate.show(this.#boxes_bg, {
                            name: 'fade',
                            afterEnter : () => {
                                resolve();
                            }
                        });
                    }
                    else
                    {
                        this.#boxes_bg.style.display = 'block';
                        resolve();
                    }
                }
                else
                {
                    this.#boxes_bg.style.display = 'block';
                    resolve();
                }
            }
            else
            {
                resolve();
            }
        });
    }

    hideBG(is_animate)
    {
        return Promise.all([
            new Promise((resolve, reject) => {
                if (this.isBoxSingle())
                {
                    if (this.getApi().getFuncs().isTouchDevice() == false)
                    {
                        for (let i of this.getBoxBluring())
                        {
                            i.classList.remove('animate');
                            i.classList.remove('go');
                        }
                        resolve();
                    }
                    else
                    {
                        resolve();
                    }
                }
                else
                {
                    resolve();
                }
            }),
            new Promise((resolve, reject) => {
                if (this.isBoxSingle())
                {
                    if (this.getApi().getFuncs().isTouchDevice() == false)
                    {
                        if (is_animate)
                        {
                            this.#DLAnimate.hide(this.#boxes_bg, {
                                name: 'fade',
                                afterLeave : () => {
                                    resolve();
                                }
                            });
                        }
                        else
                        {
                            this.#boxes_bg.style.display = 'none';
                            resolve();
                        }
                    }
                    else
                    {
                        resolve();
                    }
                }
                else
                {
                    resolve();
                }
            })
        ]);
    }

    remove(id)
    {
        let box = this.getBoxById(id);
        if (box !== undefined)
        {
            if (box.is_showing == false)
            {
                this.removeFromList(box.id);
            }
            else if (this.isBoxSingle())
            {
                box.hideWrapper(true)
                .then(() => {
                    return new Promise((resolve) => {
                        if (this.getApi().getFuncs().isTouchDevice() == false)
                        {
                            this.clearDocumentProperites();
                            resolve();
                        }
                        else
                        {
                            this.clearDocumentProperites();
                            setTimeout(() => {
                                this.#boxes_bg.style.display = 'none';
                                resolve();
                            }, 50);
                        }
                    });
                })
                .then(() =>
                {
                    this.removeFromList(box.id);
                });
            }
            else
            {
                this.#boxes[this.#boxes.length - 2].showWrapper(false, box).then(() =>
                {
                    this.removeFromList(box.id);
                });
            }
        }
    }

    removeFromList(id)
    {
        let f = undefined;
        for (let i in this.#boxes)
        {
            if (this.#boxes[i].id == id)
            {
                f = i;
                break;
            }
        }

        if (f !== undefined)
        {
            this.#boxes[f].destroy();
            this.#boxes_container.removeChild(this.#boxes[f].getWrapper());
            this.#boxes.splice(f, 1);
        };
    }

    toTop(id)
    {
        let need_box = this.getBoxById(id);
        if (need_box !== undefined && this.#boxes[this.#boxes.length - 1].id != id)
        {
            let now_box = this.#boxes[this.#boxes.length - 1];

            this.#boxes.sort((x,y) => { 
                return x.id == id ? 1 : y.id == id ? -1 : 0; 
            });

            need_box.showWrapper(false, now_box).then(() => {
                //All ready
            });
        }
    }
}