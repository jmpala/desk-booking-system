<template>
    <div
        :class="{
            [$style.legendBox]: true,
            [$style.mouseOver]: mouseOver
        }"
        @mouseenter="mouseOver = true"
        @mouseleave="mouseOver = false"
    >
        <ul>
            <li>
                <span
                    :style="{'background-color': statusColors.available}"
                    :class="$style.circle"
                />
                Available
            </li>
            <li>
                <span
                    :style="{'background-color': statusColors.booked}"
                    :class="$style.circle"
                />
                Booked
            </li>
            <li>
                <span
                    :style="{'background-color': statusColors.bookedByYou}"
                    :class="$style.circle"
                />
                Booked by you
            </li>
            <li>
                <span
                    :style="{'background-color': statusColors.disabled}"
                    :class="$style.circle"
                />
                Disabled
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'StatusLegends',
    data() {
        return {
            mouseOver: false,
        };
    },
    props: {
        statusColors: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style lang="scss" module>
.legendBox {
  position: absolute;
  bottom: 0;
  right: 0;
  height: 150px;
  width: 200px;
  z-index: 1000;
  background-color: #efc050;
  font-size: 1.3rem;
  border-radius: 5px 0 0 0;
  text-align: left;
  animation: opacity 0.5s ease-in-out;

  &.mouseOver {
    opacity: 50%;
  }
}

ul {
  padding: 0.5rem;
  list-style: none;
}

.circle {
  border-radius: 50%;
  display: inline-block;
  height: 1.3rem;
  width: 1.3rem;
}
</style>
