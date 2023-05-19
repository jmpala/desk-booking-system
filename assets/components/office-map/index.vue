<template>
  <div class="office-map-component">
    <date-picker
        :selectedDate="selectedDate"
        @dateChanged="handleDateChanged"
    />

    <div
        class="border border-primary border-2 rounded-2"
        :class="{
          [$style.mapContainer]: true,
          [$style.pastDateBorder]: isPastDate,
        }"
    >
      <l-map
          ref="map"
          style="height: 600px"
          :max-bounds="mapBoundaries"
          :zoom="zoom"
          :min-zoom="minZoom"
          :max-zoom="maxZoom"
          :center="center"
          :crs="crs"
          :options="{ attributionControl: false }"
          @mousemove="handleMouseMove"
          @click="handleMapClick"
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
            @click="openOverlay"
        />

        <status-legends
            :statusColors="statusColors"
        />
      </l-map>

      <transition
          name="fade"
          :enter-class="$style['fade-enter']"
          :enter-active-class="$style['fade-enter-active']"
          :leave-class="$style['fade-leave']"
          :leave-active-class="$style['fade-leave-active']"
          appear
      >
        <information-overlay
            v-if="isOverlayOpen"
            :isOverlayOpen="isOverlayOpen"
            :selectedBookable="selectedBookable"
            :isPastDate="isPastDate"
            :selectedDate="selectedDate"
        />
      </transition>

      <transition
          name="fade-past-booking-overlay"
          :enter-class="$style['fade-past-booking-overlay-enter']"
          :enter-active-class="$style['fade-past-booking-overlay-enter-active']"
          :leave-class="$style['fade-past-booking-overlay-leave']"
          :leave-active-class="$style['fade-past-booking-overlay-leave-active']"
          appear
      >
        <past-date-overlay
            v-if="isPastDate"
        />
      </transition>

    </div>
    <p>Lat:{{ mouseLat }} Long:{{ mouseLon }}</p>
  </div>
</template>

<script>
import { getOfficeStateByDate } from '@/services/loadBookablesService';
import { CRS } from 'leaflet';
import { LMap, LImageOverlay } from 'vue2-leaflet';
import StatusLegends from '@/components/office-map/status-legends';
import SeatMarker from '@/components/office-map/seat-marker';
import DatePicker from '@/components/office-map/date-picker';
import InformationOverlay from '@/components/office-map/information-overlay';
import PastDateOverlay from '@/components/office-map/past-date-overlay';

import { extractDateFromDateIsoString } from '@/js/src/components/seatmap/utils/utils';

export default {
  components: {
    LMap,
    LImageOverlay,
    StatusLegends,
    SeatMarker,
    DatePicker,
    InformationOverlay,
    PastDateOverlay,
  },
  data() {
    return {
      url: './images/bigroom.png',
      bounds: [[0, 0], [1095, 1899]],
      mapBoundaries: [[-1095, -1899], [2190, 3798]],
      center: [547, 945],
      minZoom: -1,
      zoom: -1,
      maxZoom: 0,
      crs: CRS.Simple,
      bookables: [],
      selectedBookable: null,
      mouseLat: 0,
      mouseLon: 0,
      statusColors: {
        available: '#bada55',
        booked: '#d51961',
        bookedByYou: '#3399ff',
        disabled: '#5a5a5a',
      },
      selectedDate: extractDateFromDateIsoString(new Date()),
      isOverlayOpen: false,
      isPastDate: false,
    };
  },
  async mounted() {
    await this.load();
  },
  methods: {
    handleMouseMove(event) {
      this.mouseLat = event.latlng.lat;
      this.mouseLon = event.latlng.lng;
    },
    handleDateChanged(event) {
      const today = new Date(extractDateFromDateIsoString(new Date()));
      this.selectedDate = extractDateFromDateIsoString(event);
      this.isPastDate = new Date(this.selectedDate) < today;
      this.closeOverlay();
    },
    handleMapClick(event) {
      if (this.$refs.map.$el === event.originalEvent.target) return this.closeOverlay();
    },
    async load() {
      const res = await getOfficeStateByDate(new Date(this.selectedDate));
      if (!res) {
        console.error('Error, response: ', res);
        return;
      }
      console.log('Response: ', res.data);
      this.bookables = res.data.bookables;
    },
    openOverlay(event) {
      this.selectedBookable = event;
      this.isOverlayOpen = true;
    },
    closeOverlay() {
      this.isOverlayOpen = false;
    },
  },
  watch: {
    async selectedDate() {
      await this.load();
    }
  }
};
</script>

<style lang="scss" module>
.mapContainer {
  position: relative;
  overflow: hidden;
}

.pastDateBorder {
  border: 2px solid #d51961 !important;
}

.fade-enter, .fade-leave-to, .fade-leave-active {
  left: -50%;
}

.fade-enter-active, .fade-leave-active {
  transition: left 0.4s ease-in-out;
}

.fade-past-booking-overlay-enter,
.fade-past-booking-overlay-leave-to,
.fade-past-booking-overlay-leave-active {
  bottom: -10%;
}

.fade-past-booking-overlay-enter-active,
.fade-past-booking-overlay-leave-active {
  transition: bottom 0.5s ease-in-out;
}

</style>
