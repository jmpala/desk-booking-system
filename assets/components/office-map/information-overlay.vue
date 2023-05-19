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
            class="d-flex flex-column align-items-center justify-content-center"
        >
            <ul
                v-if="isDisabled"
            >
                <li>From: {{ disableFromDate }}</li>
                <li>To: {{ disableToDate }}</li>
                <li>{{ this.selectedBookable.disabledNotes }}</li>
            </ul>

            <ul
                v-if="isBookedByLoggedUser"
            >
                <li>Confirmation: {{ this.selectedBookable.bookingConfirmationCode }}</li>
                <li>{{ this.selectedBookable.userName }}</li>
                <li>From: {{ bookedFromDate }}</li>
                <li>To: {{ bookedToDate }}</li>
                <li>
                    <a
                        v-if="!isPastDate"
                        class="btn btn-primary"
                        :href="'/booking/' + this.selectedBookable.bookingId + '/edit'"
                    >
                        Edit Booking
                    </a>
                </li>
            </ul>

            <ul
                v-if="isBooked && !isBookedByLoggedUser"
            >
                <li>Confirmation: {{ this.selectedBookable.bookingConfirmationCode }}</li>
                <li>{{ this.selectedBookable.userName }}</li>
                <li>From: {{ bookedFromDate }}</li>
                <li>To: {{ bookedToDate }}</li>
            </ul>

            <ul
                v-if="!isBooked && !isBookedByLoggedUser && !isDisabled"
            >
                <li>
                    <a
                        v-if="!isPastDate"
                        class="btn btn-primary"
                    >Book</a>
                </li>
            </ul>

            <p
                v-if="isPastDate"
                class="text-center badge text-bg-dark"
            >
                Past date
            </p>

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
            required: true,
        },
        isPastDate: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        extractDateFromDateIsoString,
    },
    computed: {
        statusString() {
            if (this.selectedBookable.isDisabled) return 'Disabled';
            if (this.selectedBookable.isBookedByLoggedUser) return 'Booked by you';
            if (this.selectedBookable.isBooked) return 'Booked';
            return 'Available';
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
            return extractDateFromDateIsoString(new Date(this.selectedBookable.disabledFromDate));
        },
        disableToDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.disabledToDate));
        },
        bookedFromDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.bookingStartDate));
        },
        bookedToDate() {
            return extractDateFromDateIsoString(new Date(this.selectedBookable.bookingEndDate));
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
}

</style>
