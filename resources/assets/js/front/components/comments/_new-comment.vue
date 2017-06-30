<template lang="html">
  <div class="Editor">
    <h1 class="Pg-header">Your Comment</h1>

    <div class="Editor__Body">
      <textarea @input="updtMarkdown"
                :disabled="isEditing || submitting"
                :value="md"
                class="Editor__Markdown"
                id="new_user_comment"
                name="new_user_comment"
                placeholder="Type in your comment..."
                v-show="isMarkdown"
      ></textarea>

      <div class="Editor__Preview"
           v-html="compiled"
           v-show="!isMarkdown"/>
    </div>

    <div class="Editor__Footer">
      <button @click.prevent="submit"
              :class="submitClass"
              :disabled="isEditing || submitting"
              type="submit"
              v-html="buttonText"
      ></button>

      <button @click.prevent="toggleView"
              :disabled="isEditing || submitting"
              class="Editor__Toggle"
              v-html="mdText"
      ></button>

      <div class="Editor__Footer_right">
          <small>Use <a target="_blank"
                        href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a
                     > to format your comment</small
          >
      </div>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  const AutoSize = require('autosize')
  const mkd = require('markdown-it')('commonmark').use(require('markdown-it-highlightjs'))

  import {
    debounce
  } from 'lodash'

  export default {
    beforeDestroy () {
      let ta = document.querySelector('textarea')

      if (ta) {
        ta.removeEventListener('focus', function () {
          AutoSize(ta)
        })
      }

      eventBus.$off('editingComment', this.toggleNewComments)
    },

    computed: {
      buttonText () {
        return this.submitting ? 'Working' : 'Post'
      },

      compiled () {
        return mkd.render(this.input)
      },

      md () {
        return this.input
      },

      mdText () {
        return this.isMarkdown ? 'Preview' : 'Markdown'
      },

      submitClass () {
        let submitting = this.submitting ? ' Button_pulse' : ''
        return `Button Button_green${submitting}`
      }
    },

    created () {
      eventBus.$on('editingComment', this.toggleNewComments)
    },

    data () {
      return {
        input: '',
        isMarkdown: true,
        submitting: false,
        isEditing: false
      }
    },

    methods: {
      alertError () {
        this.$swal({
          title: 'Error!',
          text: 'Unable to post comment. Please try again.',
          type: 'error'
        }).then(() => {}, (dismiss) => {})

        this.submitting = false
      },

      submit () {
        if (!this.input.length || this.submitting) {
          this.focus('new_user_comment') // @mixins.js
          return false
        }

        this.submitting = true

        let payload = { body: this.input }

        ajax.post(`/api/articles/${this.articleId}/comments`, payload)
            .then((r) => { this.updateData(r.data.comment) })
            .catch((e) => { this.alertError() })
      },

      toggleNewComments (state) {
        this.isEditing = !!state
      },

      toggleView () {
        this.isMarkdown = !this.isMarkdown
      },

      updateData (newComment) {
        this.input = ''
        this.submitting = false
        this.comments.push(newComment)
      },

      updtMarkdown: debounce(function (e) { this.input = e.target.value }, 300)
    },

    mounted () {
      let ta = document.querySelector('textarea')

      if (ta) {
        ta.addEventListener('focus', function () {
          AutoSize(ta)
        })
      }
    },

    props: {
      articleId: {
        required: true,
        type: String
      },
      comments: {
        required: true,
        type: Array
      },
      storedUser: {
        required: true,
        type: Object
      }
    },

    watch: {
      'isMarkdown' () {
        if (this.isMarkdown) {
          setTimeout(() => {
            this.focus('new_user_comment') // @mixins.js
          }, 0)
        }
      }
    }
  }
</script>
