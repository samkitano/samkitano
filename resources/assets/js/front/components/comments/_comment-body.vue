<template lang="html">
    <div class="Comment__Body" v-html="body"/>
</template>


<script type="text/ecmascript-6">
  const md = require('markdown-it')('commonmark').use(require('markdown-it-highlightjs'))

  export default {
    computed: {
      body () {
        return md.render(this.comment.body)
      }
    },

    props: {
      comment: {
        required: true,
        type: Object
      }
    }
  }
</script>
