<template>
  <div class="relative dropdown inline-block">
    <div @click.prevent="toggle" :ref="triggerContainerRef">
      <slot name="trigger" :state="state"></slot>
    </div>

    <portal to="end-of-document">
      <transition name="dropdown">
        <div ref="dropdownContent" class="dropdown__content" :style="dropdownStyle" v-if="isOpen">
          <slot name="content" v-bind:close="close"></slot>
        </div>
      </transition>
    </portal>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  props: {
    contentStyle: {
      type: Object,
      default: () => {}
    },
    closeOnAction: {
      type: Boolean,
      default: true
    },
    alignContent: {
      default: "right",
      type: String
    }
  },
  data() {
    return {
      buttonToFocusAfterClose: null,
      state: "default",
      stateChanged: Date.now(),
      top: 0,
      left: 0,
      right: 0
    };
  },
  methods: {
    setTriggerRectangle() {
      let trigger = this.$refs[this.triggerContainerRef].firstChild;
      let rect = trigger.getBoundingClientRect();

      this.top = rect.top + rect.height;
      this.left = rect.left;
      this.right = window.innerWidth - rect.right;
    },
    close() {
      if (this.state !== "default") {
        this.stateChange = Date.now();
      }

      this.state = "default";

      this.$emit("close");

      setTimeout(() => {
        window.dropdownOpen = false;

        this.buttonToFocusAfterClose = null;
      }, 100);
    },

    toggle(e) {
      console.log("togggling");
      if (this.isOpen) {
        this.close();
      } else {
        this.lastOpened = this._uid;

        this.stateChange = Date.now();

        this.setTriggerRectangle();

        window.dropdownOpen = true;

        this.state = "open";

        this.buttonToFocusAfterClose = this.$refs[
          this.triggerContainerRef
        ].firstChild;

        this.$emit("open");
      }
    },
    getContentStyle() {},
    elementInDropdownContent(dropdownContent, target) {
      return dropdownContent.innerHTML.includes(target.innerHTML);
    },
    escape(e) {
      if (e.keyCode === 27) {
        this.close();

        if (this.buttonToFocusAfterClose !== null) {
          this.buttonToFocusAfterClose.focus();
        }
      }
    },
    documentClick(e, uid) {
      this.$nextTick(() => {
        let el = this.$refs.dropdownContent;

        if (!el) {
          return;
        }

        let shouldClose =
          !this.elementInDropdownContent(el, e.target) || this.closeOnAction;

        if (shouldClose && !this.isAnimating()) {
          // this.state = "default";
          // this.$emit("close");

          this.close();
        }
      });
    },
    isAnimating() {
      return Date.now() - this.stateChange < 400;
    }
  },
  computed: {
    dropdownStyle({ contentStyle, alignContent }) {
      let trigger = this.$refs[this.triggerContainerRef];
      return {
        ...contentStyle,
        ...{
          top: this.top + "px",
          [alignContent]: this[alignContent] + "px",
          width: trigger.clientWidth + "px"
        }
      };
    },
    isOpen({ trigger }) {
      return this.state === "open";
    },
    stateChangedRently() {
      return Date.now() - this.stateChange < 100;
    },
    animating() {
      return Date.now() - this.stateChange < 400;
    },
    triggerContainerRef() {
      return `triggerContainerId${this._uid}`;
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.setTriggerRectangle();
    });

    window.addEventListener("resize", this.setTriggerRectangle);
    window.addEventListener("scroll", this.setTriggerRectangle);
  },
  created() {
    document.addEventListener("click", this.documentClick);
    document.addEventListener("keydown", this.escape.bind(this._uid));
  },
  destroyed() {
    document.removeEventListener("click", this.documentClick);
    document.removeEventListener("keydown", this.escape);
    window.removeEventListener("resize", this.setTriggerRectangle);
    window.removeEventListener("scroll", this.setTriggerRectangle);
  }
};
</script>

<style scoped lang="scss">
.dropdown {
  position: relative;

  &__content {
    position: fixed;
    min-width: 100px;
    background: #fff;
    overflow-x: hidden;
    overflox-y: scroll;
    z-index: 100;
  }
}
</style>
