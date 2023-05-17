<template>
    <l-circle
        :lat-lng="[
            bookable.shapeY,
            bookable.shapeX
            ]"
        :fill-color="statusColor"
        :color="statusColor"
    />
</template>

<script>
import { LCircle } from 'vue2-leaflet';

export default {
    data() {
        return {};
    },
    components: {
        LCircle,
    },
    props: {
        bookable: {
            type: Object,
            required: true,
        },
        statusColors: {
            type: Object,
            required: true,
        },
    },
    computed: {
        statusColor(): string {
            if (this.bookable.isDisabled) return this.statusColors.disabled;
            if (this.bookable.isBookedByLoggedUser) return this.statusColors.bookedByYou;
            if (this.bookable.isBooked) return this.statusColors.booked;
            return this.statusColors.available;
        },
    },
};
</script>

<style module lang="scss">

</style>
