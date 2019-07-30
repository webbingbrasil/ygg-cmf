<template>
    <div :class="classes" class="YggGeolocationEdit">
        <template v-if="hasGeocoding">
            <div class="mb-2">
                <form @submit.prevent="handleSearchSubmitted">
                    <div class="row no-gutters">
                        <div class="col position-relative">
                            <YggText
                                :placeholder="lSub('geocode_input.placeholder')"
                                :value="search"
                                @input="handleSearchInput"
                                class="YggGeolocationEdit__input"
                            />
                            <template v-if="loading">
                                <YggLoading class="YggGeolocationEdit__loading" inline small visible/>
                            </template>
                        </div>
                        <div class="col-auto pl-2">
                            <YggButton outline>{{ lSub('search_button') }}</YggButton>
                        </div>
                    </div>
                </form>

                <template v-if="message">
                    <small>{{ message }}</small>
                </template>
            </div>
        </template>

        <component
            :bounds="currentBounds"
            :center="center"
            :class="mapClasses"
            :is="editableMapComponent"
            :marker-position="currentLocation"
            :max-bounds="maxBounds"
            :tiles-url="tilesUrl"
            :zoom="zoom"
            @change="handleMarkerPositionChanged"
            class="YggGeolocationEdit__map"
        />
    </div>
</template>

<script>
    import {YggButton, YggLoading} from "../../../ui";
    import YggModal from '../../../Modal';
    import YggText from '../Text';
    import {LocalizationBase} from '../../../../mixins';
    import {geocode, getEditableMapByProvider} from "./maps";
    import {tilesUrl} from "./util";

    export default {
        mixins: [LocalizationBase('form.geolocation.modal')],
        components: {
            YggLoading,
            YggModal,
            YggText,
            YggButton,
        },
        props: {
            location: Object,
            center: Object,
            bounds: Object,
            zoom: Number,
            maxBounds: Array,
            geocoding: Boolean,
            mapsProvider: {
                type: String,
                default: 'gmaps',
            },
            mapsOptions: Object,
            geocodingProvider: {
                type: String,
                default: 'gmaps',
            },
            geocodingOptions: Object,
        },
        data() {
            return {
                loading: false,
                search: null,
                message: null,
                currentLocation: this.location,
                currentBounds: this.bounds,
            }
        },
        computed: {
            editableMapComponent() {
                return getEditableMapByProvider(this.mapsProvider);
            },
            hasGeocoding() {
                return this.geocoding;
            },
            classes() {
                return {
                    'YggGeolocationEdit--loading': this.loading,
                }
            },
            mapClasses() {
                return [
                    `YggGeolocationEdit__map--${this.mapsProvider}`,
                ]
            },
            tilesUrl() {
                return tilesUrl(this.mapsOptions);
            },
        },
        methods: {
            handleSearchInput(search) {
                this.search = search;
            },
            handleMarkerPositionChanged(position) {
                this.currentLocation = position;
                this.message = '';
                this.$emit('change', this.currentLocation);
                if (this.hasGeocoding) {
                    this.loading = true;
                    geocode(this.geocodingProvider, {latLng: position}, this.geocodingOptions)
                        .then(results => {
                            if (results.length > 0) {
                                this.search = results[0].address;
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            },
            handleSearchSubmitted() {
                const address = this.search;
                this.message = '';
                this.loading = true;
                geocode(this.geocodingProvider, {address}, this.geocodingOptions)
                    .then(results => {
                        if (results.length > 0) {
                            this.currentLocation = results[0].location;
                            this.currentBounds = results[0].bounds;
                            this.$emit('change', this.currentLocation);
                        } else {
                            this.message = this.lSub(`geocode_input.message.no_results`).replace(':query', address || '');
                        }
                    })
                    .catch(status => {
                        this.message = `${this.lSub(`geocode_input.message.error`)}${status ? ` (${status})` : ''}`;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
        },
    }
</script>
