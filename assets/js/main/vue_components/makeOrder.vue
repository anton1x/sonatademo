<template>
<div id="make_order_app_wrapper" ref="app_wrapper">
    <div class="top">
        <div class="step_1" :class="{sel : nowStep >= 1}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(1)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(1)">выбор адреса</a>
            </div>
        </div>
        <div class="step_2" :class="{sel : nowStep >= 2 && this.dataStep2.canceled == false}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(2)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(2)">интернет</a>
            </div>
        </div>
        <div class="step_3" :class="{sel : nowStep >= 3 && this.dataStep3.canceled == false}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(3)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(3)">телевидение</a>
            </div>
        </div>
        <div class="step_4" :class="{sel : nowStep == 4 || (nowStep > 4 && (this.dataStep4.canceled == false && (checkPhoneHasTariff || checkVisionHomeHasTariff || checkVisionParkingHasTariff)))}">
            <div class="h">
                <a href="javascript://" class="circle" @click="goToStepFromTops(4)"><span>&nbsp;</span></a>
                <a href="javascript://" class="title" @click="goToStepFromTops(4)">дополнительные услуги</a>
            </div>
        </div>
        <div class="step_5" :class="{sel : nowStep >= 5}">
            <div class="h">
                <a href="javascript://" class="circle"><span>&nbsp;</span></a>
                <a href="javascript://" class="title">оформление заявки</a>
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
                    </div>
                </div>
            </template>
            <template v-if="nowStep == 2">
                <div class="step_wrapper" id="make_order_app_step_2_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Настройте Интернет как вам нужно</div>
                        <div class="input" :style="{maxWidth : (addressesInternetPlansList.length * 180) + 'px'}">
                            <ui-liner :options="addressesInternetPlansList" v-model="dataStep2.internetPlan" :helpblock="internetPlansFormHelpBlock" :key="'internet_liner'">
                                 <template v-slot:title="i">
                                    <span class="mb">{{i.speed}}</span> Мбит/с
                                </template>   
                            </ui-liner>
                        </div>
                    </div>
                    <div class="block_wrapper">
                        <div class="title">Выбор оборудования</div>
                        <div id="make_order_app_step_2_devices" :class="{gpon : currentAddressConType == 'gpon'}">
                            <div class="gpon_block" v-if="currentAddressConType == 'gpon'">
                                <div class="desc">
                                    <div class="title">Внимание!</div>
                                    <div class="text">В вашем жилом комплексе подключение к услуге доступа в Интернет осуществляется по современной технологии <span>GPON (оптика в квартиру)</span>. <span>Обязательным оборудованием для подключения является PON-модем Eltex</span>.</div>
                                    <div class="link"><a href="/upload/media/documents/0001/01/15e3ef476f87e2730e5dca69d884c17d52f5a911.pdf" target="_blank">Подробнее</a></div>
                                </div>
                                <div class="devices">
                                    <template v-for="(d,i) in currentDataAddress.connection_type.devices">
                                        <template v-if="formData.products.devices_internet_ont[d] !== undefined">
                                            <div :key="i + '_ont_devices'">
                                                <div class="image" :style="{'background-image' : 'url('+getOntDevice(d).image.url.reference+')'}" @click="dataStep2.optDevice = d"><div></div></div>
                                                <div class="title" @click="(dataStep2.optDevice = d)">{{getOntDevice(d).title}}</div>
                                                <div class="desc" v-html="getOntDevice(d).description"></div>
                                                <div class="bot">
                                                    <div class="price">{{coolNumber(getOntDevice(d).price.connection_price)}} <span>Р</span></div>
                                                    <div class="switcher">
                                                        <div class="input">
                                                            <ui-switcher v-model="dataStep2.optDevice" :truevalue="d" :falsevalue="null" :usercanoff="false" mode="checkbox" :key="i + '_ont_devices_switcher'"></ui-switcher>
                                                        </div>
                                                        <div class="result">
                                                            <span v-if="dataStep2.optDevice == d" class="selected">выбрано</span>
                                                            <span v-else @click="(dataStep2.optDevice = d)" class="sel">выбрать</span>
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
                                            <div :key="i + '_wifi_devices'" :class="{not_sel : dataStep2.wifiDevice != d && dataStep2.wifiDevice !== null}">
                                                <div class="image" :style="{'background-image' : 'url('+getWiFiDevice(d).image.url.reference+')'}"><div :style="{'min-height' : Math.round((160 / getWiFiDevice(d).image.sizes.reference.width) * getWiFiDevice(d).image.sizes.reference.height) + 'px' }"></div></div>
                                                <div class="content">
                                                    <div class="title">{{getWiFiDevice(d).title}}</div>
                                                    <div class="desc" v-html="getWiFiDevice(d).description"></div>
                                                    <div class="price">{{coolNumber(getWiFiDevice(d).price.connection_price)}} <span>Р</span></div>
                                                    <div class="switcher">
                                                        <div class="input">
                                                            <ui-switcher v-model="dataStep2.wifiDevice" :truevalue="d" :falsevalue="null" mode="checkbox" :key="i + '_wifi_devices_switcher'"></ui-switcher>
                                                        </div>
                                                        <!--
                                                        <div class="result">
                                                            <template v-if="dataStep2.wifiDevice == d">
                                                                <span class="selected">выбрано</span>
                                                                <span class="cancel" @click="(dataStep2.wifiDevice = null)">отказаться от роутера</span>
                                                            </template>
                                                            <span v-else @click="(dataStep2.wifiDevice = d)" class="sel">выбрать</span>
                                                        </div>
                                                        -->
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
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
                </div>
            </template>
            <template v-if="nowStep == 3">
                <div class="step_wrapper" id="make_order_app_step_3_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Настройте ваш телевизор</div>
                        <div class="input" :style="{maxWidth : (addressesTvPlansList.length * 180) + 'px'}">
                            <ui-liner :options="addressesTvPlansList" v-model="dataStep3.tvPlan" :helpblock="tvPlansFormHelpBlock" :key="'tv_liner'"></ui-liner>
                        </div>
                        <div class="simple">
                            <div id="make_order_app_step_3_tv_box">
                                <div class="title">Внимание!</div>
                                <div class="text"><span>При отсутствии технологии Smart TV</span> в вашем телевизоре для просмотра Интерактивного телевидения понадобится ТВ-приставка.</div>
                                <div class="link"><a href="javascript://" target="_blank">Подробнее</a></div>
                                <div class="item">
                                    <div class="image" :style="{'background-image' : 'url('+getTvBox().image.url.reference+')'}"><div :style="{'min-height' : Math.round((160 / getTvBox().image.sizes.reference.width) * getTvBox().image.sizes.reference.height) + 'px' }"></div></div>
                                    <div class="content">
                                        <div class="price">{{coolNumber(getTvBox().price.connection_price)}} <span>Р</span></div>
                                        <div class="switcher">
                                            <div class="input">
                                                <ui-switcher v-model="dataStep3.tvBox" :truevalue="getTvBox().id" :falsevalue="null" mode="checkbox" :key="getTvBox().id + '_tvbox_devices_switcher'" class="invert"></ui-switcher>
                                            </div>
                                            <div class="result">
                                                <template v-if="dataStep3.tvBox == getTvBox().id">
                                                    <span class="selected">выбрано</span>
                                                    <!--<span class="cancel" @click="(dataStep3.tvBox = null)">отказаться</span>-->
                                                </template>
                                                <span v-else @click="(dataStep3.tvBox = getTvBox().id)" class="sel">выбрать</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simple">
                            <block-fading v-model="dataStep3.openedAddons" title="Дополнительные пакеты ТВ" :key="'tv_addons_block'">
                                <template v-slot:icon><div id="make_order_app_step_3_icon_addons"></div></template>
                                <template v-slot:content="data">
                                    <div class="fading_block_list">
                                        <div v-for="(e, k) in addressesTvAddonsList" :key="k + '_tv_addons'" :class="{sel : dynamicListTvAddons[e.id]}">
                                            <div class="title"><span @click="openAddonsInfo(e.id)">{{e.title}}</span></div>
                                            <div class="price">{{e.price.monthly_price}} <span>р/мес</span></div>
                                            <div class="switcher"><ui-switcher v-model="dynamicListTvAddons[e.id]" mode="checkbox" :key="e.id + '_tv_addons_switcher'"></ui-switcher></div>
                                        </div>
                                    </div>
                                </template>  
                            </block-fading>
                        </div>
                        <div class="simple" v-if="dataStep3.tvPlan !== null && formData.products.tv_basic[dataStep3.tvPlan].include_theatres == false">
                            <block-fading v-model="dataStep3.openedTheathers" title="Онлайн кинотеатры" :key="'tv_theathers_block'">
                                <template v-slot:icon><div id="make_order_app_step_3_icon_theathers"></div></template>
                                <template v-slot:content="data">
                                    <div class="fading_block_list">
                                        <div v-for="(e, k) in addressesTvTheatresList" :key="k + '_tv_theathers'" :class="{sel : dynamicListTvTheathers[e.id]}">
                                            <div class="title"><span @click="openTheatherInfo(e.id)">{{e.title}}</span></div>
                                            <div class="price">{{e.price.monthly_price}} <span>р/мес</span></div>
                                            <div class="switcher"><ui-switcher v-model="dynamicListTvTheathers[e.id]" mode="checkbox" :key="e.id + '_tv_theathers_switcher'"></ui-switcher></div>
                                        </div>
                                    </div>
                                </template>  
                            </block-fading>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="nowStep == 4">
                <div class="step_wrapper" id="make_order_app_step_4_wrapper">
                    <div class="block_wrapper">
                        <div class="title">Дополнительные услуги</div>
                        <div class="simple">
                             <block-fading v-model="dataStep4.openedPhone" title="Телефония" :key="'phone_block'">
                                <template v-slot:icon><div id="make_order_app_step_4_icon_phone"></div></template>
                                <template v-slot:content>
                                    <div class="fading_block_wrapper">
                                        <div id="make_order_app_step_4_phone_block">
                                            <div class="block_wrapper">
                                                <div class="title">Тарифы</div>
                                                <div class="simple tariffs_block">
                                                    <div v-for="(i,k) in addressesPhoneTariffsList">
                                                        <div class="input">
                                                            <ui-switcher v-model="dataStep4.phoneTariff" :truevalue="i.id" :falsevalue="null" :key="i.id + '_phone_tariff_switcher'"></ui-switcher>
                                                        </div>
                                                        <div class="title"><span @click="(dataStep4.phoneTariff = i.id)">{{i.title}}</span></div>
                                                        <div class="info">
                                                            <div class="price">{{coolNumber(i.price.monthly_price)}} <span>р/мес</span></div>
                                                            <div class="desc" v-html="i.description"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block_wrapper">
                                                <div class="title">Выберите домашний телефон</div>
                                                <div class="simple">
                                                    <block-fading v-model="dataStep4.openedPhoneDectDevs" title="DECT-телефоны" :key="'phone_block_devices_dect'">
                                                        <template v-slot:content>
                                                            <div class="fading_block_wrapper phone_devices">
                                                                <div v-for="(i,k) in addressesPhoneDectDevicesList">
                                                                    <div class="image" :style="{'background-image' : 'url('+i.image.url.reference+')'}"><div :style="{'padding-top' : (Math.round((i.image.sizes.reference.height / i.image.sizes.reference.width) * 100 * 1000) / 1000) + '%' }"></div></div>
                                                                    <div class="title">{{i.title}}</div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                    <div class="price">{{coolNumber(i.price.connection_price)}} <span>р</span></div>
                                                                    <div class="input"><ui-counter v-model="dynamicListPhoneDectDevices[i.id]" :key="'phone_block_devices_dect' + i.id"></ui-counter></div>
                                                                </div>
                                                            </div>
                                                        </template>  
                                                    </block-fading>
                                                </div>
                                                <div class="simple">
                                                    <block-fading v-model="dataStep4.openedPhoneTableDevs" title="Настольные телефоны" :key="'phone_block_devices_table'">
                                                        <template v-slot:content>
                                                            <div class="fading_block_wrapper phone_devices">
                                                                <div v-for="(i,k) in addressesPhoneTableDevicesList">
                                                                    <div class="image" :style="{'background-image' : 'url('+i.image.url.reference+')'}"><div :style="{'padding-top' : (Math.round((i.image.sizes.reference.height / i.image.sizes.reference.width) * 100 * 1000) / 1000) + '%' }"></div></div>
                                                                    <div class="title">{{i.title}}</div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                    <div class="price">{{coolNumber(i.price.connection_price)}} <span>р</span></div>
                                                                    <div class="input"><ui-counter v-model="dynamicListPhoneTableDevices[i.id]" :key="'phone_block_devices_table' + i.id"></ui-counter></div>
                                                                </div>
                                                            </div>
                                                        </template>  
                                                    </block-fading>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>  
                            </block-fading>
                        </div>
                        <div class="simple">
                            <block-fading v-model="dataStep4.openedVision" title="Видеонаблюдение" :key="'vision_block'">
                                <template v-slot:icon><div id="make_order_app_step_4_icon_vision"></div></template>
                                <template v-slot:content>
                                    <div class="fading_block_wrapper">
                                        <block-taps v-model="dataStep4.openedVisionTap" :taps="{home : 'В квартире', parking : 'На паркинге'}" :key="'vision_block_taps'">
                                            <template v-slot:tap_home>
                                                <div class="make_order_app_step_4_vision">
                                                    <div class="block_wrapper">
                                                        <div class="title">Тарифы</div>
                                                        <div class="desc">Для всех тарифов действуют <strong>локальный видеоархив</strong> без ограничений и <strong>интеллектуальные уведомления</strong> о движении и звуке.</div>
                                                        <div class="simple tariffs_block">
                                                            <div v-for="(i,k) in addressesVisionHomeTariffsList">
                                                                <div class="input">
                                                                    <ui-switcher v-model="dataStep4.visionHomeTariff" :truevalue="i.id" :falsevalue="null" :key="i.id + '_vision_home_tariff_switcher'"></ui-switcher>
                                                                </div>
                                                                <div class="title"><span @click="(dataStep4.visionHomeTariff = i.id)">{{i.title}}</span></div>
                                                                <div class="info">
                                                                    <div class="price">{{coolNumber(i.price.monthly_price)}} <span>р/мес</span></div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block_wrapper">
                                                        <div class="title">Выберите видеокамеру</div>
                                                        <div class="desc">Камеры не требуют сложной установки, можно поставить на полку, стол или например прикрепить к холодильнику. Подключение к электропитанию 220В.</div>
                                                        <div class="simple vision_devices">
                                                            <div v-for="(i,k) in addressesVisionHomeDevicesList">
                                                                <div class="image" :style="{'background-image' : 'url('+i.image.url.reference+')'}"><div :style="{'padding-top' : (Math.round((i.image.sizes.reference.height / i.image.sizes.reference.width) * 100 * 1000) / 1000) + '%' }"></div></div>
                                                                <div class="info">
                                                                    <div class="title">{{i.title}}</div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                    <div class="price">{{coolNumber(i.price.connection_price)}} <span>р</span></div>
                                                                    <div class="input"><ui-counter v-model="dynamicListVisionHomeDevices[i.id]" :key="'vision_block_devices_home' + i.id"></ui-counter></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template v-slot:tap_parking>
                                                <div class="make_order_app_step_4_vision">
                                                    <div class="attention">
                                                        <div class="title">Внимание!</div>
                                                        <div class="text">
                                                            Стоимость подключения PoE-порта в подземном паркинге составляет <span class="price">{{getVisionParkingPoe().price.connection_price}}  р.</span>, является разовым платежом. Интернет и электропитание для камеры осуществляется одним кабелем.
                                                            <br><br>
                                                            Абонентская плата за порт и интернет-подключение со скоростью до 10 Мбит/с составляет
                                                        </div>
                                                        <div class="price">{{getVisionParkingPoe().price.monthly_price}} <span>р/мес</span></div>
                                                    </div>
                                                    <div class="block_wrapper">
                                                        <div class="title">Тарифы</div>
                                                        <div class="desc">Для всех тарифов действуют <strong>локальный видеоархив</strong> без ограничений и <strong>интеллектуальные уведомления</strong> о движении и звуке.</div>
                                                        <div class="simple tariffs_block">
                                                            <div v-for="(i,k) in addressesVisionParkingTariffsList">
                                                                <div class="input">
                                                                    <ui-switcher v-model="dataStep4.visionParkingTariff" :truevalue="i.id" :falsevalue="null" :key="i.id + '_vision_parking_tariff_switcher'"></ui-switcher>
                                                                </div>
                                                                <div class="title"><span @click="(dataStep4.visionParkingTariff = i.id)">{{i.title}}</span></div>
                                                                <div class="info">
                                                                    <div class="price">{{coolNumber(i.price.monthly_price)}} <span>р/мес</span></div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block_wrapper">
                                                        <div class="title">Выберите видеокамеру</div>
                                                        <div class="desc">Камеры не требуют сложной установки, можно поставить на полку, стол или например прикрепить к холодильнику. Подключение к электропитанию 220В.</div>
                                                        <div class="simple vision_devices">
                                                            <div v-for="(i,k) in addressesVisionParkingDevicesList">
                                                                <div class="image" :style="{'background-image' : 'url('+i.image.url.reference+')'}"><div :style="{'padding-top' : (Math.round((i.image.sizes.reference.height / i.image.sizes.reference.width) * 100 * 1000) / 1000) + '%' }"></div></div>
                                                                <div class="info">
                                                                    <div class="title">{{i.title}}</div>
                                                                    <div class="desc" v-html="i.description"></div>
                                                                    <div class="price">{{coolNumber(i.price.connection_price)}} <span>р</span></div>
                                                                    <div class="input"><ui-counter v-model="dynamicListVisionParkingDevices[i.id]" :key="'vision_block_devices_parking' + i.id"></ui-counter></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template> 
                                        </block-taps>
                                    </div>
                                </template>  
                            </block-fading>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="nowStep == 5">
                <div class="step_wrapper" id="make_order_app_step_5_wrapper">
                    <div class="title">Оформление заявки на подключение</div>
                    <div class="desc"><b>Поздравляем!</b> Остался всего один заключительный шаг — оформление заявки на подключение услуг. По Российскому законодательству для этого понадобятся паспортные данные. Выберите пожалуйста способ их внесения —  в Онлайн режиме или с помощью нашего менеджера, который свяжется с вами и заполнит за вас всё что необходимо. <b>Подключение производится в течение 1 дня после оформления заявки и оплаты услуг связи.</b></div>
                    <div class="taps">
                        <block-taps v-model="dataStep5.openedTap" :taps="{self : 'Онлайн режим', phone : 'Помощь менеджера'}" :key="'final_block_taps'" class="nohp">
                            <template v-slot:tap_self>
                                <div class="contacts_form">
                                    <div class="static">
                                        <div class="title">Жилой комплекс:</div>
                                        <div class="value">{{currentDataAddress.title}} ({{currentDataAddress.address}})</div>
                                    </div>
                                    <div class="dbl">
                                        <div class="field" v-if="currentDataAddress.need_building_input">
                                            <div class="title"><span>*</span> Корпус:</div>
                                            <div class="input"><template v-if="currentDataAddress.available_buildings.length > 0"><ui-select :options="currentAddressAvailableBuildingsList" v-model="dataStep5.input_building" :key="'input_building_selector'"></ui-select></template><template v-else><input type="text" v-model="dataStep5.input_building"></template></div>
                                        </div>
                                        <div class="field">
                                            <div class="title"><span>*</span> Квартира:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_apartment"></div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="title"><span>*</span> Ф.И.О.:</div>
                                        <div class="input"><input type="text" v-model="dataStep5.input_fio"></div>
                                    </div>
                                    <div class="dbl">
                                        <div class="field">
                                            <div class="title"><span>*</span> Серия паспорта:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_passport_series"></div>
                                        </div>
                                        <div class="field">
                                            <div class="title"><span>*</span> Номер паспорта:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_passport_num"></div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="title"><span>*</span> Кем и когда выдан:</div>
                                        <div class="input"><textarea v-model="dataStep5.input_passport_who" style="height:70px"></textarea></div>
                                    </div>
                                    <div class="field">
                                        <div class="title"><span>*</span> Адрес регистрации:</div>
                                        <div class="input"><input type="text" v-model="dataStep5.input_passport_address"></div>
                                    </div>
                                    <div class="field">
                                        <div class="title"><span>*</span> Дата рождения:</div>
                                        <div class="input">
                                            <div class="birthday_wrapper">
                                                <div class="day">
                                                    <ui-select :options="selectDaysList" v-model="dataStep5.input_bday" :key="'bday_selector'"></ui-select>
                                                </div>
                                                <div class="month">
                                                    <ui-select :options="selectMonthsList" v-model="dataStep5.input_bmonth" :key="'bmonth_selector'"></ui-select>
                                                </div>
                                                <div class="year">
                                                    <ui-select :options="selectYearsList" v-model="dataStep5.input_byear" :key="'byear_selector'"></ui-select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dbl">
                                        <div class="field">
                                            <div class="title"><span>*</span> E-mail:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_email"></div>
                                        </div>
                                        <div class="field">
                                            <div class="title"><span>*</span> Телефон:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_phone" ref="step_5_phone_1"></div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="title">Комментарий к заявке:</div>
                                        <div class="input"><textarea v-model="dataStep5.input_comment" style="height:100px"></textarea></div>
                                    </div>
                                </div>
                            </template>
                            <template v-slot:tap_phone>
                                <div class="contacts_form">
                                    <div class="static">
                                        <div class="title">Жилой комплекс:</div>
                                        <div class="value">{{currentDataAddress.title}} ({{currentDataAddress.address}})</div>
                                    </div>
                                    <div class="field">
                                        <div class="title"><span>*</span> Ф.И.О.:</div>
                                        <div class="input"><input type="text" v-model="dataStep5.input_fio"></div>
                                    </div>
                                    <div class="dbl">
                                        <div class="field">
                                            <div class="title"><span>*</span> Телефон:</div>
                                            <div class="input"><input type="text" v-model="dataStep5.input_phone" ref="step_5_phone_2"></div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="title">Комментарий к заявке:</div>
                                        <div class="input"><textarea v-model="dataStep5.input_comment" style="height:100px"></textarea></div>
                                    </div>
                                </div>
                            </template>
                        </block-taps>
                    </div>
                    <div class="connect_timing">
                        <block-fading v-model="dataStep5.openedConnectTime" title="Желаемая дата подключения" :key="'connect_time'">
                            <template v-slot:icon><div id="make_order_app_step_5_icon_connect_timing"></div></template>
                            <template v-slot:content>
                                <div class="fading_block_wrapper contacts_form">
                                    <div class="field">
                                        <div class="title">Дата:</div>
                                        <div class="input">
                                            <div class="birthday_wrapper">
                                                <div class="day">
                                                    <ui-select :options="selectConnectDaysList" v-model="dataStep5.input_cday" :key="'cday_selector'"></ui-select>
                                                </div>
                                                <div class="month">
                                                    <ui-select :options="selectMonthsList" v-model="dataStep5.input_cmonth" :key="'cmonth_selector'"></ui-select>
                                                </div>
                                                <div class="year">
                                                    <ui-select :options="selectConnectYearsList" v-model="dataStep5.input_cyear" :key="'cyear_selector'"></ui-select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="title">Время:</div>
                                        <div class="input">
                                            <div class="hours_wrapper">
                                                <!--
                                                <div>с:</div>
                                                <div class="start">
                                                    <ui-select :options="selectHoursList" v-model="dataStep5.input_chour_start" :key="'chour_start_selector'"></ui-select>
                                                </div>
                                                <div>до:</div>
                                                <div class="end">
                                                    <ui-select :options="selectHoursList" v-model="dataStep5.input_chour_end" :key="'chour_end_selector'"></ui-select>
                                                </div>
                                                -->
                                                <ui-select :options="selectCHoursList" v-model="dataStep5.input_chour" :key="'chour_start_selector'"></ui-select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>  
                        </block-fading>
                    </div>
                    <!--
                    <div class="checkbox_block">
                        <div class="checkbox"><ui-switcher v-model="dataStep5.checkbox_already_client" mode="checkbox" :key="'checkbox_already_client'"></ui-switcher></div>
                        <div class="info">
                            <div class="text"><span @click="(dataStep5.checkbox_already_client = true)">Я являюсь действующим клиентом</span></div>
                            <div class="desc">Отметьте, пожалуйста, галочкой, если Вы являетесь действующим абонентом РосфонДом.</div>
                        </div>
                    </div>
                    -->
                    <!--
                    <div class="checkbox_block">
                        <div class="checkbox"><ui-switcher v-model="dataStep5.checkbox_from_other_operator" mode="checkbox" :key="'checkbox_from_other_operator'"></ui-switcher></div>
                        <div class="info">
                            <div class="text"><span @click="(dataStep5.checkbox_from_other_operator = true)">Я переключаюсь с другого оператора связи</span></div>
                        </div>
                    </div>
                    -->
                    <div class="checkbox_block">
                        <div class="checkbox"><ui-switcher v-model="dataStep5.checkbox_policy" mode="checkbox" :key="'checkbox_policy'"></ui-switcher></div>
                        <div class="info">
                            <div class="text"><span @click="(dataStep5.checkbox_policy = true)">Даю согласие на обработку персональных данных и принимаю условия</span> <a href="/upload/media/documents/0001/01/2854a85e4ea8ce9412ee86cd53da65329da320a8.pdf" target="_blank">политики конфиденциальности</a></div>
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
                                <div class="text">Жилой комплекс:</div>
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
                                        <span class="t">«<span class="black">{{getTvPlan(dataStep3.tvPlan).title}}</span>» <span class="gray light">{{getTvPlan(dataStep3.tvPlan).scale_title}}</span></span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getTvPlan(dataStep3.tvPlan).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                    <div class="item" v-if="dataStep3.tvBox !== null">
                                        <span class="t"><span class="black">{{getTvBox().title}}</span></span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getTvBox().price.connection_price)}} <span>р/мес</span></span>
                                    </div>
                                </div>
                                <template v-if="currentAddressTvAddons.length > 0">
                                <div class="title">Дополнительные пакеты ТВ:</div>
                                <div class="items">
                                    <div v-for="i in currentAddressTvAddons" class="item smt">
                                        <span class="t"><span class="black">{{getTvAddon(i).title}}</span></span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getTvAddon(i).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                </div>
                                </template>
                                <template v-if="currentAddressTvTheathers.length > 0">
                                <div class="title">Онлайн кинотеатры:</div>
                                <div class="items">
                                    <div v-for="i in currentAddressTvTheathers" class="item smt">
                                        <span class="t"><span class="black">{{getTvTheather(i).title}}</span></span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getTvTheather(i).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                </div>
                                </template>
                            </div>
                        </div>
                        <div id="make_order_app_result_phone" v-if="checkPhoneHasTariff">
                            <div class="title">
                                <div class="icon"></div>
                                <div class="text">Телефония:</div>
                            </div>
                            <div class="content">
                                <div class="items">
                                    <div class="item" v-if="dataStep4.phoneTariff !== null">
                                        <span class="t">«<span class="black">{{getPhoneTariff(dataStep4.phoneTariff).title}}</span>»</span>
                                        <span class="d">—</span>
                                        <span class="p">{{coolNumber(getPhoneTariff(dataStep4.phoneTariff).price.monthly_price)}} <span>р/мес</span></span>
                                    </div>
                                </div>
                                <div class="title">Оборудование:</div>
                                <template v-if="currentAddressPhoneDectDevices.length > 0 || currentAddressPhoneTableDevices.length > 0">
                                    <div class="items">
                                    <div v-for="i in currentAddressPhoneDectDevices" class="item smt">
                                        <span class="t"><span class="light gray">DECT-телефон</span> «<span class="black">{{getPhoneDectDevice(i).title}}</span>»</span>
                                        <span class="d">—</span>
                                        <span class="p"><i>{{dynamicListPhoneDectDevices[i]}} шт.,</i> {{coolNumber(getPhoneDectDevice(i).price.connection_price * dynamicListPhoneDectDevices[i])}} <span>р</span></span>
                                    </div>
                                    <div v-for="i in currentAddressPhoneTableDevices" class="item smt">
                                        <span class="t"><span class="light gray">Настольный телефон</span> «<span class="black">{{getPhoneTableDevice(i).title}}</span>»</span>
                                        <span class="d">—</span>
                                        <span class="p"><i>{{dynamicListPhoneTableDevices[i]}} шт.,</i> {{coolNumber(getPhoneTableDevice(i).price.connection_price * dynamicListPhoneTableDevices[i])}} <span>р</span></span>
                                    </div>
                                </div>
                                </template>
                                <template v-else>
                                <div class="no_item">не выбрано</div>
                                </template>
                            </div>
                        </div>
                        <div id="make_order_app_result_vision" v-if="checkVisionHomeHasTariff || checkVisionParkingHasTariff">
                            <div class="title">
                                <div class="icon"></div>
                                <div class="text">Видеонаблюдение:</div>
                            </div>
                            <div class="content">
                                <template v-if="checkVisionHomeHasTariff">
                                    <div class="big_title">В квартире</div>
                                    <div class="items">
                                        <div class="item" v-if="dataStep4.visionHomeTariff !== null">
                                            <span class="t">«<span class="black">{{getVisionHomeTariff(dataStep4.visionHomeTariff).title}}</span>»</span>
                                            <span class="d">—</span>
                                            <span class="p">{{coolNumber(getVisionHomeTariff(dataStep4.visionHomeTariff).price.monthly_price)}} <span>р/мес</span></span>
                                        </div>
                                    </div>
                                    
                                    <div class="title">Оборудование:</div>
                                    <template v-if="currentAddressVisionHomeDevices.length > 0">
                                        <div class="items">
                                        <div v-for="i in currentAddressVisionHomeDevices" class="item smt">
                                            <span class="t"><span class="light gray">Видеокамера</span> «<span class="black">{{getVisionHomeDevice(i).title}}</span>»</span>
                                            <span class="d">—</span>
                                            <span class="p"><i>{{dynamicListVisionHomeDevices[i]}} шт.,</i> {{coolNumber(getVisionHomeDevice(i).price.connection_price * dynamicListVisionHomeDevices[i])}} <span>р</span></span>
                                        </div>
                                    </div>
                                    </template>
                                    <template v-else>
                                    <div class="no_item">не выбрано</div>
                                    </template>
                                </template>
                                <template v-if="checkVisionParkingHasTariff">
                                    <div class="big_title">На паркинге</div>
                                    <div class="items">
                                        <div class="item">
                                            <span class="t">Подключение PoE-порта</span>
                                            <span class="d">—</span>
                                            <span class="p">{{coolNumber(getVisionParkingPoe().price.connection_price)}} <span>р</span></span>
                                        </div>
                                        <div class="item">
                                            <span class="t">Абонентская плата за PoE-порт</span>
                                            <span class="d">—</span>
                                            <span class="p">{{coolNumber(getVisionParkingPoe().price.monthly_price)}} <span>р/мес</span></span>
                                        </div>
                                        <div class="item" v-if="dataStep4.visionParkingTariff !== null">
                                            <span class="t">«<span class="black">{{getVisionParkingTariff(dataStep4.visionParkingTariff).title}}</span>»</span>
                                            <span class="d">—</span>
                                            <span class="p">{{coolNumber(getVisionParkingTariff(dataStep4.visionParkingTariff).price.monthly_price)}} <span>р/мес</span></span>
                                        </div>
                                    </div>
                                    <div class="title">Оборудование:</div>
                                    <template v-if="currentAddressVisionParkingDevices.length > 0">
                                        <div class="items">
                                        <div v-for="i in currentAddressVisionParkingDevices" class="item smt">
                                            <span class="t"><span class="light gray">Видеокамера</span> «<span class="black">{{getVisionParkingDevice(i).title}}</span>»</span>
                                            <span class="d">—</span>
                                            <span class="p"><i>{{dynamicListVisionParkingDevices[i]}} шт.,</i> {{coolNumber(getVisionParkingDevice(i).price.connection_price * dynamicListVisionParkingDevices[i])}} <span>р</span></span>
                                        </div>
                                    </div>
                                    </template>
                                    <template v-else>
                                    <div class="no_item">не выбрано</div>
                                    </template>
                                </template>
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
                    <a href="javascript://" class="button_3 pbig" @click="goNextStep"><template v-if="nowStep > 4 && dataStep5.openedTap == 'self'">Перейти к оплате</template><template v-else-if="nowStep > 4 && dataStep5.openedTap == 'phone'">Оформить заявку</template><template v-else>Далее</template></a>
                </div>
                <div class="cancel" v-if="showButtonCancel">
                    <a href="javascript://" @click="cancelThisStep">Пропустить <span>этот шаг</span></a>
                </div>
            </div>
        </div>
    </div>
    

