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
                    :class="[$style.circle, $style.available]"
                />
                Available
            </li>
            <li>
                <span
                    :class="[$style.circle, $style.booked]"
                />
                Booked
            </li>
            <li>
                <span
                    :class="[$style.circle, $style.bookedByYou]"
                />
                Booked by you
            </li>
            <li>
                <span
                    :class="[$style.circle, $style.disabled]"
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

  &.available {
    background: #bada55;
  }

  &.booked {
    background: #5a5a5a;
  }

  &.bookedByYou {
    background: #3399ff;
  }

  &.disabled {
    background: #d51961;
  }
}
</style>
