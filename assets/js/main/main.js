import './../../css/main/main.less';

import './polyfills.js';

import 'formdata-polyfill';

import smoothscroll from 'smoothscroll-polyfill';
smoothscroll.polyfill();

import Vue from 'vue';
import Vuex from 'vuex';
import ApiModel from './api/';
import ProjectApiModel from './project/';

Vue.use(Vuex);

import Vue2TouchEvents from 'vue2-touch-events/index.js';

Vue.use(Vue2TouchEvents, {
    disableClick: true
});

import InputmaskI from "inputmask";
global.Inputmask = InputmaskI;

import dynamicList from './libs/dynamicList.js';
global.dynamicList = dynamicList;

global.eApi = new ApiModel();
global.projectApi = new ProjectApiModel(eApi);
eApi.setProjectApi(projectApi);

import itemsSliderComponent from './vue_components/itemsSlider.vue';
Vue.component('items-slider-component', itemsSliderComponent);

var _page_vue_components = ['items-slider-component'];

var _instances_dynamic_components = {};

global.rebuild = function() {
    for (let i in _instances_dynamic_components)
    {
        _instances_dynamic_components[i].forEach((instance, id) => {
            instance.$destroy(true);
        });
        _instances_dynamic_components[i] = [];
    }

    for (let i of _page_vue_components)
    {
        for (let e of document.getElementById('all').querySelectorAll(i+'.vueInit'))
        {
            if (_instances_dynamic_components[i] === undefined) {
                _instances_dynamic_components[i] = [];
            }
            _instances_dynamic_components[i].push(new Vue({}).$mount(e));
        }
    }
};


rebuild();
