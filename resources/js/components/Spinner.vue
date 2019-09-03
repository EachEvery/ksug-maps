<template>
  <loading-icon class="text-white spin" />
</template>
<style>
.spin {
  animation: spin 3s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotateZ(0);
  }
  100% {
    transform: rotateZ(360deg);
  }
}
</style>

</style>
<script>
import loadingIcon from "./LoadingIcon";

export default {
  components: {
    loadingIcon
  }
};
</script>