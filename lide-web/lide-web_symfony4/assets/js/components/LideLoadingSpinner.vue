<template>
  <div class="half-circle-spinner" :style="{height : this.size + 'px', width: this.size + 'px'}">
    <div class="circle circle-1" :style="{'border-top-color' : color, 'border': 'calc(' + size + ' / 10)'}"></div>
    <div class="circle circle-2" :style="{'border-bottom-color' : color, 'border': 'calc(' + size + ' / 10)'}"></div>
  </div>
</template>

<script>
  export default {
    props: {
      color: {
        type: String,
        required: false,
        default () {
          this.$vuetify.theme.primary
        }
      },
      size: {
        type: Number,
        required: false,
        default () {
          return 120
        }
      }
    },
    name: "lide-loading-spinner"
  }
</script>
