<template lang="html">
  <div class="User__Bio">
    <div class="User__Key">Bio</div>

    <div class="User__Val" v-if="own && canEdit">
      <div class="Editable hint--right" :aria-label="label">
        <span contenteditable
              id="editable_name"
              data-ph="Tell us something about you..."
              v-on:blur="cancel"
              v-html="fields.bio.value"
              :class="userBioClass"
              @keydown.13="change"
              @keydown.27="cancel"
              @click="setEdit"
        ></span>
      </div>
    </div>

    <div class="User__Val" v-else>{{ profile.bio}}</div>

    <div class="User__Error" v-html="error"></div>
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    beforeDestroy () {
      this.cleanUp()
    },

    beforeMount () {
      this.fields.bio.value = this.profile.bio
    },

    computed: {
      canEdit () {
        return !this.disabled
      },

      label () {
        return this.isEditing ? 'Hit Enter to Submit. Esc to  cancel' : 'Click to Change'
      },

      userBioClass () {
        let base = 'Editable__content'
        let edit = !this.isEditing ? '' : ` ${base}_editing`
        let err = !this.error === '' ? '' : ` ${base}_error`

        return `${base}${edit}${err}`
      }
    },

    data () {
      return {
        error: '',
        fields: {
          bio: {
            errors: [],
            value: '',
            validation: {
              required: false,
              type: 'bio'
            }
          }
        },
        payload: {},
        isEditing: false
      }
    },

    methods: {
      alertDone () {
        this.error = this.checkMark('Bio updated') // @mixins.js

        setTimeout(() => {
          this.error = ''
        }, 3000)
      },

      cancel (e) {
        document.execCommand('undo')
        e.target.blur()
        this.isEditing = false
        eventBus.$emit('editingField', '')
      },

      change (e) {
        let bio = e.target.innerText.trim()

        e.target.blur()
        e.preventDefault()
        this.fields.bio.value = bio
        this.error = ''

        if (this.hasInvalidData()) {
          return false
        }

        this.setPayload()

        ajax.post(`/api/users/${this.profile.slug}`, this.payload)
            .then((r) => { this.alertDone() })
            .catch((e) => { this.error = e.response.data['bio'][0] })
      },

      cleanUp () {
        eventBus.$emit('editingField', false)
        this.fields.bio.value = ''
        this.fields.bio.errors = []
      },

      hasInvalidData () {
        this.fields = this.validate(this.fields) // @validation.js
        this.error = this.stringifyValidationErrors(this.fields) // @validation.js

        return this.validationHasErrors(this.fields) // @validation.js
      },

      setEdit () {
        this.isEditing = true
        eventBus.$emit('editingField', 'bio')
      },

      setPayload () {
        this.payload = {
          '_method': 'PATCH',
          bio: this.fields.bio.value.trim()
        }
      }
    },

    props: {
      disabled: {
        required: true,
        type: Boolean
      },
      own: {
        required: true,
        type: Boolean
      },
      profile: {
        required: true,
        type: Object
      }
    }
  }
</script>
