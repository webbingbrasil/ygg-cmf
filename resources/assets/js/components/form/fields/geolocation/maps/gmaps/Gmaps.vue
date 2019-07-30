<template>
    <GmapMap
        :center="center"
        :options="options"
        :zoom="zoom"
        ref="map"
    >
        <GmapMarker :position="markerPosition"/>
    </GmapMap>
</template>

<script>
    import {Map, Marker} from 'vue2-google-maps';
    import {createMapOptions, defaultMapOptions} from "./util";

    export default {
        name: 'YggGmaps',

        components: {
            GmapMap: Map,
            GmapMarker: Marker,
        },

        props: {
            markerPosition: Object,
            center: Object,
            zoom: Number,
        },

        computed: {
            options() {
                return createMapOptions({
                    ...defaultMapOptions,
                    maxBounds: this.maxBounds,
                });
            },
        },
    }
</script>
