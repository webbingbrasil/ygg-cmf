<template>
    <div class="YggGeolocation">
        <template v-if="isLoading">
            {{ l('form.geolocation.loading') }}
        </template>
        <template v-else-if="isEmpty">
            <YggButton @click="handleShowModalButtonClicked" class="w-100" outline>
                {{ l('form.geolocation.browse_button') }}
            </YggButton>
        </template>
        <template v-else>
            <YggCard :has-close="!readOnly" @close-click="handleRemoveButtonClicked"
                     class="YggModule--closeable"
                     light
            >
                <div class="row">
                    <div class="col-7">
                        <component
                            :center="value"
                            :class="mapClasses"
                            :is="mapComponent"
                            :marker-position="value"
                            :max-bounds="maxBounds"
                            :tiles-url="tilesUrl"
                            :zoom="zoomLevel"
                            class="YggGeolocation__map"
                        />
                    </div>
                    <div class="col-5 pl-0">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div>
                                <div>
                                    <small>Latitude : {{ latLngString.lat }}</small>
                                </div>
                                <div>
                                    <small>Longitude : {{ latLngString.lng }}</small>
                                </div>
                            </div>
                            <div>
                                <YggButton :disabled="readOnly" @click="handleRemoveButtonClicked" class="remove-button"
                                           outline small type="danger">
                                    {{ l('form.geolocation.remove_button') }}
                                </YggButton>
                                <YggButton :disabled="readOnly" @click="handleEditButtonClicked" outline small>
                                    {{ l('form.geolocation.edit_button') }}
                                </YggButton>
                            </div>
                        </div>
                    </div>
                </div>
            </YggCard>
        </template>
        <YggModal
            :title="modalTitle"
            :visible.sync="modalVisible"
            @ok="handleModalSubmitted"
            no-close-on-backdrop
        >
            <transition :duration="300">
                <template v-if="modalVisible">
                    <YggGeolocationEdit
                        :center="value || initialPosition"
                        :geocoding="geocoding"
                        :geocoding-options="providerOptions(geocodingProvider)"
                        :geocoding-provider="providerName(geocodingProvider)"
                        :location="value"
                        :maps-options="providerOptions(mapsProvider)"
                        :maps-provider="providerName(mapsProvider)"
                        :max-bounds="maxBounds"
                        :zoom="zoomLevel"
                        @change="handleLocationChanged"
                    />
                </template>
            </transition>
        </YggModal>
    </div>
</template>

<script>
    import {Localization} from '../../../../mixins';
    import {YggButton, YggCard} from "../../../ui";
    import YggModal from '../../../Modal';

    import {getMapByProvider, loadMapProvider} from "./maps";
    import {dd2dms, providerName, providerOptions, tilesUrl, triggerResize} from "./util";

    import YggGeolocationEdit from './GeolocationEdit.vue';


    export default {
        name: 'YggGeolocation',
        mixins: [Localization],

        inject: {
            $tab: {
                default: null
            }
        },

        components: {
            YggGeolocationEdit,
            YggCard,
            YggButton,
            YggModal,
        },

        props: {
            value: Object,
            readOnly: Boolean,
            uniqueIdentifier: String,
            geocoding: Boolean,
            apiKey: String,
            boundaries: Object,
            zoomLevel: {
                type: Number,
                default: 4
            },
            initialPosition: {
                type: Object,
                default: () => ({
                    lat: 46.1445458,
                    lng: -2.4343779
                })
            },
            displayUnit: {
                type: String,
                default: 'DD',
                validator: unit => unit === 'DMS' || unit === 'DD'
            },
            mapsProvider: {
                type: Object,
                default: () => ({
                    name: 'gmaps',
                }),
            },
            geocodingProvider: {
                type: Object,
                default: () => ({
                    name: 'gmaps',
                }),
            },
        },
        data() {
            return {
                ready: false,
                modalVisible: false,
                location: this.value,
            }
        },
        computed: {
            isLoading() {
                return !this.ready;
            },
            isEmpty() {
                return !this.value;
            },
            latLngString() {
                if (this.displayUnit === 'DMS') {
                    return {
                        lat: dd2dms(this.value.lat),
                        lng: dd2dms(this.value.lng, true)
                    }
                } else if (this.displayUnit === 'DD') {
                    return this.value;
                }
            },
            mapComponent() {
                return getMapByProvider(providerName(this.mapsProvider));
            },
            mapClasses() {
                return [
                    `YggGeolocation__map--${providerName(this.mapsProvider)}`,
                ];
            },
            tilesUrl() {
                const mapsOptions = providerOptions(this.mapsProvider);
                return tilesUrl(mapsOptions);
            },
            maxBounds() {
                return this.boundaries
                    ? [this.boundaries.sw, this.boundaries.ne]
                    : null;
            },
            modalTitle() {
                return this.geocoding
                    ? this.l('form.geolocation.modal.title')
                    : this.l('form.geolocation.modal.title-no-geocoding');
            },
        },
        methods: {
            providerName,
            providerOptions,

            handleModalSubmitted() {
                this.$emit('input', this.location);
            },
            handleRemoveButtonClicked() {
                this.$emit('input', null);
            },
            handleShowModalButtonClicked() {
                this.modalVisible = true;
            },
            handleEditButtonClicked() {
                this.modalVisible = true;
            },
            handleLocationChanged(location) {
                this.location = location;
            },
            loadProvider(providerData) {
                const name = providerName(providerData);
                const {apiKey} = providerOptions(providerData);
                return loadMapProvider(name, {
                    apiKey,
                });
            },
            async init() {
                await this.loadProvider(this.mapsProvider);
                if (this.geocodingProvider) {
                    await this.loadProvider(this.geocodingProvider);
                }
                this.ready = true;
            }
        },
        created() {
            this.init();
        },
        mounted() {
            if (this.$tab) {
                this.$tab.$once('active', () => {
                    // force update maps components on tab active
                    triggerResize();
                });
            }
        }
    }
</script>
