<template>
<div id="make_order_app_wrapper" ref="app_wrapper">
    <div class="top">
        <div class="step_1" :class="{sel : nowStep >= 1}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(1)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(1)">выбор адреса</a>
            </div>
        </div>
        <div class="step_2" :class="{sel : nowStep >= 2}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(2)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(2)">интернет</a>
            </div>
        </div>
        <div class="step_3" :class="{sel : nowStep >= 3}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(3)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(3)">телевидение</a>
            </div>
        </div>
        <div class="step_4" :class="{sel : nowStep >= 4}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(4)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(4)">дополнительные услуги</a>
            </div>
        </div>
        <div class="step_5" :class="{sel : nowStep >= 5}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(5)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(5)">оформление заявки</a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="step_content">
            <template v-if="nowStep == 1">
                <div class="step_wrapper" id="make_order_app_step_1_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Выберите ваш адрес</div>
                        <div class="input">
                            <ui-select :options="addressesOptionsList" v-model="dataAddressId" custom="always" :key="'address_selector'">
                                <template v-slot:option="option">
                                    <div class="option autoheight" :title="option.text"><div class="sub_text" style="font-weight:bold">{{option.title}}</div><div class="sub_text gray">{{option.address}}</div></div>
                                </template>
                                <template v-slot:bar="selectedOption">
                                    <div class="value" :title="selectedOption.text">{{selectedOption.title}} <span class="gray">({{selectedOption.address}})</span></div>
                                </template>   
                            </ui-select>
                        </div>
                        <div class="simple">
                            <block-fading :datacontent="{}">
                                <template v-slot:icon>ИКА</template>
                                <template v-slot:content="data">
                                    !!!!
                                </template>  
                            </block-fading>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="nowStep == 2">
                <div class="step_wrapper" id="make_order_app_step_2_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Настройте тариф так, как нужно вам</div>
                        <div class="input" :style="{maxWidth : (addressesInternetPlansList.length * 160) + 'px'}">
                            <ui-liner :options="addressesInternetPlansList" v-model="dataStep2.internetPlan" :helpblock="internetPlansFormHelpBlock" :key="'internet_liner'">
                                 <template v-slot:title="i">
                                    <span class="mb">{{i.speed}}</span> Мбит/с
                                </template>   
                            </ui-liner>
                        </div>
                    </div>
                    <div class="block_wrapper">
                        <div class="title">Дополнительно</div>
                        <div class="switcher_option">
                            <div class="input"><ui-switcher v-model="dataStep2.staticIp" :key="'internet_addon_static_ip'"></ui-switcher></div>
                            <div class="content">
                                <div class="title">Статический IP-адрес</div>
                                <div class="desc">(для стабильного удалённого доступа к вашим сетевым устройствам)</div>
                                <div class="price">{{coolNumber(addressesInternetAdds['3'].price.monthly_price)}} <span>р/мес</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="block_wrapper">
                        <div class="title">Выбор оборудования</div>
                        <div id="make_order_app_step_2_devices" :class="{gpon : currentAddressConType == 'gpon'}">
                            <div class="gpon_block" v-if="currentAddressConType == 'gpon'">
                                <div class="desc">
                                    <div class="title">Внимание!</div>
                                    <div class="text">В вашем жилом комплексе подключение к интернету осуществляется по технологии <span>GPON</span>. <span>Обязательным оборудованием для подключения является ont-модем Eltex</span>.</div>
                                </div>
                                <div class="devices">
                                    <template v-for="(d,i) in currentDataAddress.connection_type.devices">
                                        <template v-if="formData.products.devices_internet_ont[d] !== undefined">
                                            <div :key="i + '_ont_devices'">
                                                <div class="image" :style="{'background-image' : 'url('+getOntDevice(d).image.url.reference+')'}" @click="dataStep2.optDevice = d"><div></div></div>
                                                <div class="title" @click="dataStep2.optDevice = d">{{getOntDevice(d).title}}</div>
                                                <div class="desc" v-html="getOntDevice(d).description"></div>
                                                <div class="bot">
                                                    <div class="price">{{coolNumber(getOntDevice(d).price.connection_price)}} <span>Р</span></div>
                                                    <div class="switcher">
                                                        <div class="input">
                                                            <ui-switcher v-model="dataStep2.optDevice" :truevalue="d" :falsevalue="null" :usercanoff="false" mode="checkbox" :key="i + '_ont_devices_switcher'"></ui-switcher>
                                                        </div>
                                                        <div class="result">
                                                            <span v-if="dataStep2.optDevice == d" class="selected">выбрано</span>
                                                            <span v-else @click="dataStep2.optDevice = d" class="sel">выбрать</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                            <div class="wifi_block">
                                <div class="title" v-if="currentAddressConType == 'gpon'">Выбор Wi-Fi роутера</div>
                                <div class="desc">Для организации беспроводной Wi-Fi сети в квартире и комфортного использования нескольких устройств рекомендуем одну из представленных моделей Wi-Fi роутеров:</div>
                                <div class="devices">
                                    <template v-for="(d,i) in currentDataAddress.connection_type.devices">
                                        <template v-if="formData.products.devices_internet_wifi[d] !== undefined">
                                            <div :key="i + '_wifi_devices'">
                                                <div class="image" :style="{'background-image' : 'url('+getWiFiDevice(d).image.url.reference+')'}"><div :style="{'min-height' : Math.round((160 / getWiFiDevice(d).image.sizes.reference.width) * getWiFiDevice(d).image.sizes.reference.height) + 'px' }"></div></div>
                                                <div class="content">
                                                    <div class="title">{{getWiFiDevice(d).title}}</div>
                                                    <div class="desc" v-html="getWiFiDevice(d).description"></div>
                                                    <div class="price">{{coolNumber(getWiFiDevice(d).price.connection_price)}} <span>Р</span></div>
                                                    <div class="switcher">
                                                        <div class="input">
                                                            <ui-switcher v-model="dataStep2.wifiDevice" :truevalue="d" :falsevalue="null" mode="checkbox" :key="i + '_wifi_devices_switcher'"></ui-switcher>
                                                        </div>
                                                        <div class="result">
                                                            <template v-if="dataStep2.wifiDevice == d">
                                                                <span class="selected">выбрано</span>
                                                                <span class="cancel" @click="dataStep2.wifiDevice = null">отказаться от роутера</span>
                                                            </template>
                                                            <span v-else @click="dataStep2.wifiDevice = d" class="sel">выбрать</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="nowStep == 3">
                <div class="step_wrapper" id="make_order_app_step_3_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Настройте ваш телевизор</div>
                        <div class="input" :style="{maxWidth : (addressesTvPlansList.length * 160) + 'px'}">
                            <ui-liner :options="addressesTvPlansList" v-model="dataStep3.tvPlan" :helpblock="tvPlansFormHelpBlock" :key="'tv_liner'"></ui-liner>
                        </div>
                        <div class="simple">
                            <div id="make_order_app_step_3_tv_box">
                                <div class="title">Внимание!</div>
                                <div class="text">При отсутствии смарт-тв для просмотра интерактивного телевидения понадобится тв-приставка.</div>
                                <div class="item">
                                    <div class="image" :style="{'background-image' : 'url('+getTvBox().image.url.reference+')'}"><div :style="{'min-height' : Math.round((160 / getTvBox().image.sizes.reference.width) * getTvBox().image.sizes.reference.height) + 'px' }"></div></div>
                                    <div class="content">
                                        <div class="price">{{coolNumber(getTvBox().price.connection_price)}} <span>Р</span></div>
                                        <div class="switcher">
                                            <div class="input">
                                                <ui-switcher v-model="dataStep3.tvBox" :truevalue="getTvBox().id" :falsevalue="null" mode="checkbox" :key="getTvBox().id + '_tvbox_devices_switcher'"></ui-switcher>
                                            </div>
                                            <div class="result">
                                                <template v-if="dataStep3.tvBox == getTvBox().id">
                                                    <span class="selected">выбрано</span>
                                                    <span class="cancel" @click="dataStep3.tvBox = null">отказаться</span>
                                                </template>
                                                <span v-else @click="dataStep3.tvBox = getTvBox().id" class="sel">выбрать</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simple">
                            <block-fading :datacontent="{t:dataStep3.tvPlan}">
                                <template v-slot:icon>ИКА</template>
                                <template v-slot:content="data">
                                    !!!! {{data.t}} ! {{dataStep3.tvPlan}}
                                    <ui-switcher v-model="dataStep3.t"></ui-switcher>
                                </template>  
                            </block-fading>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="result">
            <div>
                <div class="result_holder">
                    <div class="title">Ваш выбор</div>
                    <div class="blocks">
                        <div id="make_order_app_result_1">
                            <div class="title">
                                <div class="icon"></div>
                                <div class="text">Адрес:</div>
                            </div>
                            <div class="content">
                                <div class="simple_text">{{currentDataAddress.title}} ({{currentDataAddress.address}})</div>
                            </div>
                        </div>
                        <div id="make_order_app_result_2" v-if="nowStep >= 2 && dataStep2.canceled == false">
                            <div class="title">
                                <div class="icon"></div>
                                <div class="text">Интернет:</div>
                            </div>
                            <div class="content">
                                <div class="items">
                                    <div class="item" v-if="dataStep2.internetPlan !== null">
                                        <span class="t">Тариф {{getInternetPlan(dataStep2.internetPlan).title}}</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getInternetPlan(dataStep2.internetPlan).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                    <div class="item" v-if="dataStep2.staticIp">
                                        <span class="t">{{addressesInternetAdds['3'].title}}</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(addressesInternetAdds['3'].price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                    <div class="item" v-if="dataStep2.optDevice !== null && currentAddressConType == 'gpon'">
                                        <span class="t">Оптический модем {{getOntDevice(dataStep2.optDevice).title}}</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getOntDevice(dataStep2.optDevice).price.connection_price)}} <span>р</span></span>
                                    </div>
                                    <div class="item" v-if="dataStep2.wifiDevice !== null">
                                        <span class="t">Wi-Fi роутер {{getWiFiDevice(dataStep2.wifiDevice).title}}</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getWiFiDevice(dataStep2.wifiDevice).price.connection_price)}} <span>р</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="make_order_app_result_3" v-if="nowStep >= 3 && dataStep3.canceled == false">
                            <div class="title">
                                <div class="icon"></div>
                                <div class="text">Телевидение:</div>
                            </div>
                            <div class="content">
                                <div class="items">
                                    <div class="item" v-if="dataStep3.tvPlan !== null">
                                        <span class="t">«{{getTvPlan(dataStep3.tvPlan).title}}» {{getTvPlan(dataStep3.tvPlan).scale_title}}</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getTvPlan(dataStep3.tvPlan).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="suming" v-if="nowStep >= 2">
                        <div class="title">
                            <div class="icon"></div>
                            <div class="text">Предварительный расчет:</div>
                        </div>
                        <div class="items">
                            <div>
                                <div class="i1">
                                    <div class="t">Ежемесячный платеж:</div>
                                    <div class="v" :class="{n : servicesSale > 0}">{{coolNumber(monthlyPayment)}} <span>р/мес</span></div>
                                </div>
                                <div class="i1" v-if="servicesSale > 0">
                                    <div class="t"><span class="r">*</span> С учетом скидки <span class="b">{{servicesSale}}%</span>:</div>
                                    <div class="v">{{coolNumber(monthlyPaymentWithSale)}} <span>р/мес</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="i1">
                                    <div class="t">Стартовый платеж:</div>
                                    <div class="v">{{coolNumber(startPayment)}} <span>р</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="i2">
                                    <div class="t">К оплате:</div>
                                    <div class="v">{{coolNumber(fullStartPayment)}} <span>р</span></div>
                                </div>
                            </div>
                            <div v-if="servicesSale > 0">
                                <div class="i3">
                                    <div class="t">*</div>
                                    <div class="v">при выборе <template v-if="servicesCount == 2">двух</template><template v-if="servicesCount > 2">трех и более</template> услуг скидка составляет <span>{{servicesSale}}%</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons">
            <div>
                <div class="back" v-if="showButtonBack">
                    <a href="javascript://" class="button_5 pbig" @click="goBackStep">Назад</a>
                    <a href="javascript://" class="b" @click="goBackStep"><span>&nbsp;</span></a>
                </div>
                <div class="next">
                    <a href="javascript://" class="button_3 pbig" @click="goNextStep">Далее</a>
                </div>
                <div class="cancel" v-if="showButtonCancel">
                    <a href="javascript://">Пропустить <span>этот шаг</span></a>
                </div>
            </div>
        </div>
    </div>
    

