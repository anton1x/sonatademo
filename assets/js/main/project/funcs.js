import projectController from './../api/models/projectController.js';

import axios from 'axios';
import DLAnimate from './../libs/dl-animate-module.js';

export default class extends projectController {

    constructor(api, projectApi) {
        
        super(api, projectApi);
    }

    #anim_id_counter = 1;
    #z_index_id_counter = 1;
    addAnimShow(items)
    {
        items.forEach((item) =>
        {
            let item_id = (this.#anim_id_counter++);

            item.setAttribute('data-anim-spec-id', item_id.toString());
            this.getApi().getFuncs().addMouseEnterEvents(item);

            item.is_entered = false;

            item.f_enter = (it) => {
                if (it.is_entered)
                {
                    return;
                }
                it.is_entered = true;

                let sb = it.querySelector('.short_block');
                sb.anim_step = 1;
                let hs = sb.offsetHeight;
                let isNew = false;

                if (typeof it.hs == 'undefined')
                {
                    it.hs = sb.offsetHeight;
                    isNew = true;
                }

                let endf = (e) => {
                    if (e.target == sb)
                    {
                        if (sb.anim_step == 2)
                        {
                            sb.removeEventListener('transitionend', endf);
                            sb.classList.remove('s1');
                            sb.style.removeProperty('max-height');
                            sb.classList.remove('full');
                            it.style.removeProperty('z-index');
                            delete it.hs;
                        }
                    }
                };
                sb.addEventListener('transitionend', endf);

                if (isNew)
                {
                    it.classList.add('s1');
                    sb.classList.add('full');
                    sb.hf = sb.offsetHeight;

                    sb.style.setProperty('max-height', hs+'px');
                    sb.classList.add('s1');
                    it.classList.remove('s1');
                    it.style.setProperty('z-index', (this.#z_index_id_counter++).toString());
                }

                this.getApi().getFuncs().nextAnimFrame(() => {
                    if (sb.anim_step == 1)
                    {
                        sb.classList.add('showing');
                        sb.style.setProperty('max-height', sb.hf+'px');
                    }
                });
            };

            item.f_leave = (it) => {
                if (it.is_entered == false)
                {
                    return;
                }
                it.is_entered = false;

                let sb = it.querySelector('.short_block');

                sb.anim_step = 2;
                sb.classList.remove('showing');
                sb.style.setProperty('max-height', it.hs+'px');
            };

            if (this.getApi().getFuncs().isTouchDevice())
            {
                item.addEventListener('click', (e) => {
                    item.f_enter(item);
                });

                window.addEventListener('touchstart', (e) => {
                    if (!(e.target == item || e.target.closest('[data-anim-spec-id="'+item_id.toString()+'"]') !== null))
                    {
                        item.f_leave(item);
                    }
                });
            }
            else
            {
                item.addEventListener('mouseenter', (e) => {
                    item.f_enter(item);
                });
                item.addEventListener('mouseleave', (e) => {
                    item.f_leave(item);
                });
            }

        });
    }


    validateForm(form, exec, validateFunc, errorsFunc) {

        let submit = () => {
            let formData = new FormData(form);
            let formJSONdata = Object.fromEntries(formData);
            let is_valid = true;

            if (typeof validateFunc == 'function')
            {
                let errors = validateFunc(formJSONdata, form);
                if (errors.length > 0)
                {
                    is_valid = false;
                    if (typeof errorsFunc == 'function')
                    {
                        errorsFunc(errors, form);
                    }
                }
            }

            if (is_valid)
            {
                if (typeof exec == 'function')
                {
                    exec(formJSONdata, formData, form);
                }
            }
        };

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            submit();
        });

        form.querySelectorAll('.submit_button').forEach((b) => {
            b.addEventListener('click', (e) => {
                e.preventDefault();
                submit();
            });
        });
    }

    showErrorsInBox(errors, options)
    {
        let text = '<div>' + errors.join('</div><div>') + '</div>';
        let html = `<div class="errors_in_box_wrapper">
            <div class="text">${text}</div>
            <div class="button"><a href="javascript://" class="button_3" onclick="eApi.getBoxes().getBoxById('errors_in_box').close(); return false;">OK</a>
        </div>`;

        options = Object.assign({
            id : 'errors_in_box',
            animate : true,
            minWidth : 300,
            maxWidth : 760,
            close_on_bg_click : true
        }, options);

        options.content = html;

        this.getApi().getBoxes().create(options);
    }
}