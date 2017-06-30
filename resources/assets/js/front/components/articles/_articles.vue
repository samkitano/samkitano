<template lang="html">
  <div :class="articleClass" v-if="article">
    <div class="Article__Heading">
      <div class="Article__Heading_link">
        <a :href="`/articles/${article.slug}`">{{ article.title }}</a>
      </div>

      <div class="Article__Heading_text Pg-header">
        {{ article.subtitle }}
      </div>
    </div>

    <div class="Article__Content">
      <div class="Article__Content_partial" v-if="! article.content">
        <p v-html="article.teaser"/>
      </div>

      <div class="Article__Content_partial" v-if="article._snippetResult">
        <p v-html="'[...] ' + article._snippetResult.body.value + ' [...]'"/>
      </div>
    </div>

    <div class="Article__Footer">
      <div class="Article__Footer_top">
        <icon-tags :article="article"/>
      </div>

      <div class="Article__Footer_bottom">
        <icon-likes :storedUser="storedUser" :article="article"/>

        <icon-comments :storedUser="storedUser" :article="article"/>

        <icon-dates :humanized="false" :article="article"/>
      </div>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  import iconComments from './_icon_comments.vue'
  import iconDates from './_icon_dates.vue'
  import iconLikes from './_icon_likes.vue'
  import iconTags from './_icon_tags.vue'

  export default {
    components: {
      iconComments,
      iconDates,
      iconLikes,
      iconTags
    },

    computed: {
      articleClass () {
        let aDate = this.digitizeDate(this.article.created) // @mixins.js
        let base = 'Articles__List Article Article_'

        return `${base}${aDate}`
      }
    },

    name: 'ListedArticle',

    props: {
      article: {
        required: true,
        type: Object
      },
      storedUser: {
        required: true,
        type: [Boolean, Object]
      }
    }
  }
</script>
