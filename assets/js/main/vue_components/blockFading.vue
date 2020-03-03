<template>
<div class="block_fading opened" ref="block_fading">
    <div class="bar" ref="bar">
        <div class="icon"><slot name="icon"></slot></div>
        <div class="title">{{title}}</div>
    </div>
    <div class="content" ref="content">
        <slot name="content" v-bind="datacontent"></slot>
    </div>
</div>
</template>

<script>

export default {
    props : {
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
        
    },
    watch : {
        
    },
    created() {
        
    },
    beforeUpdate() {
        
    },
    mounted() {
        
        
    },
    beforeDestroy() {
        
    },
}
</script>

<style>
</style>
