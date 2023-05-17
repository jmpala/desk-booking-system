<template>
    <div
        :class="{
            [$style.overlay]: true,
            [$style.hidden]: !isOverlayOpen
        }"
    >
        <h1 class="h1 mt-3 text-center">{{ selectedBookable.bookableCode }}</h1>
        <h2 class="text-center">{{ statusString }}</h2>

        <div
            v-if="isDisabled"
            class="d-flex flex-column align-items-center justify-content-center"
        >
            <p>From: {{ disableFromDate }}</p>
            <p>To: {{ disableToDate }}</p>
            <p>{{ this.selectedBookable.disabledNotes }}</p>
        </div>

        <div
            v-if="isBookedByLoggedUser"
            class="d-flex flex-column align-items-center justify-content-center"
        >
            <p>Confirmation: {{ this.selectedBookable.bookingConfirmationCode }}</p>
            <p>From: {{ bookedFromDate }}</p>
            <p>To: {{ bookedToDate }}</p>
            <a class="btn btn-primary" :href="'/booking/' + this.selectedBookable.bookingId + '/edit'">Edit Booking</a>
        </div>

        <div
            v-if="isBooked && !isBookedByLoggedUser"
            class="d-flex flex-column align-items-center justify-content-center"
        >
            <p>Confirmation: {{ this.selectedBookable.bookingConfirmationCode }}</p>
            <p>From: {{ bookedFromDate }}</p>
            <p>To: {{ bookedToDate }}</p>
        </div>

        <div
            v-if="!isBooked && !isBookedByLoggedUser && !isDisabled"
            class="d-flex flex-column align-items-center justify-content-center"
        >
            <a class="btn btn-primary">Book</a>
        </div>

    </div>
</template>

<script>
import { extractDateFromDateIsoString } from '@/js/src/components/seatmap/utils/utils';

export default {
    props: {
        isOverlayOpen: {
            type: Boolean,
            required: true,
        },
        selectedBookable: {
            type: Object,
            required: false,
        },
    },
    methods: {
        extractDateFromDateIsoString,
    },
    computed: {
        statusString() {
            if (this.selectedBookable.isDisabled) return "Disabled";
            if (this.selectedBookable.isBookedByLoggedUser) return "Booked by you";
            if (this.selectedBookable.isBooked) return "Booked";
            return "Available";
        },
        isDisabled() {
            return this.selectedBookable.isDisabled;
        },
        isBookedByLoggedUser() {
            return this.selectedBookable.isBookedByLoggedUser;
        },
        isBooked() {
            return this.selectedBookable.isBooked;
        },
        disableFromDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.disabledFromDate))
        },
        disableToDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.disabledToDate))
        },
        bookedFromDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.bookingStartDate))
        },
        bookedToDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.bookingEndDate))
        },
    },
};
</script>

<style lang="scss" module>
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  min-width: min(100%, 500px);
  height: 100%;
  z-index: 1000;
  background-color: #fff;
  border-radius: 5px;
  border: 1px solid #ccc;
  -webkit-box-shadow: 2px 0px 5px 0px rgba(0, 0, 0, 0.59);
  box-shadow: 2px 0px 5px 0px rgba(0, 0, 0, 0.59);
  transition: left 0.5s ease-out;
}

.hidden {
  left: -500px;
}
</style>
