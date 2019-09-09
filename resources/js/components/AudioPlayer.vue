<template>
  <div class="w-full overflow-hidden pt-24">
    <div
      class="bg-white px-8 xl:px-24 py-4"
      style="box-shadow: 0 10px 15px 21px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"
    >
      <div
        class="text-center uppercase font-mono font-bold text-xs lg:-ml-6 xl:mb-3 xl:text-base"
      >{{label}}</div>

      <div class="flex">
        <clickable @click.prevent="() =>controlAudio('play')" v-if="state !== 'play'">
          <play-icon class="w-8 h-8 xl:w-12 xl:h-12 text-black" />
        </clickable>

        <clickable @click.prevent="() =>controlAudio('pause')" v-if="state !== 'pause'">
          <pause-icon class="w-8 h-8 xl:w-12 xl:h-12 text-black" />
        </clickable>

        <div class="flex relative flex-grow mx-5 self-center">
          <input
            type="range"
            class="w-full py-2"
            v-model="sliderVal"
            @mousedown="handleRangeMouseDown"
            @mouseup="handleRangeMouseUp"
          />
        </div>

        <audio
          class="hidden"
          :src="src"
          ref="audio"
          @timeupdate="handleTimeUpdated"
          @canplay="setDuration"
        />

        <span
          class="self-center text-black font-display font-semibold w-16 -mt-1 text-center"
        >{{timestamp}}</span>
      </div>
    </div>
  </div>
</template>
<script>
import clickable from "./Clickable";
import playIcon from "./PlayIcon";
import pauseIcon from "./PauseIcon";

export default {
  components: {
    clickable,
    playIcon,
    pauseIcon
  },
  props: {
    src: String,
    label: String
  },
  data() {
    return {
      currentTime: 0,
      totalTime: 0,
      sliderVal: 0,
      state: "pause"
    };
  },
  computed: {
    scrubberStyle({ sliderVal }) {
      return { left: `${sliderVal}%` };
    },
    sliderTime({ sliderVal, totalTime }) {
      return totalTime * (sliderVal / 100);
    },
    currentMinutes({ sliderTime }) {
      return Math.floor(sliderTime / 60);
    },

    currentSeconds({ sliderTime }) {
      return sliderTime % 60;
    },

    timestamp({ currentMinutes, currentSeconds }) {
      return `${this.pad(currentMinutes)}:${this.pad(currentSeconds)}`;
    }
  },
  methods: {
    setDuration(e) {
      this.totalTime = e.target.duration;
    },
    pad(n) {
      return ("0000" + n).slice(-2);
    },
    handleRangeMouseDown() {
      this.$refs.audio.pause();
    },
    handleRangeMouseUp() {
      let perc = this.sliderVal / 100;

      let newTime = this.totalTime * perc;

      this.$refs.audio.currentTime = newTime;
      this.current = newTime;

      this.controlAudio("play");
    },
    controlAudio(method) {
      this.state = method;
      this.$refs.audio[method]();
    },
    handleTimeUpdated(e) {
      this.updateTime(e.target.currentTime);
    },
    updateTime(time) {
      this.currentTime = Math.round(time);

      this.totalTime = this.$refs.audio.duration;

      this.sliderVal = (this.currentTime / this.totalTime) * 100;
    }
  }
};
</script>

