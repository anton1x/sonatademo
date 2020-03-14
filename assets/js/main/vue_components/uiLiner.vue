<template>
<div class="ui_liner" ref="ui_liner">
    <div class="select_elem">
        <input type="hidden" :value="selectedOption.value">
    </div>

    <div class="bar" ref="bar">
        <div class="line" ref="line">
            <div class="gray_line"></div>
            <div class="sel_line" ref="sel_line"></div>
        </div>
        <div class="bars">
            <template v-for="(i,c) in options">
                <div :ref="'bar_item_' + c" class="bar_item">
                    <span @click="selOption(i.value)">&nbsp;</span>
                    <div></div>
                </div>
            </template>
        </div>
        <div class="point" ref="point"><span ref="point_click">&nbsp;</span><div></div></div>
        <div class="titles">
            <template v-for="(i,c) in options">
                <div :style="countTitleDivStyle(i, c)" :class="{sel : i.value == selectedVal}">
                    <div>
                        <div class="title">
                            <span class="help"><span class="q" @click="showHelpBlock(i.value)">&nbsp;</span></span>
                            <span class="text" @click="selOption(i.value)"><slot name="title" v-bind="i">{{i.text}}</slot></span>
                        </div>
                        <div v-if="showingHelpBlock == i.value && helpBlocksMode == 1" class="title_help_wrapper">
                            <div class="arrow"></div>
                            <div class="content">
                                <a href="javascript://" class="close" @click="showingHelpBlock = undefined">&nbsp;</a>
                                <div v-html="formShowHelpBlock(i.value)"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
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
            default: null
        },
        helpblock : {
            type: Function,
            default: ()=>{ return ''; }
        }
    },
    data () {
        return {
            selectedVal : null,
            showingHelpBlock : null,
            helpBlocksMode : 1
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

            let v = this.selectedVal === null ? '' : this.selectedVal.toString();
            let key = this.selectedVal === null ? 0 : (this.options.findIndex(i => i.value.toString() == v));
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
        countTitleDivStyle(i, c)
        {
            if (this.options.length < 2)
            {
                return {'flex-basis' : '100%'};
            }

            let w;

            if ((c == 0 || c == (this.options.length - 1)) == false)
            {
                w = 'calc(24px + ((100% - '+(this.options.length * 24)+'px) / '+(this.options.length - 1)+'))';
            }
            else
            {
                w = 'calc(24px + ((100% - '+(this.options.length * 24)+'px) / '+((this.options.length - 1) * 2)+'))';
            }

            let s = {
                'flex-basis' : w
            };

            return s;
        },
        selOption(key)
        {
            this.selectedVal = key;
            this.rebuildLineAndPointer();
        },
        getOptionIndexByValue(value)
        {
            let v = value === null ? '' : value.toString();
            let r = this.options.findIndex(i => i.value.toString() == v);
            return r == -1 ? 0 : r;
        },
        getBarItem(value)
        {
            return this.$refs['bar_item_'+this.getOptionIndexByValue(value)][0];
        },
        getBarItemLeftOffset(value)
        {
            return Math.round(this.getBarItem(value).getBoundingClientRect().left - this.$refs['line'].getBoundingClientRect().left);
        },
        getPointLeftOffset()
        {
            return Math.round(this.$refs['point'].getBoundingClientRect().left - this.$refs['line'].getBoundingClientRect().left);
        },
        setLineAndPointer(offset, is_animate = false, on_anim_end = ()=>{})
        {
            if (offset < 0)
            {
                offset = 0;
            }
            else if (offset > this.maxOffset)
            {
                offset = this.maxOffset;
            }

            if (is_animate)
            {
                let f = () => {
                    this.$refs['point'].removeEventListener('transitionend', f);
                    this.$refs['sel_line'].classList.remove('anim');
                    this.$refs['point'].classList.remove('anim');
                    on_anim_end();
                };
                this.$refs['sel_line'].classList.add('anim');
                this.$refs['point'].classList.add('anim');
                this.$refs['point'].addEventListener('transitionend', f);
                this.nextAnimFrame(() => {
                    this.$refs['sel_line'].style.width = (offset + 12)+'px';
                    this.$refs['point'].style.left = offset+'px';
                });
            }
            else
            {
                this.$refs['sel_line'].classList.remove('anim');
                this.$refs['point'].classList.remove('anim');
                this.$refs['sel_line'].style.width = (offset + 12)+'px';
                this.$refs['point'].style.left = offset+'px';
            }
        },
        rebuildLineAndPointer()
        {
            let offset = this.getBarItemLeftOffset(this.selectedVal);
            this.setLineAndPointer(offset);
        },
        scrollerTouchStart(e)
        {
            this.mouseMoving = true;
            this.pointOffset = this.getPointLeftOffset();
            this.setLineAndPointer(this.pointOffset);
            if (typeof e.touches != 'undefined')
            {
                this.mouseMovingX_start = e.touches[0].clientX;
                this.mouseMovingX = e.touches[0].clientX;
                this.mouseMovingY = e.touches[0].clientY;
            }
            else
            {
                this.mouseMovingX = e.pageX;
                this.mouseMovingY = e.pageY;
            }
            window.document.body.classList.add('noSel');
        },
        scrollerTouchMove(e)
        {
            if (this.mouseMoving)
			{
                if (typeof e.touches != 'undefined')
                {
                    let offsetX = this.mouseMovingX - e.touches[0].clientX;
                    let offsetY = this.mouseMovingY - e.touches[0].clientY;
                    this.mouseMovingX = e.touches[0].clientX;
                    this.mouseMovingY = e.touches[0].clientY;
                    if (Math.abs(offsetX) > Math.abs(offsetY))
                    {
                        e.preventDefault();
                        this.setLineAndPointer(this.pointOffset + (e.pageX - this.mouseMovingX_start));
                    }
                }
                else
                {
                    let dX = e.pageX - this.mouseMovingX;
                    let dY = e.pageY - this.mouseMovingY;
                    this.setLineAndPointer(this.pointOffset + dX);
                }
			}
        },
        scrollerTouchEnd(e)
        {
            if (this.mouseMoving)
			{
                window.document.body.classList.remove('noSel');
                this.mouseMoving = false;
                let offset = this.getPointLeftOffset();
                let min_range = undefined;
                let min_range_val = Infinity;

                this.options.forEach(e => {
                    let v = this.getBarItemLeftOffset(e.value);
                    if (Math.abs(v - offset) < min_range_val)
                    {
                        min_range_val = Math.abs(v - offset);
                        min_range = e.value;
                    }
                });

                this.setLineAndPointer(this.getBarItemLeftOffset(min_range), true);
                this.selectedVal = min_range;
            }
        },
        rebuildSizing()
        {
            this.maxOffset = this.options.length > 0 ? this.getBarItemLeftOffset(this.options[(this.options.length - 1)].value) : 0;
            this.rebuildLineAndPointer();
            this.helpBlocksMode = window.innerWidth > 520 ? 1 : 2;
        },
        showHelpBlock(value)
        {
            if (value == this.showingHelpBlock)
            {
                this.showingHelpBlock = undefined;
            }
            else
            {
                this.showingHelpBlock = value;

                if (this.helpBlocksMode == 2)
                {
                    global.eApi.getBoxes().create({
                        animate : true,
                        minWidth : 300,
                        close_on_bg_click : true,
                        content : this.formShowHelpBlock(value),
                        box_destroy : () => {
                            this.showingHelpBlock = undefined;
                        }
                    });
                }
            }
        },
        formShowHelpBlock(value)
        {
            return this.helpblock(value);
        }
    },
    watch : {
        value : function(v)
        {
            if (v !== this.selectedVal)
            {
                this.selectedVal = v;
                this.rebuildLineAndPointer();
                //this.showingHelpBlock = undefined;
            }
        },
        selectedVal : function(v)
        {
            this.showingHelpBlock = undefined;
            this.selectedVal = this.selectedOption.value;
            this.$emit('input', this.selectedVal);
        }
    },
    created() {
        this.selectedVal = this.value;
        this.selectedVal = this.selectedOption.value;
        this.mouseMoving = false;
        this.mouseMovingX = 0;
        this.mouseMovingY = 0;
    },
    beforeUpdate() {
        
    },
    updated() {
        
    },
    mounted() {

        this.rebuildSizing();
        window.addEventListener('resize', this.rebuildSizing);

        this.$refs['point_click'].addEventListener('mousedown', this.scrollerTouchStart);
        this.$refs['point_click'].addEventListener('touchstart', this.scrollerTouchStart);
        document.addEventListener('mouseup', this.scrollerTouchEnd);
        document.addEventListener('blur', this.scrollerTouchEnd);
        document.addEventListener('touchend', this.scrollerTouchEnd);
        document.addEventListener('mousemove', this.scrollerTouchMove);
        document.addEventListener('touchmove', this.scrollerTouchMove);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.rebuildSizing);

        this.$refs['point_click'].removeEventListener('mousedown', this.scrollerTouchStart);
        this.$refs['point_click'].removeEventListener('touchstart', this.scrollerTouchStart);
        document.removeEventListener('mouseup', this.scrollerTouchEnd);
        document.removeEventListener('blur', this.scrollerTouchEnd);
        document.removeEventListener('touchend', this.scrollerTouchEnd);
        document.removeEventListener('mousemove', this.scrollerTouchMove);
        document.removeEventListener('touchmove', this.scrollerTouchMove);
    },
}
</script>

<style>
</style>
