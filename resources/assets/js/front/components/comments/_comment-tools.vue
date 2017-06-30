<template lang="html">
  <div class="Comment__Tools">
    <svg-icon @click.prevent="editComment"
              ariaText="Edit"
              icon="edit"
              iconClass="Svg__Icon_clickable"
              iconText=""
              v-if="!disabled"/>

    <svg-icon @click.prevent="deleteComment"
              ariaText="Delete"
              icon="trash"
              iconClass="Svg__Icon_clickable"
              iconText=""
              v-if="!disabled"/>
  </div>
</template>


<script type="text/ecmascript-6">
  import svgIcon from '../_icons/_svg-icon.vue'

  export default {
    components: {
      svgIcon
    },

    computed: {
      ariaText () {
        return this.editingComment ? 'Cancel' : 'Edit'
      },

      disabled () {
        return this.userAwaitsConfirmation(this.storedUser) || (this.isEditing && !this.isBeingEdited)
      },

      isBeingEdited () {
        return this.editId === this.comment.id
      }
    },

    data () {
      return {
        commentIdx: false,
        editId: false,
        isEditing: false
      }
    },

    methods: {
      alertDeleted () {
        this.$swal({
          title: 'Done!',
          text: 'Comment Deleted',
          type: 'success',
          timer: 3000
        }).then(() => {}, (dismiss) => {})
      },

      deleteComment () {
        this.$swal({
          title: 'Delete Comment',
          text: 'Are you sure?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55'
        }).then(() => { this.removeComment() }, (dismiss) => {})
      },

      editComment () {
        if (this.isEditing) {
          eventBus.$emit('editingComment', false)
          this.isEditing = false
          this.editId = false
          this.commentIdx = false
        } else {
          eventBus.$emit('editingComment', {
            commentId: this.comment.id,
            commentIdx: this.commentIndex }
          )
          this.isEditing = true
          this.editId = this.comment.id
          this.commentIdx = this.commentIndex
        }
      },

      removeComment () {
        let payload = { '_method': 'DELETE' }
        let spliced = this.comments.splice(this.commentIndex, 1)

        ajax.post(`/api/articles/${this.articleId}/comments/${this.comment.id}`, payload)
            .then((r) => { this.alertDeleted() })
            .catch((e) => { this.resetComments(spliced[0]) })
      },

      resetComments (deletedComment) {
        this.comments.push(deletedComment)
        this.$swal({
          title: 'Error!',
          text: 'Unable to delete comment. Please try again.',
          type: 'error'
        }).then(() => {}, (dismiss) => {})
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
      comments: {
        required: true,
        type: Array
      },
      commentIndex: {
        required: true,
        type: Number
      }
    }
  }
</script>
