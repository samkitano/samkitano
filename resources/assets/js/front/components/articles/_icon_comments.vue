<template lang="html">
  <div class="Article__Footer__Icon Article__Footer__Icon_comments">
    <svg-icon :ariaText="ariaText"
              :iconClass="iconClass"
              :iconText="iconText"
              icon="comment"/>
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
        let base = 'comment'
        let pre = 'You have'
        let num = this.numberOfComments
        let own = num > 1 ? `${base}s` : base

        if (!this.storedUser || !num) {
          let total = this.article.comments.total
          let res = total > 1 ? `${base}s` : base

          return `${total} ${res}`
        }

        if (!num) {
          return 'You haven\'t posted any comments yet'
        }

        return `${pre} ${num} ${own}`
      },

      iconClass () {
        let base = 'Svg__Icon'
        let active = !this.userCommented ? '' : ` ${base}_active`

        return !this.storedUser ? base : `${base}${active}`
      },

      iconText () {
        return this.article.comments.total.toString()
      },

      isCommented () {
        return this.isDefined(this.article, 'comments') // @mixins.js
      },

      numberOfComments () {
        return this.userCommented ? this.posters[this.userSlug] : 0
      },

      posters () {
        return this.article.comments.users
      },

      userCommented () {
        return this.userIsAuthed(this.storedUser)
          && this.isCommented
          && this.isDefined(this.posters, this.userSlug)
      },

      userSlug () {
        return this.storedUser.slug
      }
    },

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