</div>
</template>

<script>

export default {
    props : {
    },
    data () {
        return {
            nowStep : 1,
            dataAddressId : undefined,
            dataStep2 : {
                canceled : false,
                internetPlan : null,
                staticIp : false,
                optDevice : null,
                wifiDevice : null,
            },
            dataStep3 : {
                canceled : false,
                tvPlan : null,
                tvBox : null,
                t : null
            }
        }
    },
    computed : {
        //Списки
        addressesOptionsList()
        {
            let result = [];
            this.formData.addresses.forEach(e => {
                result.push({value : e.id, text : `${e.title} (${e.address})`, title : e.title, address : e.address});
            });
            return result;
        },
        addressesInternetPlansList()
        {
            let result = [];
            for (let i in this.formData.products.internet_basic)
            {
                let e = this.formData.products.internet_basic[i];
                if (this.currentDataAddress.internet_plans.indexOf(e.id) > -1)
                {
                    result.push({value : e.id, text : `${e.speed} Мбит/с`, speed : e.speed});
                }
            }
            return result;
        },
        addressesInternetAdds()
        {
            return this.formData.products.additional_internet;
        },
        addressesTvPlansList()
        {
            let result = [];
            for (let i in this.formData.products.tv_basic)
            {
                let e = this.formData.products.tv_basic[i];
                if (this.currentDataAddress.tv_plans.indexOf(e.id) > -1)
                {
                    result.push({value : e.id, text : e.scale_title});
                }
            }
            return result;
        },

        //Генераторы
        currentDataAddress()
        {
            let i = this.formData.addresses.findIndex((e) => {
                return e.id == this.dataAddressId;
            });
            return this.formData.addresses[(i == -1 ? 0 : i)]
        },
        currentAddressConType()
        {
            return this.currentDataAddress.connection_type.id == 1 || this.currentDataAddress.connection_type.id == 3 ? 'gpon' : 'ethernet';
        },

        //Кнопки
        showButtonBack()
        {
            if (this.nowStep > 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        },
        showButtonCancel()
        {
            if (this.nowStep > 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        },

        //Стоимость

        servicesCount()
        {
            let r = 0;
            if (this.nowStep >= 2 && this.dataStep2.canceled == false)
            {
                r++;
            }

            return r;
        },

        servicesSale()
        {
            let s = this.servicesCount;

            if (s == 2)
            {
                return 10;
            }
            else if (s > 2)
            {
                return 15;
            }
            else
            {
                return 0;
            }
        },

        monthlyPayment()
        {
            let r = 0;
            if (this.nowStep >= 2 && this.dataStep2.canceled == false)
            {
                if (this.dataStep2.internetPlan !== null)
                {
                    r += this.getInternetPlan(this.dataStep2.internetPlan).price.monthly_price;
                }

                if (this.dataStep2.staticIp)
                {
                    r += this.addressesInternetAdds['3'].price.monthly_price;
                }
            }

            return r;
        },

        monthlyPaymentWithSale()
        {
            let r = this.monthlyPayment;
            let s = this.servicesSale;

            if (s == 0)
            {
                return r;
            }

            return Math.round(r - (r * (1/s)));
        },

        startPayment()
        {
            let r = 0;
            if (this.nowStep >= 2 && this.dataStep2.canceled == false)
            {
                if (this.dataStep2.optDevice !== null && this.currentAddressConType == 'gpon')
                {
                    r += this.getOntDevice(this.dataStep2.optDevice).price.connection_price;
                }

                if (this.dataStep2.wifiDevice !== null)
                {
                    r += this.getWiFiDevice(this.dataStep2.wifiDevice).price.connection_price;
                }
            }

            return r;
        },

        fullStartPayment()
        {
            return this.monthlyPaymentWithSale + this.startPayment;
        }
    },
    methods : {
        //Геттеры
        getInternetPlan(d)
        {
            return this.formData.products.internet_basic[d];
        },
        getOntDevice(d)
        {
            return this.formData.products.devices_internet_ont[d];
        },
        getWiFiDevice(d)
        {
            return this.formData.products.devices_internet_wifi[d];
        },
        getTvPlan(d)
        {
            return this.formData.products.tv_basic[d];
        },
        getTvBox()
        {
            return this.formData.products.devices_tv_box['36'];
        },

        //Функции
        coolNumber(v)
        {
            return global.eApi.getFuncs().numberFormat(v, (v == parseInt(v) ? 0 : 2), ',', ' ');
        },

        showErrors(errors)
        {
            global.projectApi.getFuncs().showErrorsInBox(errors);
        },

        //Системное
        checkStep()
        {
            if (this.nowStep == 2)
            {
                if (this.currentAddressConType == 'gpon' && this.dataStep2.optDevice === null)
                {
                    this.showErrors(['В вашем жилом комплексе подключение к интернету осуществляется по технологии GPON. Для продолжения необходимо выбрать подходящий ont-модем.']);
                    return false;
                }
            }

            return true;
        },
        goNextStep()
        {
            if (this.checkStep())
            {
                if (this.nowStep < 5)
                {
                    this.nowStep++;
                }
                else
                {

                }
            }
        },
        goBackStep()
        {
            if (this.nowStep > 1)
            {
                this.nowStep--;
            }
        },
        goToStepFromTops(step)
        {
            if (step < this.nowStep)
            {
                this.nowStep = step;
            }
        },
        internetPlansFormHelpBlock : function(id)
        {
            if (typeof this.formData.products.internet_basic[id] != 'undefined')
            {  
                let e = this.formData.products.internet_basic[id];
                let pr = e.price.monthly_price;
                let tpl = `
                    <div class="internet_plan_help_wrapper">
                        <div class="title">${e.title}</div>
                        <div class="price">${pr} <span>р/мес</span></div>`;
                if (e.price.connection_price > 0)
                {
                    let prc = e.price.connection_price;
                    tpl += `<div class="price">${prc} <span>подключение</span></div>`;
                }

                if (e.description !== null)
                {
                    tpl += `<div class="desc">${e.description}</div>`;
                }

                tpl += `</div>`;

                return tpl;
            }
           
            return '';
        },

        tvPlansFormHelpBlock : function(id)
        {
            
            if (typeof this.formData.products.tv_basic[id] != 'undefined')
            {  
                let e = this.formData.products.tv_basic[id];
                let pr = e.price.monthly_price;
                let tpl = `
                    <div class="tv_plan_help_wrapper">
                        <div class="st">тариф</div>
                        <div class="title">${e.title}</div>
                        <div class="scale_title">${e.scale_title}</div>
                        <div class="price">${pr} <span>р/мес</span></div>`;
                if (e.price.connection_price > 0)
                {
                    let prc = e.price.connection_price;
                    tpl += `<div class="price">${prc} <span>подключение</span></div>`;
                }

                tpl += `<div class="bot">`;

                if (e.description !== null)
                {
                    tpl += `<div class="desc">${e.description}</div>`;
                }

                tpl += `<div class="list_link"><a href="javascript://">Список каналов</a></div></div></div>`;

                return tpl;
            }
           
            return '';
        }
    },
    watch : {
        nowStep : function(v) {
            let t = document.getElementById('app_page_wrapper').getBoundingClientRect().top + window.pageYOffset;
            if (t < (document.scrollingElement ? document.scrollingElement.scrollTop : window.document.body.scrollTop))
            {
                window.scrollTo({ top: t});
            }

            if (v == 2)
            {
                if (this.dataStep2.optDevice !== null && this.currentDataAddress.connection_type.devices.findIndex((e) => {
                    return e.toString() == this.dataStep2.optDevice.toString();
                }) == -1) {
                    this.dataStep2.optDevice = null;
                }

                if (this.dataStep2.wifiDevice !== null && this.currentDataAddress.connection_type.devices.findIndex((e) => {
                    return e.toString() == this.dataStep2.wifiDevice.toString();
                }) == -1) {
                    this.dataStep2.wifiDevice = null;
                }

                /*
                if (this.currentAddressConType == 'gpon')
                {
                    let c = 0;
                    let v = undefined;
                    this.currentDataAddress.connection_type.devices.forEach((e) => {
                        if (this.formData.products.devices_internet_ont[e] !== undefined)
                        {
                            c++;
                            v = e;
                        }
                    });
                    if (c == 1)
                    {
                        this.dataStep2.optDevice = v;
                    }
                }
                */
            }
        }
    },
    created() {
        this.formData = window.order_form_data;

    },
    beforeUpdate() {
        
    },
    mounted() {
        //console.log(this.$refs['client_phone']);
        //console.log(Inputmask);
        //Inputmask({"mask": "+7 (999) 999-9999"}).mask(this.$refs['client_phone']);
        //this.inputmask_phone = new Inputmask("+7 (999) 999-9999");
       // this.inputmask_phone.mask(this.$refs['client_phone']);
    },
    beforeDestroy() {
        
    },
}
</script>

<style>
</style>
