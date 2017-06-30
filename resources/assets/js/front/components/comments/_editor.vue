<template lang="html">
  <div class="Editor">
    <div class="Editor__Body">
      <textarea @input="updtMarkdown"
                @keyup.esc="cancel"
                :value="md"
                class="Editor__Markdown"
                name="user_comment"
                placeholder="Type in your comment..."
                v-show="isMarkdown"
      ></textarea>

      <div class="Editor__Preview"
           v-html="compiled"
           v-show="! isMarkdown"/>
    </div>

    <div class="Editor__Footer">
      <button @click.prevent="submit"
              :class="submitClass"
              :disabled="submitting"
              type="submit"
              v-html="buttonText"/>

      <button @click.prevent="toggleView"
              class="Editor__Toggle"
              v-html="mdText"/>

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
    },

    computed: {
      buttonText () {
        return this.submitting ? 'Working' : 'Update'
      },

      canEdit () {
        return this.isEditing && (this.editingComment === this.comment.id)
      },

      compiled () {
        return mkd.render(this.input)
      },

      isEditing () {
        return this.editingComment !== false
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

    data () {
      return {
        input: this.comment.body,
        isMarkdown: true,
        submitting: false,
        payload: {
          '_method': 'PATCH',
          body: ''
        }
      }
    },

    methods: {
      cancel () {
        this.clearForm()
        eventBus.$emit('editingComment', false)
      },

      clearForm () {
        this.input = ''
        this.submitting = false
      },

      finish () {
        this.comment.body = this.payload.body
        this.comment.updated = Date.now()
        this.clearForm()
      },

      showError () {
        this.$swal({
          title: 'Error',
          text: 'Unable to update your comment. Please, try again later.',
          type: 'error'
        }).then(() => {}, (dismiss) => {})

        this.submitting = false
      },

      toggleView () {
        this.isMarkdown = !this.isMarkdown
      },

      submit () {
        if (!this.input.length || this.submitting) {
          return false
        }

        this.submitting = true
        this.payload.body = this.input

        ajax.post(`/api/articles/${this.slug}/comments/${this.editId}`, this.payload)
            .then((r) => { this.finish() })
            .catch((e) => { this.showError() })

        this.cancel()
      },

      updtMarkdown: debounce(
        function (e) {
          this.input = e.target.value
        }, 300
      )
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
      comment: {
        required: true,
        type: Object
      },
      commentIdx: {
        required: true,
        type: Number
      },
      editId: {
        required: true,
        type: Number
      },
      slug: {
        required: true,
        type: String
      }
    },

    watch: {
      'isMarkdown' () {
        if (this.isMarkdown) {
          setTimeout(() => {
            document.querySelector('textarea').focus()
          }, 0)
        }
      }
    }
  }
</script>
