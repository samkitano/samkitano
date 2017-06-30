<template lang="html">
  <div :class="commentClass" :id="commentId">
    <comm-head :comment="comment"/>

    <comm-body :comment="comment" v-show="!canEdit"/>

    <editor :comment="comment"
            :commentIdx="commentIdx"
            :editId="editId"
            :slug="articleId"
            v-if="canEdit"/>

    <hr :class="belongsToUser ? 'green' : 'blue'">

    <comm-footer :articleId="articleId"
                 :comment="comment"
                 :commentIndex="commentIndex"
                 :comments="comments"
                 :owned="belongsToUser"
                 :storedUser="storedUser"/>
  </div>
</template>


<script type="text/ecmascript-6">
  import commBody from './_comment-body.vue'
  import commFooter from './_comment-footer.vue'
  import commHead from './_comment-head.vue'
  import editor from './_editor.vue'

  export default {
    beforeDestroy () {
      eventBus.$off('editingComment', this.setEdition)
    },

    components: {
      commBody,
      commFooter,
      commHead,
      editor
    },

    computed: {
      admin () {
        return this.storedUser && this.storedUser.admin
      },

      authorMatch () {
        return this.storedUser.slug === this.comment.author.slug
      },

      belongsToUser () {
        return this.admin
          || (this.userIsAuthed(this.storedUser)
          && !this.userAwaitsConfirmation(this.storedUser)
          && this.authorMatch)
      },

      canEdit () {
        return this.belongsToUser && this.editId === this.comment.id
      },

      commentId () {
        return `Comment_${this.comment.id}`
      },

      commentClass () {
        let base = 'Comment'
        let ownd = this.belongsToUser ? ` ${base}__Owned` : ''
        let edit = this.editId === this.comment.id ? ` ${ownd}_editing` : ''

        return `${base}${ownd}${edit}`
      }
    },

    created () {
      eventBus.$on('editingComment', this.setEdit)
    },

    data () {
      return {
        commentIdx: false,
        editId: false,
        isEditing: false
      }
    },

    methods: {
      setEdit (args) {
        if (!args) {
          this.isEditing = false,
          this.editId = false,
          this.commentIdx = false
        } else {
          this.isEditing = true,
          this.editId = args.commentId,
          this.commentIdx = args.commentIdx
        }
      }
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
      storedUser: {
        required: true,
        type: [Boolean, Object]
      }
    }
  }
</script>
