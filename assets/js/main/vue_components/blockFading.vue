<template>
<div class="block_fading" ref="block_fading">
    <div class="bar" ref="bar" @click="barClick">
        <div class="icon" v-if="$slots.icon !== undefined"><slot name="icon"></slot></div>
        <div class="title">{{title}}</div>
        <div class="arrow"></div>
    </div>
    <div class="content" ref="content">
        <div ref="content_c"><slot name="content" v-bind="datacontent"></slot></div>
    </div>
</div>
</template>

<script>

export default {
    props : {
        value : {
            type: Boolean,
            default: false
        },
        datacontent : {
            type: Object,
            default: function() {
                return {};
            },
        },
        title : {
            type: String,
            default: 'Смотреть'
        },
    },
    data () {
        return {
            animGo : false,
            openBlocked : false,
            isOpened : false,
        }
    },
    computed : {
        
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
        barClick(e) {
            e.preventDefault();

            if (this.isOpened == false)
            {
                this.open();
            }
            else
            {
                this.close();
            }
        },
        clearOF()
        {
            this.$refs['content'].classList.remove('animating-enter');
            this.$refs['content'].classList.remove('hid');
            this.$refs['content'].style.removeProperty('max-height');
        },
        clearCF()
        {
            this.$refs['content'].classList.remove('animating-hide');
            this.$refs['content'].style.removeProperty('max-height');
            this.$refs['content'].classList.remove('hid');
            this.$refs['content'].classList.remove('show');
        },
        endF(e)
        {
            if (e.target == this.$refs['content'])
            {
                if (this.animDir == 'enter')
                {
                    this.clearOF();
                    this.animGo = false;
                }
                else if (this.animDir == 'hide')
                {
                    this.clearCF();
                    this.animGo = false;
                }
            }
        },
        open()
        {
            if (this.isOpened == false)
            {
                this.isOpened = true;
                this.$refs['block_fading'].classList.add('opened');

                this.animGo = true;

                this.$refs['content'].classList.add('show');
                this.$refs['content'].classList.add('fxh');
                this.$refs['content'].classList.add('hid');
                let h = this.$refs['content_c'].offsetHeight;
                this.$refs['content'].style.setProperty('max-height', '0px');
                this.$refs['content'].classList.remove('fxh');

                this.nextAnimFrame(() => {
                    this.animDir = 'enter';
                    this.$refs['content'].classList.remove('animating-hide');
                    this.$refs['content'].classList.add('animating-enter');
                    this.$refs['content'].style.setProperty('max-height', h+'px');
                });
            }
        },
        close()
        {
            if (this.isOpened == true)
            {
                this.isOpened = false;
                this.$refs['block_fading'].classList.remove('opened');

                this.animGo = true;

                let h = this.$refs['content_c'].offsetHeight;
                this.$refs['content'].style.setProperty('max-height', h+'px');
                this.$refs['content'].classList.add('hid');

                this.nextAnimFrame(() => {
                    this.animDir = 'hide';
                    this.$refs['content'].classList.remove('animating-enter');
                    this.$refs['content'].classList.add('animating-hide');
                    this.$refs['content'].style.setProperty('max-height', '0');
                });
                
            }
        },
        updatePosition()
        {
            if (this.isOpened == false && this.$refs['content'].classList.contains('show'))
            {
                this.$refs['block_fading'].classList.remove('opened');
                this.$refs['content'].classList.remove('animating-hide');
                this.$refs['content'].classList.remove('animating-enter');
                this.$refs['content'].style.removeProperty('max-height');
                this.$refs['content'].classList.remove('hid');
                this.$refs['content'].classList.remove('show');
            }
            else if (this.isOpened && this.$refs['content'].classList.contains('show') == false)
            {
                this.$refs['block_fading'].classList.add('opened');
                this.$refs['content'].classList.remove('animating-hide');
                this.$refs['content'].classList.remove('animating-enter');
                this.$refs['content'].style.removeProperty('max-height');
                this.$refs['content'].classList.remove('hid');
                this.$refs['content'].classList.add('show');
            }
        }
    },
    watch : {
        value : function(v)
        {
            if (v != this.isOpened)
            {
                this.isOpened = v;
                this.updatePosition();
            }
        },
        isOpened : function(v)
        {
            this.$emit('input', v);
        }
    },
    created() {
        
    },
    beforeUpdate() {
        
    },
    mounted() {
        this.$refs['content'].addEventListener('transitionend', this.endF);
        this.isOpened = this.value;
        this.updatePosition();
    },
    updated() {
        this.isOpened = this.value;
        this.updatePosition();
    },
    beforeDestroy() {
        this.$refs['content'].removeEventListener('transitionend', this.endF);
    },
}
</script>

<style>
</style>
