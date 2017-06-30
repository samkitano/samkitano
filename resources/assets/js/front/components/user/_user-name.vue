<template lang="html">
  <div class="User__Name">
    <div class="User__Key">Name</div>

    <div class="User__Val" v-if="canEdit && own">
      <div class="Editable hint--right" :aria-label="fieldLabel">
        <span contenteditable
              id="editable_name"
              data-ph="Your name is required"
              v-on:blur="cancel"
              :class="fieldClass"
              @keydown.13="change"
              @keydown.27="cancel"
              @click="setEdit">{{ fields.name.value }}</span>
      </div>
    </div>

    <div class="User__Val" v-else>{{ profile.name }}</div>

    <div class="User__Error" v-html="error"></div>
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    beforeDestroy () {
      this.cleanUp()
    },

    beforeMount () {
      this.fields.name.value = this.profile.name
    },

    computed: {
      canEdit () {
        return !this.disabled
      },

      fieldClass () {
        let base = 'Editable__content'
        let edit = !this.isEditing ? '' : ` ${base}_editing`
        let err = !this.error === '' ? '' : ` ${base}_error`

        return `${base}${edit}${err}`
      },

      fieldLabel () {
        return this.isEditing ? 'Hit Enter to Submit. Esc to  cancel' : 'Click to Change'
      }
    },

    data () {
      return {
        editing: '',
        error: '',
        fields: {
          name: {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'name'
            }
          }
        },
        payload: {},
        isEditing: false
      }
    },

    methods: {
      alertDone () {
        eventBus.$emit('nameChanged', this.payload.name)
        this.error = this.checkMark('Name updated') // @mixins.js

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
        let name = e.target.innerText.trim()

        e.target.blur()
        e.preventDefault()
        this.fields.name.value = name
        this.error = ''

        if (this.hasInvalidData()) {
          return false
        }

        this.setPayload()

        ajax.post(`/api/users/${this.profile.slug}`, this.payload)
            .then((r) => { this.alertDone() })
            .catch((e) => { this.error = e.response.data['name'][0] })
      },

      cleanUp () {
        eventBus.$emit('editingField', false)
        this.fields.name.value = ''
        this.fields.name.errors = []
      },

      hasInvalidData () {
        this.fields = this.validate(this.fields) // @validation.js
        this.error = this.stringifyValidationErrors(this.fields) // @validation.js

        return this.validationHasErrors(this.fields) // @validation.js
      },

      setEdit () {
        this.isEditing = true
        eventBus.$emit('editingField', 'name')
      },

      setPayload () {
        this.payload = {
          '_method': 'PATCH',
          name: this.fields.name.value.trim()
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
