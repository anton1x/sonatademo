<template>
<div class="block_taps" ref="block_taps">
    <div class="bar" ref="bar">
        <template v-for="(i,k,index) in taps">
            <div :key="'bar_' + k" :style="{'margin-left' : -index + 'px'}" :class="{sel : k == tapOpened}" @click="openTap(k)">{{i}}</div>
        </template>
    </div>
    <div class="content" ref="content">
        <template v-for="(i,k) in taps">
            <div :key="'tap_' + k" :ref="'tap_'+ k"><slot :name="'tap_' + k"></slot></div>
        </template>
    </div>
</div>
</template>

<script>

export default {
    props : {
        value : {
            type: String,
            default: null
        },
        taps : {
            type: Object,
            default: function() {
                return {};
            },
        }
    },
    data () {
        return {
            animGo : false,
            animGoTarget : null,
            openBlocked : false,
            tapOpened : null,
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
        openTap(i)
        {
            if (this.animGo)
            {
                return;
            }

            if (i != this.tapOpened)
            {
                let old_tap = this.$refs['tap_' + this.tapOpened][0];
                let new_tap = this.$refs['tap_' + i][0];
                this.animGo = true;
                this.animGoTarget = i;
                this.tapOpened = i;

                let fh = () => {
                    old_tap.removeEventListener('transitionend', fh);
                    old_tap.classList.remove('show');

                    new_tap.style.setProperty('opacity', '0');
                    new_tap.classList.add('show');
                    new_tap.classList.add('animating-show');

                    this.nextAnimFrame(() => {
                        new_tap.style.setProperty('opacity', '1');
                    });
                };
                old_tap.addEventListener('transitionend', fh);

                let fs = () => {
                    new_tap.removeEventListener('transitionend', fs);
                    this.animGoTarget = null;
                    this.animGo = false;
                };
                new_tap.addEventListener('transitionend', fs);

                old_tap.classList.add('animating-hide');
                this.nextAnimFrame(() => {
                    old_tap.style.setProperty('opacity', '0');
                });

            }
        },
        updatePosition()
        {
            if (this.animGo == false || (this.animGo && this.animGoTarget != this.tapOpened))
            {
                for (let i in this.taps)
                {
                    if (i == this.tapOpened)
                    {
                        this.$refs['tap_' + i][0].classList.add('show');
                    }
                    else
                    {
                        this.$refs['tap_' + i][0].classList.remove('show');
                    }
                }
            }
        }
    },
    watch : {
        value : function(v)
        {
            if (v != this.tapOpened)
            {
                this.tapOpened = v;
                this.updatePosition();
            }
        },
        tapOpened : function(v)
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
        this.tapOpened = this.value;
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
