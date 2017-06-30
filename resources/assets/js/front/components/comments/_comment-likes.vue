<template lang="html">
  <div class="Comment__Likes">
    <svg-icon @click.prevent="like"
              :ariaText="svgText"
              :iconClass="svgClass"
              :iconText="iconText"
              icon="like"/>
	</div>
</template>


<script type="text/ecmascript-6">
  import svgIcon from '../_icons/_svg-icon.vue'
  import {
    includes
  } from 'lodash'

  export default {
    components: {
      svgIcon
    },

    computed: {
      iconText () {
        return this.comment.likes.total.toString()
      },

      isAuthed () {
        return this.userIsAuthed(this.storedUser) // @mixins.js
      },

      isLikeable () {
        return this.isAuthed && !this.owned && !this.isLiked
      },

      isLiked () {
        return this.storedUser ? includes(this.comment.likes.users, this.storedUser.slug) : false
      },

      svgClass () {
        let base = 'Svg__Icon'
        let liked = this.isLiked ? ` ${base}_active` : ''
        let clickable = this.isLikeable ? ` ${base}_clickable` : ` ${base}_unclickable`

        return `${base}${liked}${clickable}`
      },

      svgText () {
        if (this.isLikeable && !this.owned) {
          return 'Click to like this comment'
        }

        let num = this.comment.likes.total
        let word = num > 1 || !num ? ' Likes' : ' Like'

        return this.isLiked ? 'You Like This Comment' : num.toString() + word
      }
    },

    data () {
      return {
        liking: false
      }
    },

    methods: {
      addLike () {
        this.comment.likes.users.push(this.storedUser.slug)
        this.comment.likes.total++
        this.liking = false
      },

      alertError (e) {
        let alert = {
          title: 'Sorry!',
          text: 'Try again later.',
          type: 'error'
        }

        this.liking = false
        this.$swal(alert).then(() => {}, (dismiss) => {})
      },

      like () {
        if (!this.isLikeable || this.liking) {
          return false
        }

        this.liking = true
        ajax.post(`/api/articles/${this.articleId}/comments/${this.comment.id}/like`)
            .then((r) => { this.addLike() })
            .catch((e) => { this.alertError(e) })
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
