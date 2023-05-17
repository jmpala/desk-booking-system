<template>
    <div class="d-flex">
        <button
            class="btn btn-outline-primary"
            id="moveBacks"
            @click="moveBack"
        >
            <i class="bi bi-chevron-double-left" />
        </button>
        <input
            id="datePickers"
            type="date"
            :value="this.selectedDate"
            @change="dateSelected"
        >
        <button
            class="btn btn-outline-primary"
            id="moveForwards"
            @click="moveForward"
        >
            <i class="bi bi-chevron-double-right" />
        </button>
    </div>
</template>

<script>
export default {
    props: {
        selectedDate: {
            type: String,
            required: true,
        },
    },
    methods: {
        moveBack() {
            const date = new Date(this.selectedDate);
            date.setDate(date.getDate() - 1);
            this.$emit('dateChanged', date);
        },
        moveForward() {
            const date = new Date(this.selectedDate);
            date.setDate(date.getDate() + 1);
            this.$emit('dateChanged', date);
        },
        dateSelected(event) {
            const date = new Date(event.target.value);
            this.$emit('dateChanged', date);
        }
    },
};
</script>

<style scoped lang="scss">

</style>
