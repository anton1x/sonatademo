<template>
<div class="ui_switcher" :class="{switcher : mode == 'switcher', checkbox : mode == 'checkbox'}" ref="ui_switcher">
    <div class="select_elem">
        <input type="hidden" :value="selectedVal">
    </div>

    <template v-if="mode == 'switcher'">
        <a href="javascript://" class="bar" ref="bar" @click="changePosition">
            <span class="line"></span>
            <span class="point">&nbsp;</span>
        </a>
    </template>

    <template v-if="mode == 'checkbox'">
        <a href="javascript://" class="bar_checkbox" ref="bar" @click="changePosition">
            <span class="point">&nbsp;</span>
        </a>
    </template>
</div>
</template>

<script>

export default {
    props : {
        truevalue : {
            type: [Boolean, Number, String],
            default: true,
        },
        falsevalue : {
            type: [Boolean, Number, String],
            default: false,
        },
        value : {
            type: [Boolean, Number, String],
            default: undefined
        },
        usercanoff : {
            type: [Boolean, String],
            default: true
        },
        mode : {
            type: [String],
            default: 'switcher',
        },
    },
    data () {
        return {
            selectedVal : undefined
        }
    },
    computed : {
        position()
        {
            if (this.selectedVal == this.truevalue)
            {
                return 'on';
            }
            return 'off';
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
        updatePosition(is_animate = false)
        {
            if (is_animate)
            {
                let f = () => {
                    this.$refs['bar'].removeEventListener('transitionend', f);
                    this.$refs['bar'].classList.remove('anim');
                };
                this.$refs['bar'].addEventListener('transitionend', f);
                this.$refs['bar'].classList.add('anim');
                this.nextAnimFrame(() => {
                    if (this.position == 'on')
                    {
                        this.$refs['bar'].classList.add('on');
                    }
                    else
                    {
                        this.$refs['bar'].classList.remove('on');
                    }
                });
            }
            else
            {
                if (this.position == 'on')
                {
                    this.$refs['bar'].classList.add('on');
                }
                else
                {
                    this.$refs['bar'].classList.remove('on');
                }
            }
        },
        changePosition()
        {
            if (this.position == 'on' && (this.usercanoff == false || this.usercanoff == 'false'))
            {
                return;
            }
            this.selectedVal = this.position == 'off' ? this.truevalue : this.falsevalue;
            this.updatePosition(true);
        }
    },
    watch : {
        value : function(v)
        {
            if (v !== this.selectedVal)
            {
                this.selectedVal = v;
                this.updatePosition(true);
            }
        },
        selectedVal : function(v)
        {
            this.$emit('input', v);
        }
    },
    created() {
        this.selectedVal = this.value;
    },
    beforeUpdate() {
        
    },
    mounted() {
        
        this.updatePosition();
    },
    beforeDestroy() {
        
    },
}
</script>

<style>
</style>
