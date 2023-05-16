<template>
    <div>
        <l-map
            ref="map"
            style="height: 350px"
            :max-bounds="mapBoundaries"
            :zoom="zoom"
            :min-zoom="minZoom"
            :max-zoom="maxZoom"
            :center="center"
            :crs="crs"
            @mousemove="handleMouseMove"
        >
            <l-image-overlay
                :url="url"
                :bounds="bounds"
            />

            <l-marker
                v-for="b in bookables"
                :lat-lng="[b.lat, b.lon]"
                :key="b.id"
            >
                <l-icon
                    :icon-url="'./images/available.png'"
                    :icon-size="[30, 30]"
                />
            </l-marker>

        </l-map>
        <p>Lat:{{ mouseLat }} Long:{{ mouseLon }}</p>
    </div>
</template>

<script>
import { CRS } from 'leaflet';
import { LMap, LImageOverlay, LMarker, LIcon } from 'vue2-leaflet';

export default {
    components: {
        LMap,
        LImageOverlay,
        LMarker,
        LIcon,
    },
    data() {
        return {
            url: './images/bigroom.png',
            bounds: [[0, 0], [1095, 1899]],
            mapBoundaries: [[-20, -20], [1115, 1919]],
            center: [547, 945],
            minZoom: -1,
            zoom: -1,
            maxZoom: 0,
            crs: CRS.Simple,
            bookables: [
                { // left down corner
                    id: 9991,
                    lat: 0,
                    lon: 0,
                },
                { // right upper corner
                    id: 9992,
                    lat: 1095,
                    lon: 1899,
                },
                {
                    id: 1,
                    lat: 918,
                    lon: 180,
                },
                {
                    id: 2,
                    lat: 918,
                    lon: 419,
                },
                {
                    id: 3,
                    lat: 918,
                    lon: 613,
                },
                {
                    id: 4,
                    lat: 918,
                    lon: 1243,
                },
                {
                    id: 5,
                    lat: 918,
                    lon: 1474,
                },
                {
                    id: 6,
                    lat: 918,
                    lon: 1673,
                },
                {
                    id: 7,
                    lat: 640,
                    lon: 180,
                },
                {
                    id: 8,
                    lat: 640,
                    lon: 419,
                },
                {
                    id: 9,
                    lat: 640,
                    lon: 1243,
                },
                {
                    id: 10,
                    lat: 640,
                    lon: 1474,
                },
                {
                    id: 11,
                    lat: 640,
                    lon: 1673,
                },
                {
                    id: 12,
                    lat: 490,
                    lon: 280,
                },
                {
                    id: 13,
                    lat: 462,
                    lon: 490,
                },
            ],
            mouseLat: 0,
            mouseLon: 0,
        };
    },
    methods: {
        handleMouseMove(event) {
            this.mouseLat = event.latlng.lat;
            this.mouseLon = event.latlng.lng;
        },
    },
};
</script>

<style>
</style>
