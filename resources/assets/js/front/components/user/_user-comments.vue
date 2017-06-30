<template lang="html">
  <div class="User__Comments">
    <div class="User__Key" v-html="'Comments'"/>

    <div class="User__Val" v-if="comments">
      <div v-for="comment in comments">
        <a :href="`/articles/${comment.article_slug}`">{{ comment.article_title }}</a>
      </div>
    </div>

    <div class="User__Val" v-else>No comments made, so far.</div>
  </div>
</template>


<script type="text/ecmascript-6">
  import {
    filter,
    uniqBy
  } from 'lodash'

  export default {
    computed: {
      comments () {
        let comms = this.profile.comments
        let unique = uniqBy(comms, 'article_slug')

        for (let item in unique) {
          unique[item].count = filter(comms, {
            'article_slug': unique[item]['article_slug']
          }).length
        }

        return unique
      }
    },

    props: {
      profile: {
        required: true,
        type: Object
      }
    }
  }
</script>
