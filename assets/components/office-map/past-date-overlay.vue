<template>
  <h1
      :class="$style.pastBookingsOverlay"
  >Watching Past Dates</h1>
</template>

<script>
</script>

<style lang="scss" module>
.pastBookingsOverlay {
  margin: 0;
  padding: 0 1rem;
  position: absolute;
  bottom: 0;
  left: 50%;
  z-index: 1000;
  background-color: #fff;
  border-radius: 5px;
  border: 1px solid #d51961;
  -webkit-box-shadow: 2px 0px 5px 0px rgba(0, 0, 0, 0.59);
  box-shadow: 2px 0px 5px 0px rgba(0, 0, 0, 0.59);
  transform: translateX(-50%);
}

</style>
