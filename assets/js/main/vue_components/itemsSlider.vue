<script>

export default {
    
    props : ['test'],
    data () {
        return {
            is_touch : false,
            list : [],
            slide_width : 100,
            flags : {
                transition_going : false,
                window_width : 0
            },
            inline_slider_wrapper_styles : {},
            items_container_wrapper_styles : {},
            items_container_wrapper_classes : {touched : false},
            items_container_styles : {},
            arrows_styles : {
                
            },
            points_styles : {
                
            },
            selected_point : 0,
            position : Infinity,
            position_anim : Infinity,
            animation_event_disable : false,
            animate_time : '0.3s',
            points_c_width : 210,
            points_item_width : 42,
            position_for_move : 0,
            position_for_move_for_links : 0,
            mouse_clicked : false,
            clicked_link_can : false,
            touch_start_time : 0,
            touch_swiped : false,
            mouse_entered : false,
        }
    },
    methods : {
        buildItemsList() {
            let list = [];
            let points_classes = [];
            for (let i in this.$slots.default) {
                if (this.$slots.default[i].tag == 'div')
                {
                    let attrs = typeof this.$slots.default[i].data == 'object' && typeof this.$slots.default[i].data.attrs == 'object' ? this.$slots.default[i].data.attrs : {};
                    let classes = {};
                    if (typeof this.$slots.default[i].data == 'object' && typeof this.$slots.default[i].data.staticClass == 'string')
                    {
                        let classes_arr = this.$slots.default[i].data.staticClass.split(' ');
                        for (let i of classes_arr)
                        {
                            classes[i] = true;
                        }
                    }
                    list.push({
                        minH : typeof attrs['data-min-height'] == 'string' ? attrs['data-min-height'] : '0px',
                        maxH : typeof attrs['data-max-height'] == 'string' ? attrs['data-max-height'] : 'none',
                        cl : classes,
                        c : this.$slots.default[i].children
                    });
                }
            }
            this.list = list;
        },
        rebuildSizesSettings() {
            if (this.flags.window_width != window.innerWidth)
            {
                this.flags.window_width = window.innerWidth;
                this.slide_width = this.$refs['inline_slider_wrapper'].offsetWidth;

                this.$nextTick(() => {
                    this.setPosition(0, false, true);
                });
            }
        },
        setPosition(value, is_animate, force = false) {

            let v = (value + 1) * this.slide_width;

            if (v != this.position_for_move || force == true)
            {
                this.clearTimeoutShow();
                if (is_animate)
                {
                    if (this.flags.transition_going)
                    {
                        return false;
                    }

                    this.flags.transition_going = true;
                    this.position_anim = value;
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-v)+'px)');
                    this.$refs['items_container'].style.setProperty('transition', 'transform '+this.animate_time);
                    if (value < 0)
                    {
                        value = this.list.length - 1;
                    }
                    else if (value > (this.list.length - 1))
                    {
                        value = 0;
                    }
                    this.position = value;
                    this.rebuildPointsList(true);
                }
                else
                {
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-v)+'px)');
                    this.position = value;
                    this.flags.transition_going = false;
                    this.rebuildPointsList(false);
                    this.position_for_move = this.countPosOffset();
                    this.startTimeoutShow();
                }
            }
        },
        itemsContainerTransitionEnd() {
            if (this.animation_event_disable == false)
            {
                this.$refs['items_container'].style.removeProperty('transition');
                if (this.position_anim < 0)
                {
                    this.position_anim = 0;
                    this.setPosition((this.list.length - 1), false, true);
                }
                else if (this.position_anim > (this.list.length - 1))
                {
                    this.position_anim = 0;
                    this.setPosition(0, false, true);
                }
                this.flags.transition_going = false;
                this.touch_swiped = false;
                this.position_for_move = this.countPosOffset();
                this.startTimeoutShow();
            }
            else
            {
                this.animation_event_disable = false;
            }
        },
        itemsPointsTransitionEnd() {
            this.$refs['points_c'].style.removeProperty('transition');
        },
        rebuildPointsListClasess() {
            for (let i in this.list)
            {
                while (this.$refs['point_'+i].classList.length > 0) {
                    this.$refs['point_'+i].classList.remove(this.$refs['point_'+i].classList.item(0));
                }

                if (i == this.position)
                {
                    this.$refs['point_'+i].classList.add('b3');
                }
                else if (i == (this.position - 1) || i == (this.position + 1))
                {
                    this.$refs['point_'+i].classList.add('b2');
                }
            }
        },
        rebuildPointsList(animate) {
            let v = (this.position * this.points_item_width) - ((this.points_c_width / 2) - (this.points_item_width / 2));

            if (animate)
            {
                this.$refs['points_c'].style.setProperty('transform', 'translateX('+(-v)+'px)');
                this.$refs['points_c'].style.setProperty('transition', 'transform '+this.animate_time);
                setTimeout(() => {
                    this.rebuildPointsListClasess();
                }, 100);
            }
            else
            {
                this.$refs['points_c'].style.setProperty('transform', 'translateX('+(-v)+'px)');
                this.rebuildPointsListClasess();
            }

        },
        stepLeft() {
            this.setPosition((this.position - 1), true);
        },
        stepRight() {
            this.setPosition((this.position + 1), true);
        },
        swipeLeft() {
            this.touch_swiped = true;
            this.stepRight();
        },
        swipeRight() {
            this.touch_swiped = true;
            this.stepLeft();
        },
        pointClick(e) {
            let a = e.target;
            if (e.target !== e.currentTarget) {
                a = e.target.closest('a');
            }
            let i = parseInt(a.getAttribute('data-i'));
            this.setPosition(i, true);
        },
        countPosOffset() {
            var parent_p = this.$refs['items_container_wrapper'].getBoundingClientRect();
            var item_p = this.$refs['items_container'].getBoundingClientRect();
            let pos = Math.round(item_p.left - parent_p.left);
            return -pos;
        },
        mouseDown(e)
        {
            if (e.button == 0 && this.is_touch == false)
            {
                e.preventDefault();
                this.clearTimeoutShow();
                this.mouse_clicked = true;
                this.mouse_clicked_x = e.clientX;
                this.position_for_move = this.countPosOffset();
                this.items_container_wrapper_classes.touched = true;

                if (this.flags.transition_going)
                {
                    this.$refs['items_container'].style.removeProperty('transition');
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-this.position_for_move)+'px)');
                    this.flags.transition_going = false;
                }
            }
        },
        mouseEnd(e)
        {
            if (e.button == 0 && this.is_touch == false)
            {
                if (this.mouse_clicked)
                {
                    this.mouse_clicked = false;
                    let p = Math.round(this.position_for_move / this.slide_width) - 1;
                    if (p < -1)
                    {
                        p = -1;
                    }
                    else if (p > this.list.length)
                    {
                        p = this.list.length;
                    }
                    this.setPosition(p, true);
                    this.items_container_wrapper_classes.touched = false;
                }
            }
        },
        mouseMove(e)
        {
            if (this.is_touch == false)
            {
                if (this.mouse_clicked)
                {
                    let offset = this.mouse_clicked_x - e.clientX;
                    this.mouse_clicked_x = e.clientX;
                    this.position_for_move += offset;
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-this.position_for_move)+'px)');
                }
            }
        },
        touchStart(e)
        {
            if (this.touch_swiped == false)
            {
                this.clearTimeoutShow();
                this.mouse_clicked = true;
                this.mouse_clicked_x_start = e.touches[0].clientX;
                this.mouse_clicked_x = e.touches[0].clientX;
                this.mouse_clicked_y = e.touches[0].clientY;
                this.position_for_move = this.countPosOffset();
                this.touch_start_time = new Date().getTime();

                if (this.flags.transition_going)
                {
                    this.$refs['items_container'].style.removeProperty('transition');
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-this.position_for_move)+'px)');
                    this.flags.transition_going = false;
                }
            }
        },
        touchEnd(e)
        {
            if (this.mouse_clicked)
            {
                this.mouse_clicked = false;
                let detect_swipe = false;

                if (new Date().getTime() - this.touch_start_time <= 300)
                {
                    if (this.mouse_clicked_x > this.mouse_clicked_x_start && (this.mouse_clicked_x - this.mouse_clicked_x_start) > 10)
                    {
                        detect_swipe = true;
                        this.swipeRight();
                    }
                    else if (this.mouse_clicked_x < this.mouse_clicked_x_start && (this.mouse_clicked_x - this.mouse_clicked_x_start) < -10)
                    {
                        detect_swipe = true;
                        this.swipeLeft();
                    }
                }

                if (detect_swipe == false)
                {
                    let p = Math.round(this.position_for_move / this.slide_width) - 1;
                    if (p < -1)
                    {
                        p = -1;
                    }
                    else if (p > this.list.length)
                    {
                        p = this.list.length;
                    }
                    this.setPosition(p, true);
                }
            }
        },
        touchMove(e)
        {
            if (this.mouse_clicked)
            {
                let offsetX = this.mouse_clicked_x - e.touches[0].clientX;
                let offsetY = this.mouse_clicked_y - e.touches[0].clientY;
                this.mouse_clicked_x = e.touches[0].clientX;
                this.mouse_clicked_y = e.touches[0].clientY;
                if (Math.abs(offsetX) > Math.abs(offsetY))
                {
                    e.preventDefault();
                    this.position_for_move += offsetX;
                    this.$refs['items_container'].style.setProperty('transform', 'translateX('+(-this.position_for_move)+'px)');
                }
            }
        },
        mouseEnter()
        {
            this.mouse_entered = true;
            this.clearTimeoutShow();
        },
        mouseLeave()
        {
            this.mouse_entered = false;
            this.startTimeoutShow();
        },
        clearTimeoutShow()
        {
            clearTimeout(this.timeout_show);
        },
        startTimeoutShow()
        {
            if (this.mouse_entered == false && this.mouse_clicked == false)
            {
                this.clearTimeoutShow();
                this.timeout_show = setTimeout(() => {
                    this.stepRight();
                }, 5000);
            }
        }
    },
    render(h) {

        var items = [];

        let f_add = (i) => {
            items.push(h('div', {
                style : {
                    width : this.slide_width+'px'
                }
            }, [h('div', {
                class : {
                    w : true
                },
                style : {
                    width : this.slide_width+'px',
                    minHeight : typeof this.list[i].cl.absolute != 'undefined' ? this.list[i].minH : '0px'
                }
            }, ''), h('div', {
                class : Object.assign({
                    c : true
                },
                this.list[i].cl),
                style : {
                    minHeight : typeof this.list[i].cl.absolute == 'undefined' ? this.list[i].minH : '0px',
                    maxHeight : this.list[i].maxH
                }
            }, this.list[i].c)]));
        };

        if (this.list.length > 0)
        {
            f_add((this.list.length - 1));

            for (let i in this.list) {
                f_add(i);
            }

            f_add(0);
        }

        var points_list = [];
        for (let i=0; i<this.list.length; i++) {
            points_list.push(h('div', {
                key : i,
                ref : 'point_div_'+i
            }, [h('a', {
                attrs: {
                    href : 'javascript://',
                    'data-i' : i,
                },
                domProps: {
                    innerHTML: '<span>&nbsp;</span>'
                },
                ref : 'point_'+i,
                on : {
                    click : this.pointClick
                }
            }, '')]));
        }

        return h('div', {
            on : {
                mouseleave : this.mouseLeave,
                mouseenter : this.mouseEnter
            },
            class : {
                inline_slider_all : true
            },
        }, [
            h('div', {
                class : {
                    inline_slider_wrapper : true
                },
                style : this.inline_slider_wrapper_styles,
                ref: 'inline_slider_wrapper',
            }, [

                h('div', {
                    class : {
                        items_container_wrapper : true,
                        ...this.items_container_wrapper_classes
                    },
                    style : this.items_container_wrapper_styles,
                    ref: 'items_container_wrapper',
                    on : {
                        mousedown : this.mouseDown,
                        touchstart : this.touchStart
                    },
                    directives: [
                        /*
                        {
                            name: 'touch',
                            arg : 'start',
                            modifiers: {
                                //prevent : true,
                            },
                            value: this.touchStart
                        },
                        {
                            name: 'touch',
                            arg : 'swipe',
                            modifiers: {
                                left : true
                            },
                            value: this.swipeLeft
                        },
                        {
                            name: 'touch',
                            arg : 'swipe',
                            modifiers: {
                                right : true
                            },
                            value: this.swipeRight
                        },
                        */
                    ],
                }, [
                    h('div', {
                        class : {
                            items_container : true
                        },
                        style : this.items_container_styles,
                        ref: 'items_container'
                    }, items),
                ]),

                h('div', {
                    class : {
                        arrows : true
                    },
                    style : this.arrows_styles
                }, [
                    h('a', {
                        domProps: {
                            innerHTML: '<span>&nbsp;</span>'
                        },
                        class : {
                            left : true
                        },
                        on : {
                            click : this.stepLeft
                        }
                    }, ''),
                    h('a', {
                        domProps: {
                            innerHTML: '<span>&nbsp;</span>'
                        },
                        class : {
                            right : true
                        },
                        on : {
                            click : this.stepRight
                        }
                    }, '')
                ]),

                h('div', {
                    class : {
                        points_wrapper : true
                    },
                    style : this.points_styles
                },
                [h('div', {
                        class : {
                            points : true
                        },
                        ref : 'points',
                        style : this.points_styles
                    }, [
                        h('div', {
                            class : {
                                points_c : true
                            },
                            ref : 'points_c',
                            style : this.points_styles
                        }, [
                            points_list
                        ])
                    ])
                ])
            ])
        ]);
    },
    created() {
        this.is_touch = eApi.getFuncs().isTouchDevice();
        this.buildItemsList();
        this.mouse_clicked_x = 0;
        this.mouse_clicked_y = 0;
        this.mouse_clicked_x_start = 0;
        this.timeout_show = 0;
    },
    beforeUpdate() {
        this.buildItemsList();
    },
    mounted() {
        this.$refs['items_container'].addEventListener('transitionend', this.itemsContainerTransitionEnd);
        this.$refs['points_c'].addEventListener('transitionend', this.itemsPointsTransitionEnd);
        this.rebuildSizesSettings();
        window.addEventListener('resize', this.rebuildSizesSettings);
        window.addEventListener('mouseup', this.mouseEnd);
        window.addEventListener('mousemove', this.mouseMove, {passive: false});
        window.addEventListener('touchmove', this.touchMove, {passive: false});
        window.addEventListener('touchend', this.touchEnd);
        this.$refs['items_container'].querySelectorAll('a').forEach((link) =>
        {
            link.addEventListener('click', (e) => {
                if (!((this.is_touch == false && e.offsetX == 0 && e.offsetY == 0) || (this.is_touch  && this.clicked_link_can)))
                {
                    this.clicked_link_can = false;
                    e.preventDefault();
                }
            });

            link.addEventListener('mousedown', (e) => {
                if (e.button == 0 && this.is_touch == false)
                {
                    this.position_for_move_for_links = e.clientX;
                }
            });

            link.addEventListener('mouseup', (e) => {
                if (e.button == 0 && this.is_touch == false)
                {
                    if (Math.abs(this.position_for_move_for_links - e.clientX) <= 10)
                    {
                        link.click();
                    }
                }
            });

            link.addEventListener('touchstart', (e) => {
                this.position_for_move_for_links = 0;
            });

            link.addEventListener('touchmove', (e) => {
                this.position_for_move_for_links = -1;
            });

            link.addEventListener('touchend', (e) => {
                if (this.position_for_move_for_links == 0)
                {
                    this.clicked_link_can = true;
                    link.click();
                }
            });
            
        });

        this.startTimeoutShow();
    },
    beforeDestroy() {
        this.$refs['items_container'].removeEventListener('transitionend', this.itemsContainerTransitionEnd);
        this.$refs['points_c'].removeEventListener('transitionend', this.itemsPointsTransitionEnd);
        window.removeEventListener('resize', this.rebuildSizesSettings);
        window.removeEventListener('mouseup', this.mouseEnd);
        window.removeEventListener('mousemove', this.mouseMove);
        window.removeEventListener('touchmove', this.touchMove);
        window.removeEventListener('touchend', this.touchEnd);
    },
}
</script>

<style>
</style>