</div>
</template>

<script>

import Cookies from 'js-cookie';

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
                openedTheathers : true,
                openedAddons : true,
            },
            dataStep4 : {
                canceled : false,
                openedPhone : true,
                openedVision : true,
                openedVisionTap : 'home',
                phoneTariff : null,
                openedPhoneDectDevs : false,
                openedPhoneTableDevs : false,
                visionHomeTariff : null,
                visionParkingTariff : null,
            },
            dataStep5 : {
                openedTap : 'self',
                input_building : '',
                input_apartment : '',
                input_fio : '',
                input_passport_series : '',
                input_passport_num : '',
                input_passport_who : '',
                input_passport_address : '',
                input_bday : undefined,
                input_bmonth : undefined,
                input_byear : undefined,
                input_email : '',
                input_phone : '',
                input_comment : '',
                openedConnectTime : true,
                input_cday : undefined,
                input_cmonth : undefined,
                input_cyear : undefined,
                input_chour : 0,
                checkbox_already_client : false,
                checkbox_from_other_operator : false,
                checkbox_policy : false
            },
            dynamicListTvTheathers : {},
            dynamicListTvAddons : {},
            dynamicListPhoneDectDevices : {},
            dynamicListPhoneTableDevices : {},
            dynamicListVisionHomeDevices : {},
            dynamicListVisionParkingDevices : {},
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
            this.currentDataAddress.internet_plans.forEach((k) => {
                if (typeof this.formData.products.internet_basic[k] != 'undefined')
                {
                    let e = this.formData.products.internet_basic[k];
                    result.push({value : e.id, text : `${e.speed} Мбит/с`, speed : e.speed});
                }
            });

            return result;
        },
        addressesInternetAdds()
        {
            return this.formData.products.additional_internet;
        },
        addressesTvPlansList()
        {
            let result = [];
            this.currentDataAddress.tv_plans.forEach((k) => {
                if (typeof this.formData.products.tv_basic[k] != 'undefined')
                {
                    let e = this.formData.products.tv_basic[k];
                    result.push({value : e.id, text : e.scale_title});
                }
            });
            return result;
        },
        addressesTvTheatresList()
        {
            let result = [];
            this.currentDataAddress.tv_plans.forEach((k) => {
                if (typeof this.formData.products.tv_theatres[k] != 'undefined')
                {
                    result.push(this.formData.products.tv_theatres[k]);
                }
            });
            return result;
        },
        addressesTvAddonsList()
        {
            let result = [];
            this.currentDataAddress.tv_plans.forEach((k) => {
                if (typeof this.formData.products.tv_addons[k] != 'undefined')
                {
                    result.push(this.formData.products.tv_addons[k]);
                }
            });
            return result;
        },
        addressesPhoneTariffsList()
        {
            let result = [];
            for (let i in this.formData.products.additional_phone)
            {
                result.push(this.formData.products.additional_phone[i]);
            }
            return result;
        },
        addressesPhoneDectDevicesList()
        {
            let result = [];
            for (let i in this.formData.products.devices_additional_phone_dect)
            {
                result.push(this.formData.products.devices_additional_phone_dect[i]);
            }
            return result;
        },
        addressesPhoneTableDevicesList()
        {
            let result = [];
            for (let i in this.formData.products.devices_additional_phone_table)
            {
                result.push(this.formData.products.devices_additional_phone_table[i]);
            }
            return result;
        },
        addressesVisionHomeTariffsList()
        {
            let result = [];
            for (let i in this.formData.products.additional_vision_home)
            {
                result.push(this.formData.products.additional_vision_home[i]);
            }
            return result;
        },
        addressesVisionHomeDevicesList()
        {
            let result = [];
            for (let i in this.formData.products.devices_additional_vision_home)
            {
                result.push(this.formData.products.devices_additional_vision_home[i]);
            }
            return result;
        },
        addressesVisionParkingTariffsList()
        {
            let result = [];
            for (let i in this.formData.products.additional_vision_parking)
            {
                result.push(this.formData.products.additional_vision_parking[i]);
            }
            return result;
        },
        addressesVisionParkingDevicesList()
        {
            let result = [];
            for (let i in this.formData.products.devices_additional_vision_parking)
            {
                result.push(this.formData.products.devices_additional_vision_parking[i]);
            }
            return result;
        },

        //Генераторы
        currentDataAddress()
        {
            if (typeof this.formData == 'undefined')
            {
                this.formData = window.order_form_data;
            }

            let i = this.formData.addresses.findIndex((e) => {
                return e.id == this.dataAddressId;
            });
            return this.formData.addresses[(i == -1 ? 0 : i)]
        },
        currentAddressConType()
        {
            return this.currentDataAddress.connection_type.id == 1 || this.currentDataAddress.connection_type.id == 3 ? 'gpon' : 'ethernet';
        },
        currentAddressTvTheathers()
        {
            let result = [];
            if (this.dataStep3.tvPlan !== null && this.formData.products.tv_basic[this.dataStep3.tvPlan].include_theatres == false /*&& this.dataStep3.openedTheathers*/)
            {
                for (let i in this.addressesTvTheatresList)
                {
                    if (this.dynamicListTvTheathers[this.addressesTvTheatresList[i].id])
                    {
                        result.push(this.addressesTvTheatresList[i].id);
                    }
                }
            }
            return result;
        },
        currentAddressTvAddons()
        {
            let result = [];
            //if (this.currentDataAddress !== null)
            {
                for (let i in this.addressesTvAddonsList)
                {
                    if (this.dynamicListTvAddons[this.addressesTvAddonsList[i].id])
                    {
                        result.push(this.addressesTvAddonsList[i].id);
                    }
                }
            }
            return result;
        },
        currentAddressPhoneDectDevices()
        {
            let result = [];
            if (this.checkPhoneHasTariff /*&& this.dataStep4.openedPhoneDectDevs*/)
            {
                for (let i in this.dynamicListPhoneDectDevices)
                {
                    if (this.dynamicListPhoneDectDevices[i] > 0)
                    {
                        result.push(i);
                    }
                }
            }
            return result;
        },
        currentAddressPhoneTableDevices()
        {
            let result = [];
            if (this.checkPhoneHasTariff /*&& this.dataStep4.openedPhoneTableDevs*/)
            {
                for (let i in this.dynamicListPhoneTableDevices)
                {
                    if (this.dynamicListPhoneTableDevices[i] > 0)
                    {
                        result.push(i);
                    }
                }
            }
            return result;
        },
        currentAddressVisionHomeDevices()
        {
            let result = [];
            if (this.checkVisionHomeHasTariff)
            {
                for (let i in this.dynamicListVisionHomeDevices)
                {
                    if (this.dynamicListVisionHomeDevices[i] > 0)
                    {
                        result.push(i);
                    }
                }
            }
            return result;
        },
        currentAddressVisionParkingDevices()
        {
            let result = [];
            if (this.checkVisionParkingHasTariff)
            {
                for (let i in this.dynamicListVisionParkingDevices)
                {
                    if (this.dynamicListVisionParkingDevices[i] > 0)
                    {
                        result.push(i);
                    }
                }
            }
            return result;
        },
        currentAddressAvailableBuildingsList()
        {
            let result = [];
            if (this.currentDataAddress.need_building_input && this.currentDataAddress.available_buildings.length > 0)
            {
                result.push({value : '', text : '------'});
                for (let i of this.currentDataAddress.available_buildings)
                {
                    result.push({value : i, text : i});
                }
            }
            return result;
        },

        //Чекеры
        checkPhoneHasTariff()
        {
            if (this.nowStep >= 4 && this.dataStep4.canceled == false /*&& this.dataStep4.openedPhone*/ && this.dataStep4.phoneTariff !== null)
            {
                return true;
            }
            return false;
        },
        checkVisionHomeHasTariff()
        {
            if (this.nowStep >= 4 && this.dataStep4.canceled == false /*&& this.dataStep4.openedVision*/ && this.dataStep4.visionHomeTariff !== null)
            {
                return true;
            }
            return false;
        },
        checkVisionParkingHasTariff()
        {
            if (this.nowStep >= 4 && this.dataStep4.canceled == false /*&& this.dataStep4.openedVision*/ && this.dataStep4.visionParkingTariff !== null)
            {
                return true;
            }
            return false;
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
            if (this.nowStep > 1 && this.nowStep < 5)
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

            if (this.nowStep >= 3 && this.dataStep3.canceled == false)
            {
                r++;
            }

            if (this.checkPhoneHasTariff)
            {
                r++;
            }

            if (this.checkVisionHomeHasTariff)
            {
                r++;
            }

            if (this.checkVisionParkingHasTariff)
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

            if (this.nowStep >= 3 && this.dataStep3.canceled == false)
            {
                if (this.dataStep3.tvPlan !== null)
                {
                    r += this.getTvPlan(this.dataStep3.tvPlan).price.monthly_price;
                }

                this.currentAddressTvTheathers.forEach((e) => {
                    r += this.getTvTheather(e).price.monthly_price;
                });

                this.currentAddressTvAddons.forEach((e) => {
                    r += this.getTvAddon(e).price.monthly_price;
                });
            }

            if (this.checkPhoneHasTariff)
            {
                r += this.getPhoneTariff(this.dataStep4.phoneTariff).price.monthly_price;
            }

            if (this.checkVisionHomeHasTariff)
            {
                r += this.getVisionHomeTariff(this.dataStep4.visionHomeTariff).price.monthly_price;
            }

            if (this.checkVisionParkingHasTariff)
            {
                r += this.getVisionParkingTariff(this.dataStep4.visionParkingTariff).price.monthly_price;
                r += this.getVisionParkingPoe().price.monthly_price;
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

            return Math.round(r * (1 - s/100));
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

            if (this.nowStep >= 2 && this.dataStep3.canceled == false)
            {
                if (this.dataStep3.tvBox !== null)
                {
                    r += this.getTvBox().price.connection_price;
                }
            }

            if (this.checkPhoneHasTariff)
            {
                this.currentAddressPhoneDectDevices.forEach((i) => {
                    r += this.getPhoneDectDevice(i).price.connection_price * this.dynamicListPhoneDectDevices[i];
                });

                this.currentAddressPhoneTableDevices.forEach((i) => {
                    r += this.getPhoneTableDevice(i).price.connection_price * this.dynamicListPhoneTableDevices[i];
                });
            }

            if (this.checkVisionHomeHasTariff)
            {
                this.currentAddressVisionHomeDevices.forEach((i) => {
                    r += this.getVisionHomeDevice(i).price.connection_price * this.dynamicListVisionHomeDevices[i];
                });
            }

            if (this.checkVisionParkingHasTariff)
            {
                this.currentAddressVisionParkingDevices.forEach((i) => {
                    r += this.getVisionParkingDevice(i).price.connection_price * this.dynamicListVisionParkingDevices[i];
                });

                r += this.getVisionParkingPoe().price.connection_price;
            }

            return r;
        },

        fullStartPayment()
        {
            return this.monthlyPaymentWithSale + this.startPayment;
        },

        //Готовые списки

        selectDaysList()
        {
            let maxd = parseInt(new Date(this.dataStep5.input_byear, this.dataStep5.input_bmonth, 0).getDate());
            let r = [];
            for (let i = 1; i <= maxd; i++)
            {
                r.push({value : i, text : i});
            }
            return r;
        },
        selectConnectDaysList()
        {
            let maxd = parseInt(new Date(this.dataStep5.input_cyear, this.dataStep5.input_cmonth, 0).getDate());
            let r = [];
            for (let i = 1; i <= maxd; i++)
            {
                r.push({value : i, text : i});
            }
            return r;
        },
        selectMonthsList()
        {
            return [
                {value : 1, text : 'Январь'},
                {value : 2, text : 'Февраль'},
                {value : 3, text : 'Март'},
                {value : 4, text : 'Апрель'},
                {value : 5, text : 'Май'},
                {value : 6, text : 'Июнь'},
                {value : 7, text : 'Июль'},
                {value : 8, text : 'Август'},
                {value : 9, text : 'Сентябрь'},
                {value : 10, text : 'Октябрь'},
                {value : 11, text : 'Ноябрь'},
                {value : 12, text : 'Декабрь'},
            ];
        },
        selectYearsList()
        {
            let r = [];
            let d = new Date();
            var s = parseInt(d.getFullYear()) - 14;
            for (let i = s; i >= (parseInt(d.getFullYear()) - 100); i--)
            {
                r.push({value : i, text : i});
            }
            return r;
        },
        selectConnectYearsList()
        {
            let r = [];
            let d = new Date();
            var s = parseInt(d.getFullYear()) + 1;
            for (let i = s; i >= (s - 1); i--)
            {
                r.push({value : i, text : i});
            }
            return r;
        },
        /*
        selectHoursList()
        {
            let r = [];
            for (let i = 7; i <= 23; i++)
            {
                r.push({value : i, text : (i < 10 ? '0' + i : i)});
            }
            return r;
        },
        */
       selectCHoursList()
       {
           return [
               {value : 0, text : 'с 7:00 до 11:00', start : 7, end : 11},
               {value : 1, text : 'с 11:00 до 15:00', start : 11, end : 15},
               {value : 2, text : 'с 15:00 до 19:00', start : 15, end : 19},
               {value : 3, text : 'с 19:00 до 23:00', start : 19, end : 23},
           ];
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
        getTvAddon(d)
        {
            return this.formData.products.tv_addons[d];
        },
        getTvTheather(d)
        {
            return this.formData.products.tv_theatres[d];
        },
        getPhoneTariff(d)
        {
            return this.formData.products.additional_phone[d];
        },
        getPhoneDectDevice(d)
        {
            return this.formData.products.devices_additional_phone_dect[d];
        },
        getPhoneTableDevice(d)
        {
            return this.formData.products.devices_additional_phone_table[d];
        },
        getVisionHomeTariff(d)
        {
            return this.formData.products.additional_vision_home[d];
        },
        getVisionHomeDevice(d)
        {
            return this.formData.products.devices_additional_vision_home[d];
        },
        getVisionParkingTariff(d)
        {
            return this.formData.products.additional_vision_parking[d];
        },
        getVisionParkingDevice(d)
        {
            return this.formData.products.devices_additional_vision_parking[d];
        },
        getVisionParkingPoe()
        {
            return this.formData.products.additional_vision_poe[58];
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
            if (this.nowStep == 2 && this.dataStep2.canceled == false)
            {
                if (this.currentAddressConType == 'gpon' && this.dataStep2.optDevice === null)
                {
                    this.showErrors(['В вашем жилом комплексе подключение к интернету осуществляется по технологии GPON. Для продолжения необходимо выбрать подходящий PON-модем.']);
                    return false;
                }
            }

            if (this.nowStep == 4 && this.dataStep4.canceled == false)
            {
                if (this.dataStep4.openedPhone && this.dataStep4.phoneTariff === null)
                {
                    let c = 0;
                    if (this.dataStep4.openedPhoneDectDevs)
                    {
                        for (let i in this.dynamicListPhoneDectDevices)
                        {
                            if (this.dynamicListPhoneDectDevices[i] > 0)
                            {
                                c++;
                                break;
                            }
                        }
                    }
                    if (this.dataStep4.openedPhoneTableDevs)
                    {
                        for (let i in this.dynamicListPhoneTableDevices)
                        {
                            if (this.dynamicListPhoneTableDevices[i] > 0)
                            {
                                c++;
                                break;
                            }
                        }
                    }
                    if (c > 0)
                    {
                        this.showErrors(['Вы выбрали оборудование для телефонии, но не выбрали тариф. Чтобы продолжить необходимо выбрать тариф или отменить выбор оборудования.']);
                        return false;
                    }
                }

                if (this.dataStep4.openedVision && this.dataStep4.visionHomeTariff === null)
                {
                    let c = 0;
                    for (let i in this.dynamicListVisionHomeDevices)
                    {
                        if (this.dynamicListVisionHomeDevices[i] > 0)
                        {
                            c++;
                            break;
                        }
                    }
                    if (c > 0)
                    {
                        this.showErrors(['Вы выбрали оборудование для видеонаблюдения в квартире, но не выбрали тариф. Чтобы продолжить необходимо выбрать тариф или отменить выбор оборудования.']);
                        return false;
                    }
                }

                if (this.dataStep4.openedVision && this.dataStep4.visionParkingTariff === null)
                {
                    let c = 0;
                    for (let i in this.dynamicListVisionParkingDevices)
                    {
                        if (this.dynamicListVisionParkingDevices[i] > 0)
                        {
                            c++;
                            break;
                        }
                    }
                    if (c > 0)
                    {
                        this.showErrors(['Вы выбрали оборудование для видеонаблюдения на паркинге, но не выбрали тариф. Чтобы продолжить необходимо выбрать тариф или отменить выбор оборудования.']);
                        return false;
                    }
                }
            }

            if (this.nowStep == 5)
            {
                let fields;

                if (this.dataStep5.openedTap == 'self')
                {
                    fields = this.contact_fields_self;
                }
                else
                {
                    fields = this.contact_fields_not_self;
                }

                for (let e in fields) {
                    if (!(fields[e] == 'input_comment' || (fields[e] == 'input_building' && this.currentDataAddress.need_building_input == false)))
                    {
                        if (this.dataStep5[fields[e]].length < 1)
                        {
                            this.showErrors(['Заполните все обязательные поля']);
                            return false;
                        }
                    }
                }

                if (this.dataStep5.openedTap == 'self' && eApi.getFuncs().checkIsEmail(this.dataStep5.input_email) == false)
                {
                    this.showErrors(['Введите корректный e-mail']);
                    return false;
                }

                if (this.dataStep5.checkbox_policy == false)
                {
                    this.showErrors(['Требуется согласие с политикой конфиденциальности']);
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
                else if (this.nowStep >= 5)
                {
                    eApi.getInterface().showCaptcha('Для отправки Вашей заявки необходимо подтверждение', (token) => {
                        this.sendDataToServer(token);
                    });
                }
            }
        },
        sendDataToServer(token)
        {
            let data = {};
            data['g-recaptcha-response'] = token;

            data.addressId = this.currentDataAddress.id;

            data.internet = this.dataStep2.canceled ? null : {
                internetPlan : this.dataStep2.internetPlan,
                staticIp : this.dataStep2.staticIp,
                optDevice : (this.dataStep2.optDevice !== null && this.currentAddressConType == 'gpon' ? this.dataStep2.optDevice : null),
                wifiDevice :this.dataStep2.wifiDevice,
            };

            data.tv = this.dataStep3.canceled ? null : {
                tvPlan : this.dataStep3.tvPlan,
                tvBox : this.dataStep3.tvBox,
                addons : this.currentAddressTvAddons,
                theathers : this.currentAddressTvTheathers,
            };

            data.phone = {
                phoneTariff : this.checkPhoneHasTariff ? this.dataStep4.phoneTariff : null,
                dectDevices : [],
                tableDevices : [],
            };

            if (this.checkPhoneHasTariff)
            {
                this.currentAddressPhoneDectDevices.forEach((i) => {
                    data.phone.dectDevices.push({id : i, count : this.dynamicListPhoneDectDevices[i]});
                });

                this.currentAddressPhoneTableDevices.forEach((i) => {
                    data.phone.tableDevices.push({id : i, count : this.dynamicListPhoneTableDevices[i]});
                });
            }

            data.vision = {
                visionHomeTariff : this.checkVisionHomeHasTariff ? this.dataStep4.visionHomeTariff : null,
                devicesHome : [],
                visionParkingTariff : this.checkVisionParkingHasTariff ? this.dataStep4.visionParkingTariff : null,
                devicesParking : [],
                poePort : this.checkVisionParkingHasTariff && this.dataStep4.visionParkingTariff !== null ? true : false,
            };

            if (this.checkVisionHomeHasTariff)
            {
                this.currentAddressVisionHomeDevices.forEach((i) => {
                    data.vision.devicesHome.push({id : i, count : this.dynamicListVisionHomeDevices[i]});
                });
            }

            if (this.checkVisionParkingHasTariff)
            {
                this.currentAddressVisionParkingDevices.forEach((i) => {
                    data.vision.devicesParking.push({id : i, count : this.dynamicListVisionParkingDevices[i]});
                });
            }

            data.contact = {
                type : this.dataStep5.openedTap,
                use_connect_time : this.dataStep5.openedConnectTime,
                checkbox_already_client : this.dataStep5.checkbox_already_client,
                checkbox_from_other_operator : this.dataStep5.checkbox_from_other_operator,
            };

            let fields;
            if (this.dataStep5.openedTap == 'self')
            {
                fields = this.contact_fields_self;
            }
            else
            {
                fields = this.contact_fields_not_self;
            }

            for (let e in fields) {
                data.contact[fields[e]] = this.dataStep5[fields[e]];
            }

            data.contact.input_birthday = `${this.dataStep5.input_bday}.${this.dataStep5.input_bmonth}.${this.dataStep5.input_byear}`;

            if (this.dataStep5.openedConnectTime)
            {
                data.contact.connect_time = {
                    day : this.dataStep5.input_cday,
                    month : this.dataStep5.input_cmonth,
                    year : this.dataStep5.input_cyear,
                    hour_start : this.selectCHoursList[this.dataStep5.input_chour].start,
                    hour_end : this.selectCHoursList[this.dataStep5.input_chour].end,
                };
            }

            eApi.getFuncs().sendData(window.order_form_callback, data)
            .then((response) => {

                if (response.data.error)
                {
                    eApi.getFuncs().alert('Произошла неизвестная ошибка.');
                }
                else
                {
                    this.clearCookies();

                    if (response.data.should_show_payment)
                    {
                        window.pay(response.data.login, response.data.amount, (typeof data.contact.input_email != 'undefined' ? data.contact.input_email : ''), () => {
                            eApi.getFuncs().alert('Ваша заявка принята и успешно оплачена. Мы свяжемся с Вами в ближайшее время.', {
                                box_destroy : function() {
                                    window.location.href = '/';
                                }
                            });
                        });
                    }
                    else
                    {
                        eApi.getFuncs().alert('Ваша заявка принята. Мы свяжемся с Вами в ближайшее время.', {
                            box_destroy : function() {
                                window.location.href = '/';
                            }
                        });
                    }
                }
            })
            .catch(function() {
                eApi.getFuncs().alert('Произошла ошибка в ходе отправки данных.');
            });
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
        cancelThisStep()
        {
            if (this.nowStep == 2)
            {
                this.dataStep2.canceled = true;
            }
            else if (this.nowStep == 3)
            {
                this.dataStep3.canceled = true;
            }
            else if (this.nowStep == 4)
            {
                this.dataStep4.canceled = true;
            }

            this.goNextStep();
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

                tpl += `<div class="list_link"><a href="javascript://" onclick="return projectApi.getUserInterface().showTvPlanChannelsList('${e.id}');">Список каналов</a></div></div></div>`;

                return tpl;
            }
           
            return '';
        },

        openAddonsInfo(id)
        {
            projectApi.getUserInterface().showTvPlanChannelsList(id, 'tvaddon');
        },

        openTheatherInfo(id)
        {
            let item = this.getTvTheather(id);
            projectApi.getUserInterface().showTvTheaherInfo(id, item.title, item.description, item.image.url.reference);
        },

        //Сохранение данных

        saveDataToCookie()
        {
            clearTimeout(this.cookie_save_timeout);
            this.cookie_save_timeout = setTimeout(() => {

                let data = {
                    nowStep : this.nowStep,
                    dataAddressId : this.dataAddressId,
                    dataStep2 : this.dataStep2,
                    dataStep3 : this.dataStep3,
                    addressTvTheathers : this.currentAddressTvTheathers,
                    addressTvAddons : this.currentAddressTvAddons,
                    dataStep4 : this.dataStep4,
                    addressPhoneDectDevices : this.dynamicListPhoneDectDevices,
                    addressPhoneTableDevices : this.dynamicListPhoneTableDevices,
                    addressVisionHomeDevices : this.dynamicListVisionHomeDevices,
                    addressVisionParkingDevices : this.dynamicListVisionParkingDevices,
                    dataStep5 : this.dataStep5,
                };

                for (let i in this.cookies_vars)
                {
                    Cookies.set(i, (this.cookies_vars[i].t == 'json' ? JSON.stringify(data[this.cookies_vars[i].k]) : data[this.cookies_vars[i].k]), { expires: 7, path: window.location.pathname + window.location.search });
                }
                
            }, 500);
        },
        clearCookies()
        {
            for (let i in this.cookies_vars)
            {
                Cookies.remove(i, { path: window.location.pathname + window.location.search });
            }
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
                this.dataStep2.canceled = false;

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

                let optc = 0;
                let optc_id = null;
                for (let d of this.currentDataAddress.connection_type.devices)
                {
                    if (this.formData.products.devices_internet_ont[d] !== undefined)
                    {
                        optc++;
                        optc_id = d;
                    }
                }

                if (this.currentAddressConType == 'gpon' && optc == 1)
                {
                    this.dataStep2.optDevice = optc_id;
                }

            }

            if (v == 3)
            {
                this.dataStep3.canceled = false;
            }

            if (v == 4)
            {
                this.dataStep4.canceled = false;
            }

            if (v == 5)
            {
                this.$nextTick(() => {
                    this.inputmask_phone.mask(this.$refs['step_5_phone_1']);
                    this.inputmask_phone.mask(this.$refs['step_5_phone_2']);
                });
            }

            this.saveDataToCookie();
        },

        dataAddressId : function(v) {
            this.saveDataToCookie();
        },
        dataStep2 : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dataStep3 : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        currentAddressTvTheathers : function(v) {
            this.saveDataToCookie();
        },
        currentAddressTvAddons: function(v) {
            this.saveDataToCookie();
        },
        dataStep4 : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dynamicListPhoneDectDevices : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dynamicListPhoneTableDevices : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dynamicListVisionHomeDevices : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dynamicListVisionParkingDevices : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
        dataStep5 : {
            handler(v)
            {
                this.saveDataToCookie();
            },
            deep: true
        },
    },
    created() {

        this.formData = window.order_form_data;

        this.inputmask_phone = new Inputmask("+7 (999) 999-99-99", {clearIncomplete: true, onincomplete : () => { this.dataStep5.input_phone = ''; }});

        
        this.contact_fields_self = ['input_building', 'input_apartment', 'input_fio', 'input_passport_series', 'input_passport_num', 'input_passport_who', 'input_passport_address', 
        'input_email', 'input_phone', 'input_comment'];

        this.contact_fields_not_self = ['input_fio', 'input_phone', 'input_comment'];

        this.cookies_vars = {
            form_nowStep : {
                k : 'nowStep',
                t : 'int'
            },
            form_dataAddressId : {
                k : 'dataAddressId',
                t : 'int'
            },
            form_dataStep2 : {
                k : 'dataStep2',
                t : 'json'
            },
            form_dataStep3 : {
                k : 'dataStep3',
                t : 'json'
            },
            form_addressTvTheathers : {
                k : 'addressTvTheathers',
                t : 'json'
            },
            form_addressTvAddons : {
                k : 'addressTvAddons',
                t : 'json'
            },
            form_dataStep4 : {
                k : 'dataStep4',
                t : 'json'
            },
            form_addressPhoneDectDevices : {
                k : 'addressPhoneDectDevices',
                t : 'json'
            },
            form_addressPhoneTableDevices : {
                k : 'addressPhoneTableDevices',
                t : 'json'
            },
            form_addressVisionHomeDevices : {
                k : 'addressVisionHomeDevices',
                t : 'json'
            },
            form_addressVisionParkingDevices : {
                k : 'addressVisionParkingDevices',
                t : 'json'
            },
            form_dataStep5 : {
                k : 'dataStep5',
                t : 'json'
            },
        };

        this.cookie_save_timeout = 0;

        let tv_theathers = {};
        for (let i in this.formData.products.tv_theatres)
        {
           tv_theathers[i] = false; 
        }
        this.dynamicListTvTheathers = tv_theathers;

        let tv_addons = {};
        for (let i in this.formData.products.tv_addons)
        {
           tv_addons[i] = false; 
        }
        this.dynamicListTvAddons = tv_addons;

        let phone_dect_devices = {};
        for (let i in this.formData.products.devices_additional_phone_dect)
        {
           phone_dect_devices[i] = 0; 
        }
        this.dynamicListPhoneDectDevices = phone_dect_devices;

        let phone_table_devices = {};
        for (let i in this.formData.products.devices_additional_phone_table)
        {
           phone_table_devices[i] = 0; 
        }
        this.dynamicListPhoneTableDevices = phone_table_devices;

        let vision_home_devices = {};
        for (let i in this.formData.products.devices_additional_vision_home)
        {
           vision_home_devices[i] = 0; 
        }
        this.dynamicListVisionHomeDevices = vision_home_devices;

        let vision_parking_devices = {};
        for (let i in this.formData.products.devices_additional_vision_parking)
        {
           vision_parking_devices[i] = 0; 
        }
        this.dynamicListVisionParkingDevices = vision_parking_devices;



        var date = new Date();
        date.setDate(date.getDate() + 1);
        let dataStep5 = this.dataStep5;
        dataStep5.input_bday = 1;
        dataStep5.input_bmonth = 1;
        dataStep5.input_byear = parseInt(date.getFullYear()) - 14;
        dataStep5.input_cday = date.getDate();
        dataStep5.input_cmonth = date.getMonth() + 1;
        dataStep5.input_cyear = date.getFullYear();
        this.dataStep5 = dataStep5;

        //Получение куки
        this.cookie_data = {};
        for (let i in this.cookies_vars)
        {
            if (Cookies.get(i) !== undefined && Cookies.get(i).length > 0)
            {
                if (this.cookies_vars[i].t == 'int')
                {
                    this.cookie_data[this.cookies_vars[i].k] = parseInt(Cookies.get(i));
                }
                else if (this.cookies_vars[i].t == 'json')
                {
                    this.cookie_data[this.cookies_vars[i].k] = JSON.parse(Cookies.get(i));
                }
            }
        }

    },
    beforeUpdate() {
        
    },
    mounted() {
        //Обработка куки
        let max_step = 1;
        let f;

        if (this.cookie_data.dataAddressId !== undefined)
        {
            f = false;
            this.formData.addresses.forEach(e => {
                if (e.id == this.cookie_data.dataAddressId)
                {
                    this.dataAddressId = this.cookie_data.dataAddressId;
                    f = true;
                    return false;
                }
            });

            if (f)
            {
                max_step = 2;
            }
        }

        if (max_step == 2)
        {
            if (this.cookie_data.dataStep2 !== undefined)
            {
                this.dataStep2 = this.cookie_data.dataStep2;
                max_step = 3;
            }
        }

        if (max_step == 3)
        {
            if (this.cookie_data.dataStep3 !== undefined)
            {
                this.dataStep3 = this.cookie_data.dataStep3;
                max_step = 4;

                if (this.cookie_data.addressTvTheathers !== undefined)
                {
                    this.cookie_data.addressTvTheathers.forEach((i) => {
                        this.dynamicListTvTheathers[i] = true;
                    });
                }

                if (this.cookie_data.addressTvAddons !== undefined)
                {
                    this.cookie_data.addressTvAddons.forEach((i) => {
                        this.dynamicListTvAddons[i] = true;
                    });
                }
            }
        }

        if (max_step == 4)
        {
            if (this.cookie_data.dataStep4 !== undefined)
            {
                this.dataStep4 = this.cookie_data.dataStep4;
                max_step = 5;

                if (this.cookie_data.addressPhoneDectDevices !== undefined)
                {
                    for (let i in this.cookie_data.addressPhoneDectDevices)
                    {
                        let c = parseInt(this.cookie_data.addressPhoneDectDevices[i]);
                        if (c > 0 && typeof this.dynamicListPhoneDectDevices[i] != 'undefined')
                        {
                            this.dynamicListPhoneDectDevices[i] = c;
                        }
                    }
                }

                if (this.cookie_data.addressPhoneTableDevices !== undefined)
                {
                    for (let i in this.cookie_data.addressPhoneTableDevices)
                    {
                        let c = parseInt(this.cookie_data.addressPhoneTableDevices[i]);
                        if (c > 0 && typeof this.dynamicListPhoneTableDevices[i] != 'undefined')
                        {
                            this.dynamicListPhoneTableDevices[i] = c;
                        }
                    }
                }

                if (this.cookie_data.addressVisionHomeDevices !== undefined)
                {
                    for (let i in this.cookie_data.addressVisionHomeDevices)
                    {
                        let c = parseInt(this.cookie_data.addressVisionHomeDevices[i]);
                        if (c > 0 && typeof this.dynamicListVisionHomeDevices[i] != 'undefined')
                        {
                            this.dynamicListVisionHomeDevices[i] = c;
                        }
                    }
                }

                if (this.cookie_data.addressVisionParkingDevices !== undefined)
                {
                    for (let i in this.cookie_data.addressVisionParkingDevices)
                    {
                        let c = parseInt(this.cookie_data.addressVisionParkingDevices[i]);
                        if (c > 0 && typeof this.dynamicListVisionParkingDevices[i] != 'undefined')
                        {
                            this.dynamicListVisionParkingDevices[i] = c;
                        }
                    }
                }
                
            }
        }

        if (max_step == 5)
        {
            if (this.cookie_data.dataStep5 !== undefined)
            {
                this.dataStep5 = this.cookie_data.dataStep5;

            }
        }

        if (this.cookie_data.nowStep !== undefined)    
        {
            this.nowStep = this.cookie_data.nowStep > max_step ? max_step : this.cookie_data.nowStep;
        }


    },
    beforeDestroy() {
        
    },
}
</script>

<style>
</style>
