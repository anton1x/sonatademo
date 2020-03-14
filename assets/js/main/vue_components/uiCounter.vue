<template>
<div class="ui_counter" ref="counter">
    <div>
        <a href="javascript://" class="minus" @click="minus">—</a>
        <div class="value">{{innerVal}}</div>
        <a href="javascript://" class="plus" @click="plus">+</a>
    </div>
</div>
</template>

<script>

export default {
    props : {
        value : {
            type: Number,
            default: 0
        }
    },
    data () {
        return {
            innerVal : 0
        }
    },
    computed : {
        
    },
    methods : {
        plus()
        {
            this.innerVal++;
        },
        minus()
        {
            if (this.innerVal > 0)
            {
                this.innerVal--;
            }
        }
    },
    watch : {
        value : function(v)
        {
            if (v !== this.innerVal)
            {
                this.innerVal = v;
            }
        },
        innerVal : function(v)
        {
            this.$emit('input', v);
        }
    },
    created() {
        this.innerVal = this.value;
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
