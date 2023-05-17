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
            :options="{ attributionControl: false }"
            @mousemove="handleMouseMove"
        >
            <l-image-overlay
                :url="url"
                :bounds="bounds"
            />

            <seat-marker
                v-for="b in bookables"
                :bookable="b"
                :key="b.id"
                :statusColors="statusColors"
            />

            <status-legends
                :statusColors="statusColors"
            />
        </l-map>
        <p>Lat:{{ mouseLat }} Long:{{ mouseLon }}</p>
    </div>
</template>

<script>
import { getOfficeStateByDate } from '@/services/loadBookablesService';
import { CRS } from 'leaflet';
import { LMap, LImageOverlay, LMarker, LIcon } from 'vue2-leaflet';
import StatusLegends from '@/components/office-map/status-legends';
import SeatMarker from '@/components/office-map/seat-marker';

export default {
    components: {
        LMap,
        LImageOverlay,
        LMarker,
        LIcon,
        StatusLegends,
        SeatMarker,
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
            bookables: [],
            mouseLat: 0,
            mouseLon: 0,
            statusColors: {
                available: '#bada55',
                booked: '#d51961',
                bookedByYou: '#3399ff',
                disabled: '#5a5a5a',
            },
        };
    },
    async mounted() {
        const res = await getOfficeStateByDate(new Date());
        if (!res) {
            console.error('Error, response: ', res);
            return;
        }
        console.log('Response: ', res.data);
        this.bookables = res.data.bookables;
    },
    methods: {
        handleMouseMove(event) {
            this.mouseLat = event.latlng.lat;
            this.mouseLon = event.latlng.lng;
        },
    },
};
</script>

<style lang="scss">

</style>
