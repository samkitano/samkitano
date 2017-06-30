<template lang="html">
  <div class="Article__Footer__Icon Article__Footer__Icon_likes" v-if="article">
    <svg-icon :ariaText="ariaText"
              :iconClass="iconClass"
              :iconText="totalLikes"
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
      ariaText () {
        if (this.canLike && !this.canNotLike) {
          return 'Click to like this article'
        }

        let base = 'Like'
        let total = this.likes.total
        let res = total > 1 ? `${base}s` : base

        return this.canNotLike ? 'You Like This Article' : `${total} ${res}`
      },

      authed () {
        return this.userIsAuthed(this.storedUser) // @mixins.js
      },

      canLike () {
        return !this.canNotLike && this.authed && this.currentRoute !== 'articles'
      },

      canNotLike () {
        return this.authed && includes(this.likes.users, this.storedUser.slug)
      },

      currentRoute () {
        return location.href.substr(location.href.lastIndexOf('/') + 1)
      },

      iconClass () {
        let base = 'Svg__Icon'
        let clickable = this.canLike ? ` ${base}_clickable` : ` ${base}_unclickable`
        let liked = this.canNotLike ? ` ${base}_active` : ''

        return base + liked + clickable
      },

      totalLikes () {
        return this.likes.total.toString()
      }
    },

    data () {
      return {
        isLiking: false,
        likes: this.article.likes
      }
    },

    methods: {
      like () {
        if (!this.canLike || this.canNotLike || this.isLiking) {
          return false
        }

        this.isLiking = true

        ajax.post(`${process.env.articlesUrl}/${this.article.slug}/like`)
            .then((r) => {
              this.likes.users.push(this.storedUser.slug)
              this.likes.total++
              this.isLiking = false
            })
            .catch((e) => {
              this.$swal({
                title: 'Error!',
                text: e.response ? e.response.data.message : e.message,
                type: 'error',
                timer: 4000
              }).catch(this.$swal.noop)

              this.isLiking = false
            })
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
