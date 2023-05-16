<template>
    <l-map
        ref="map"
        style="height: 350px"
        :zoom="zoom"
        :min-zoom="minZoom"
        :max-zoom="maxZoom"
        :center="center"
        :crs="crs"
    >
        <l-image-overlay
            :url="url"
            :bounds="bounds"
        />
    </l-map>
</template>

<script>
import { CRS } from 'leaflet';
import { LMap, LImageOverlay } from 'vue2-leaflet';

export default {
    components: {
        LMap,
        LImageOverlay,
    },
    data() {
        return {
            url: './images/bigroom.png',
            bounds: [[0, 0], [1095, 1899]],
            center: [547, 945],
            minZoom: -1,
            zoom: -1,
            maxZoom: 0,
            crs: CRS.Simple,
        };
    }
};
</script>

<style>
</style>
