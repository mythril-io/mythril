<template>
    <div>
        <a class="button is-fullwidth is-primary" style="margin-bottom: 15px;">
            <span class="icon is-small">
                <i class="fas fa-reply-all"></i>
            </span>
            <span>Reply</span>
        </a>
        <a class="button is-small is-rounded is-fullwidth is-light heading" style="font-size: 11px;">First Post</a>
        <vue-slider
            v-model="value"
            direction="ttb"
            :tooltip="'always'"
            :tooltip-placement="'right'"
            dotSize="20"
            style="display: block; margin: 35px 0; height: 250px;"
            :process-style="{ backgroundColor: '#00d1b2' }"
            :min="min"
            :max="max"
        >
            <template v-slot:dot="{ value, focus }">
                <div :class="['custom-dot', { focus }]"></div>
            </template>
            <template v-slot:tooltip="{ value, focus }">
                <div :class="[
                    'custom-tooltip', 
                    { focus }, 
                    'vue-slider-dot-tooltip-inner', 
                    'vue-slider-dot-tooltip-inner-right',
                    'pointer'
                ]">
                    {{ value }} of {{ max }} posts <br>
                    ~ November 2019
                </div>
            </template>
        </vue-slider>
        <a class="button is-small is-rounded is-fullwidth is-light heading" style="font-size: 11px;">Last Post</a>
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'

export default {
  props: ["discussion", "posts"],
  components: {VueSlider},
  data() {
    return {
        value: [1],
        min: 1,
        max: null
    };
  },
  methods: {
  },
  created() {
      this.max = this.posts.total;
  }
}
</script>

<style>
.custom-dot {
    width: 100%;
    height: 100%;
    border-radius: 4px;
    background-color: #00d1b2;
    transition: all .3s;
}
.custom-dot:hover {
    /* transform: rotateZ(45deg); */
    cursor: move;
}
.custom-dot.focus {
    border-radius: 50%;
}
.pointer {
    cursor: move;
}
</style>