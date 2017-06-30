<template lang="html">
  <section class="Comments" v-if="comments">
    <h1 class="Pg-header">Comments
      <span>({{ comments.length }})</span>
    </h1>

    <comment :articleId="articleId"
             :comment="comment"
             :comments="comments"
             :commentIndex="index"
             :key="comment.id"
             :storedUser="storedUser"
             v-for="(comment, index) in comments"/>

    <new-comm :articleId="articleId"
              :comments="comments"
              :storedUser="storedUser"
              v-if="isCommentable && !isEditing"/>
  </section>

  <section class="Comments" v-else>
    <app-error :error="error"/>
  </section>
</template>


<script type="text/ecmascript-6">
  import appError from '../app-error.vue'
  import comment from './_comment.vue'
  import newComm from './_new-comment.vue'

  export default {
    beforeMount () {
      this.fetchData()
    },

    components: {
      appError,
      comment,
      newComm
    },

    computed: {
      isCommentable () {
        return this.userIsAuthed(this.storedUser)
      },

      storedUser () {
        return this.user ? JSON.parse(this.user).user : false
      }
    },

    data () {
      return {
        comments: [],
        error: false,
        isEditing: false
      }
    },

    methods: {
      fetchData () {
        ajax.get(`/api/articles/${this.articleId}/comments`)
            .then((r) => { this.comments = r.data.comments })
            .catch((e) => {
              this.error = {
                status: '',
                message: 'Could not load comments.'
              }
            })
      }
    },

    name: 'CommentList',

    props: {
      articleId: {
        required: true,
        type: String
      },
      user: {
        required: true,
        type: String
      }
    }
  }
</script>
