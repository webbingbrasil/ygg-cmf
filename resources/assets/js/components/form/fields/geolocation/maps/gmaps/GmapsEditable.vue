<template>
    <GmapMap
        :center="center"
        :options="options"
        :zoom="zoom"
        @click="handleMapClicked"
        ref="map"
    >
        <template v-if="hasMarker">
            <GmapMarker :position="markerPosition" @dragend="handleMarkerDragEnd" draggable/>
        </template>
    </GmapMap>
</template>

<script>
    import {Map, Marker} from 'vue2-google-maps';
    import {createMapOptions, defaultMapOptions, toLatLngBounds} from "./util";

    export default {
        name: 'YggGmapsEditable',

        components: {
            GmapMap: Map,
            GmapMarker: Marker,
        },

        props: {
            markerPosition: Object,
            bounds: Array,
            center: Object,
            zoom: Number,
            maxBounds: Array,
        },

        computed: {
            options() {
                return createMapOptions({
                    ...defaultMapOptions,
                    maxBounds: this.maxBounds,
                    draggableCursor: 'crosshair',
                });
            },
            hasMarker() {
                return !!this.markerPosition;
            }
        },
        watch: {
            bounds(bounds) {
                const latLngBounds = toLatLngBounds(bounds);
                if (latLngBounds) {
                    this.$refs.map.$mapObject.fitBounds(latLngBounds);
                }
            },
        },

        methods: {
            handleMapClicked(e) {
                this.$emit('change', e.latLng.toJSON());
            },
            handleMarkerDragEnd(e) {
                this.$emit('change', e.latLng.toJSON());
            }
        },
    }
</script>
