<template>
<div class="block_fading" ref="block_fading">
    <slot name="content" v-bind="datacontent"></slot>
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
