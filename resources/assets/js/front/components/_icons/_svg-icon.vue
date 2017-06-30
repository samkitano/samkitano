<template lang="html">
  <div :aria-label="ariaText" class="hint--top-right">
    <div class="Svg">
      <svg height="16"
           role="img"
           width="16"
           :aria-label="ariaText"
           :class="iconClass"
           :viewBox="viewBox"
           v-on:click="emitClick"
      >
        <path v-if="isComment" d="M2.002 2C1.45 2 1 2.45 1 3.007v12.986C1 16.55 1.443 17 2 17h2l2.817
        3.275c.36.42.954.415 1.317 0L11 17h10.998C22.55 17 23 16.55 23 15.993V3.007C23 2.45 22.56 2 21.998 2H2.002zM2
        3v13h2.516l2.956 3.562L10.712 16H22V3H2zm3.22 3C5.1 6 5 6.1 5 6.222v.556C5 6.9 5.097 7 5.22 7h12.56c.12 0
        .22-.1.22-.222v-.556C18 6.1 17.903 6 17.78 6H5.22zm0 3C5.1 9 5 9.1 5 9.222v.556c0 .123.097.222.22.222h12.56c.12
        0 .22-.1.22-.222v-.556C18 9.1 17.903 9 17.78 9H5.22zm-.01 3c-.116 0-.21.1-.21.222v.556c0
        .123.095.222.21.222h8.58c.116 0 .21-.1.21-.222v-.556c0-.123-.095-.222-.21-.222H5.21z" fill-rule="evenodd"/>

        <path v-if="isLike" d="M10 20c5.523 0 10-4.477 10-10S15.523 0 10 0 0 4.477 0 10s4.477 10 10 10zm0-2c4.418 0
        8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8zM6.5 9C7.328 9 8 8.328 8 7.5S7.328 6 6.5 6 5 6.672 5 7.5 5.672
        9 6.5 9zm7 0c.828 0 1.5-.672 1.5-1.5S14.328 6 13.5 6 12 6.672 12 7.5 12.672 9 13.5 9zm2.16 3c-.825 2.33-3.048
        4-5.66 4s-4.835-1.67-5.66-4h11.32z" fill-rule="evenodd"/>

        <path v-if="isEdit" d="M18.88 2.527L9.796 14.154l-.05 2.543 2.456-.664 9.083-11.627-.8-.626-8.896
        11.384-.904.33.1-.956 8.896-11.384-.802-.627zm2.368-1.378c-.41-.32-1.428.174-1.428.174l-.627.802 2.406
        1.88.625-.802s.235-1.108-.175-1.43l-.802-.625zM1 4.02v19h19v-15l-1 1v13H2v-17h13l1-1H1z"/>

        <path v-if="isTrash" d="M94.2 40L90 108.1c-.1 2.1-1.9 3.9-4 3.9H42c-2.1 0-3.9-1.8-4-3.9L33.8 40h-4l4.2 68.4c.3
        4.2 3.8 7.6 8 7.6h44c4.2 0 7.7-3.4 8-7.6L98.2 40h-4zM102 22H26c-2.2 0-4 1.8-4 4v8c0 2.2 1.8 4 4 4h76c2.2 0
        4-1.8 4-4v-8c0-2.2-1.8-4-4-4zm0 12H26v-8h76v8z"/>

        <path v-if="isTrash" d="M65 103.7c-.6 0-1-.4-1-1V45c0-.6.4-1 1-1s1 .4 1 1v57.7c0 .5-.4 1-1 1zM79.8 103.7c-.6
        0-1-.5-1-1L81 45c0-.6.5-1 1-1 .6 0 1 .5 1 1l-2.2 57.7c0 .5-.4 1-1 1zM49.7 103.7c-.5 0-1-.4-1-1L46.2 45c0-.6.4-1
        1-1 .5 0 1 .4 1 1l2.5 57.7c0 .5-.4.9-1 1z"/>

        <path v-if="isTrash" d="M53 20v-2c0-1.1.9-2 2-2h20c1.1 0 2 .9 2 2v2h4v-2c0-3.3-2.7-6-6-6H55c-3.3 0-6 2.7-6
        6v2h4z"/>

        <title>{{ ariaText }}</title>
      </svg>&nbsp;<span>{{ iconText }}</span></div
    >
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    computed: {
      isComment () {
        return this.icon === 'comment'
      },

      isEdit () {
        return this.icon === 'edit'
      },

      isLike () {
        return this.icon === 'like'
      },

      isTrash () {
        return this.icon === 'trash'
      },

      viewBox () {
        if (this.isComment || this.isEdit) { return '0 0 24 24' }
        if (this.isLike) { return '0 0 20 20' }
        if (this.isTrash) { return '0 0 128 128' }

        return '0 0 20 20'
      }
    },

    methods: {
      emitClick (e) {
        this.$emit('click', e)
      }
    },

    props: {
      iconClass: {
        required: true,
        type: String
      },
      iconText: {
        required: true,
        type: String
      },
      ariaText: {
        required: true,
        type: String
      },
      icon: {
        required: true,
        type: String
      }
    }
  }
</script>
