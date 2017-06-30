<template lang="html">
  <div class="Comment__Footer">
    <comm-likes :articleId="articleId"
                :comment="comment"
                :owned="owned"
                :storedUser="storedUser"/>

    <comm-tools :articleId="articleId"
                :comment="comment"
                :commentIndex="commentIndex"
                :comments="comments"
                v-if="owned"/>
  </div>
</template>


<script type='text/ecmascript-6'>
  import commLikes from './_comment-likes.vue'
  import commTools from './_comment-tools.vue'

  export default {
    components: {
      commLikes,
      commTools
    },

    props: {
      articleId: {
        required: true,
        type: String
      },
      comment: {
        required: true,
        type: Object
      },
      commentIndex: {
        required: true,
        type: Number
      },
      comments: {
        required: true,
        type: Array
      },
      owned: {
        required: true,
        type: Boolean
      },
      storedUser: {
        required: true,
        type: [Boolean, Object]
      }
    }
  }
</script>
