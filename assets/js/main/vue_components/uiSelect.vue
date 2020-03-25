<template>
<div class="ui_select" ref="ui_select">
    <div class="select_elem">
        <select ref="select_item" v-model="selectItemVal">
            <template v-for="option in options">
                <slot name="select_option" v-bind="option">
                    <option :value="option.value" :title="option.text">{{option.text}}</option>
                </slot>
            </template>
        </select>
    </div>

    <div class="bar" ref="bar" @click="barClick">
        <div class="result">
            <slot name="bar" v-bind="selectedOption">
                <div class="value" :title="selectedOption.text">{{selectedOption.text}}</div>
            </slot>
        </div>
    </div>

    <div class="options" ref="options">
        <div ref="options_c">
            <div class="scroll_box">
                <div class="scroll_box_w" ref="scroll_box_w">
                    <div class="scroll_box_c" :style="scrollBoxCStyle">
                        <div class="options_holder" ref="options_holder">
                            <template v-for="option in options">
                                <div :data-id="option.value" @click="optionClick(option.value)" :class="{sel : option.value == selectedOption.value}">
                                    <slot name="option" v-bind="option">
                                        <div class="option">{{option.text}}</div>
                                    </slot>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="scroller_w">
                    <div class="scroller_c" ref="scroller_c">
                        <div class="scroller" ref="scroller"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
    props : {
        options : {
            type: Array,
            default: function () {
                return [];
            }
        },
        value : {
            type: [Number, String],
            default: undefined
        },
        custom : {
            type: String,
            default: 'default'
        }
    },
    data () {
        return {
            selectItemVal : undefined,
            selectedVal : undefined,
            animGo : false,
            openBlocked : false,
            isOpened : false,
            mode : 1,
            blockSelectChange : false,
            scrollBoxCStyle : {
                width : 'auto'
            }
        }
    },
    computed : {
        selectedOption()
        {
            if (this.options.length == 0)
            {
                return {
                    value : '',
                    text : '',
                    index : 0
                };
            }

            let key = this.selectedVal === undefined ? 0 : (this.options.findIndex(i => i.value.toString() == this.selectedVal.toString()));
            let result;
            if (key == -1)
            {
                result = this.options[0];
                result.index = 0;
            }
            else
            {
                result = this.options[key];
                result.index = key;
            }
            return result;
        }
    },
    methods : {
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
        },
        optionClick(key)
        {
            this.selectedVal = key;
            this.closeOptions();
            //console.log(elem);
        },
        barClick(e) {
            e.preventDefault();

            if (this.animGo == true || this.openBlocked == true)
            {
                return;
            }


            if (this.isOpened == false)
            {
                this.openOptions();
            }
            else
            {
                if (this.mode == 1)
                {
                    this.closeOptions();
                }
            }
        },
        docClick(e) {
            if (this.mouseMoving == true)
            {
                return;
            }
            let sel = e.target.closest('.ui_select');
            if (sel === null || sel != this.$refs['ui_select'])
            {
                this.closeOptions();
            }
        },
        onSelectBlur(e)
        {
            this.openBlocked = true;
            this.$refs['select_item'].removeEventListener('select', this.onSelectBlur);
            this.closeOptions();
            setTimeout(() => {
                this.openBlocked = false;
            }, 100);
        },
        openOptions()
        {
            if (this.options.length == 0)
            {
                return;
            }

            if (this.isOpened == false)
            {
                this.isOpened = true;
                this.$refs['ui_select'].classList.add('opened');
                this.$refs['ui_select'].style.setProperty('z-index', (window.ui_select_z_index_counts++).toString());

                if (this.mode == 2)
                {
                    this.blockSelectChange = true;
                    this.$refs['select_item'].focus();
                    this.$refs['select_item'].addEventListener('blur', this.onSelectBlur);
                    setTimeout(() => {
                        this.blockSelectChange = false;
                    }, 100);

                    /*
                    if (document.createEvent) {
                        var e = document.createEvent("MouseEvents");
                        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                        this.$refs['select_item'].dispatchEvent(e);
                    } else if (element.fireEvent) {
                        this.$refs['select_item'].fireEvent("onmousedown");
                    }
                    */
                }
                else
                {
                    this.animGo = true;
                    let endf = (e) => {
                        this.$refs['options'].removeEventListener('transitionend', endf);
                        this.$refs['options'].classList.remove('animating-enter');
                        this.$refs['options_c'].classList.remove('fx');
                        this.$refs['options'].style.removeProperty('max-height');
                        this.$refs['options'].style.removeProperty('height');
                        this.animGo = false;
                    };
                    this.$refs['options'].addEventListener('transitionend', endf);

                    this.$refs['options'].classList.add('show');
                    let h = this.$refs['options'].offsetHeight;

                    this.$refs['scroll_box_w'].scrollTop = this.$refs['options_c'].querySelector('.options_holder > div').offsetHeight * this.selectedOption.index;
                    this.recountSizes();
                    this.recountScroller();

                    this.$refs['options'].style.setProperty('height', h+'px');
                    this.$refs['options'].style.setProperty('max-height', '0px');
                    this.$refs['options_c'].classList.add('fx');

                    this.nextAnimFrame(() => {
                        this.$refs['options'].classList.add('animating-enter');
                        this.$refs['options'].style.setProperty('max-height', h+'px');
                    });
                }
            }
        },
        closeOptions()
        {
            if (this.isOpened == true)
            {
                this.isOpened = false;
                if (this.mode == 2)
                {
                    this.$refs['select_item'].blur();
                    this.$refs['ui_select'].classList.remove('opened');
                    this.$refs['ui_select'].style.removeProperty('z-index');
                }
                else
                {
                    this.animGo = true;
                    let endf = (e) => {
                        this.$refs['options'].removeEventListener('transitionend', endf);
                        this.$refs['options'].classList.remove('animating-hide');
                        this.$refs['options_c'].classList.remove('fx');
                        this.$refs['options'].style.removeProperty('max-height');
                        this.$refs['options'].style.removeProperty('height');
                        this.$refs['options'].classList.remove('show');
                        this.$refs['ui_select'].classList.remove('opened');
                        this.$refs['ui_select'].style.removeProperty('z-index');
                        this.animGo = false;
                    };
                    this.$refs['options'].addEventListener('transitionend', endf);

                    let h = this.$refs['options'].offsetHeight;
                    this.$refs['options'].style.setProperty('height', h+'px');
                    this.$refs['options'].style.setProperty('max-height', h+'px');
                    this.$refs['options_c'].classList.add('fx');

                    this.nextAnimFrame(() => {
                        this.$refs['options'].classList.add('animating-hide');
                        this.$refs['options'].style.setProperty('max-height', '0');
                    });
                }
            }
        },
        recountSizes() {
            this.scrollBoxCStyle.width = 'auto';

            let h = this.$refs['options_c'].offsetHeight;
            let w = this.$refs['options_c'].offsetWidth;

            this.scrollBoxCStyle.width = w+'px';
        },
        recountScroller() {
            let h = this.$refs['options_c'].offsetHeight;

            if (h > 0)
			{
                let p = Math.round(this.$refs['scroll_box_w'].offsetHeight / this.$refs['scroll_box_w'].scrollHeight * 100);
				if (p < 20)
				{
					p = 20;
				}
				else if (p > 100)
				{
					p = 100;
				}
                this.scrollerPer = p;
                this.$refs['scroller'].style.height = p+'%';
				this.countScrollerPosition();
			}
        },
        countScrollerPosition()
        {
            let max_h = this.$refs['scroller_c'].offsetHeight - this.$refs['scroller'].offsetHeight;
            var tp = Math.round(this.$refs['scroll_box_w'].scrollTop / (this.$refs['scroll_box_w'].scrollHeight - this.$refs['scroll_box_w'].offsetHeight) * max_h);
            this.scrollerTP = tp;
            this.$refs['scroller'].style.top = tp+'px';
        },
        countScrollerPositionByTP()
        {
            let pr = this.scrollerTP / (this.$refs['scroller_c'].offsetHeight - this.$refs['scroller'].offsetHeight);
            this.$refs['scroll_box_w'].scrollTop = Math.round((this.$refs['scroll_box_w'].scrollHeight - this.$refs['scroll_box_w'].offsetHeight) * pr);
            this.$refs['scroller'].style.top = this.scrollerTP+'px';
        },
        scrollerTouchStart(e)
        {
            this.mouseMoving = true;
            this.mouseMovingX = e.pageX;
            this.mouseMovingY = e.pageY;
            this.$refs['ui_select'].classList.add('noSel');
        },
        scrollerTouchMove(e)
        {
            if (this.mouseMoving)
			{
				let dX = e.pageX - this.mouseMovingX;
				let dY = e.pageY - this.mouseMovingY;
				this.mouseMovingX = e.pageX;
				this.mouseMovingY = e.pageY;

                let tp = this.scrollerTP + dY;
                var max_h = this.$refs['scroller_c'].offsetHeight  - this.$refs['scroller'].offsetHeight;

                if (tp < 0)
				{
					tp = 0;
				}
				else if (tp > max_h)
				{
					tp = max_h;
                }

                this.scrollerTP = tp;
				this.countScrollerPositionByTP();

			}
        },
        scrollerTouchEnd(e)
        {
            this.$refs['ui_select'].classList.remove('noSel');
            setTimeout(() => {
                this.mouseMoving = false;
            }, 0)
        }
    },
    watch : {
        selectItemVal : function(v)
        {
            if (this.blockSelectChange == true)
            {
                this.blockSelectChange = false;
                this.selectItemVal = this.selectedVal;
            }
            else
            {
                this.selectedVal = v;
            }
        },
        value : function(v)
        {
            this.selectedVal = v;
            this.selectItemVal = this.selectedVal;
        },
        selectedVal : function(v)
        {
            this.selectItemVal = v;
            this.$emit('input', v);
        }
    },
    created() {
        this.selectedVal = this.value;
        this.selectItemVal = this.selectedOption.value;
        this.mode = this.custom == 'always' ? 1 : (eApi.getFuncs().isTouchDevice() == true && navigator.userAgent.match(/iPhone|iPad|iPod/i) ? 2 : 1);
        this.mouseMoving = false;
        this.mouseMovingX = 0;
        this.mouseMovingY = 0;

        if (this.mode == 1)
        {
            if (typeof window.ui_select_z_index_counts == 'undefined')
            {
                window.ui_select_z_index_counts = 9999;
            }
            document.addEventListener('click', this.docClick);
        }
    },
    beforeUpdate() {
        
    },
    mounted() {
        if (this.mode == 1)
        {
            this.$refs['scroll_box_w'].addEventListener('scroll', this.countScrollerPosition);
            this.$refs['scroller'].addEventListener('mousedown', this.scrollerTouchStart);
            this.$refs['scroller'].addEventListener('touchstart', this.scrollerTouchStart);
            document.addEventListener('mouseup', this.scrollerTouchEnd);
            document.addEventListener('blur', this.scrollerTouchEnd);
            document.addEventListener('touchend', this.scrollerTouchEnd);
            document.addEventListener('mousemove', this.scrollerTouchMove);
            document.addEventListener('touchmove', this.scrollerTouchMove);
        }
    },
    beforeDestroy() {
        if (this.mode == 1)
        {
            document.removeEventListener('click', this.docClick);
            this.$refs['scroll_box_w'].removeEventListener('scroll', this.countScrollerPosition);
            this.$refs['scroller'].removeEventListener('mousedown', this.scrollerTouchStart);
            this.$refs['scroller'].removeEventListener('touchstart', this.scrollerTouchStart);
            document.removeEventListener('mouseup', this.scrollerTouchEnd);
            document.removeEventListener('blur', this.scrollerTouchEnd);
            document.removeEventListener('touchend', this.scrollerTouchEnd);
            document.removeEventListener('mousemove', this.scrollerTouchMove);
            document.removeEventListener('touchmove', this.scrollerTouchMove);
        }
        else if (this.mode == 2)
        {
            //this.$refs['select_item'].removeEventListener('blur', this.onSelectBlur);
        }
    },
}
</script>

<style>
</style>
