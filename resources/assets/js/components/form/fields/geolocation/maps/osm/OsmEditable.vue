<template>
    <LMap
        :bounds="transformedBounds"
        :center="center"
        :max-bounds="transformedMaxBounds"
        :zoom="zoom"
        @click="handleMapClicked"
    >
        <LTileLayer :url="tilesUrl"/>
        <template v-if="hasMarker">
            <LMarker :lat-lng="markerPosition" @dragend="handleMarkerDragEnd" draggable/>
        </template>
    </LMap>
</template>

<script>
    import {LMap, LMarker, LTileLayer} from 'vue2-leaflet';
    import {toLatLngBounds} from "./util";

    export default {
        name: 'YggOsmEditable',
        components: {
            LMap,
            LMarker,
            LTileLayer,
        },
        props: {
            markerPosition: Object,
            center: Object,
            zoom: Number,
            bounds: Array,
            maxBounds: Array,
            tilesUrl: {
                type: String,
                default: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
            },
        },
        computed: {
            hasMarker() {
                return !!this.markerPosition;
            },
            transformedBounds() {
                return toLatLngBounds(this.bounds);
            },
            transformedMaxBounds() {
                return toLatLngBounds(this.maxBounds);
            }
        },
        methods: {
            handleMapClicked(e) {
                this.$emit('change', e.latlng);
            },
            handleMarkerDragEnd(e) {
                this.$emit('change', e.target.getLatLng());
            },
        }
    }
</script>
