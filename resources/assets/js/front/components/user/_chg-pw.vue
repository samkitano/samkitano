<template lang="html">
  <div class="Change-pw">
    <div class="H-Form" role="form">
      <form-head message="Change Password"/>

      <div class="H-Form__Body">
        <form-input v-model="fields.old_password.value"
                    type="password"
                    name="old_password"/>

        <form-input v-model="fields.password.value"
                    type="password"
                    name="password"/>

        <form-input v-model="fields.password_confirmation.value"
                    type="password"
                    name="password_confirmation"
                    @keyEnter="submitForm"/>

        <form-error :msg="errors" :info="formInfo"/>
      </div>

      <div class="H-Form__Footer">
        <div class="H-Form__Item H-Form__Button H-Form__Button_group">
          <button type="button"
                  name="cancel"
                  class="Button Button_green"
                  v-html="'Cancel'"
                  :disabled="submitting"
                  @click.prevent="cancel"/>

          <button type="submit"
                  name="button"
                  v-html="buttonText"
                  :class="buttonClass"
                  :disabled="submitting"
                  @click.prevent="submitForm"/>
        </div>
      </div>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  import formError from './../form-elements/_form-error.vue'
  import formHead from './../form-elements/_form-header.vue'
  import formInput from './../form-elements/_form-input.vue'
  import {
    head
  } from 'lodash'

  export default {
    components: {
      formError,
      formHead,
      formInput
    },

    computed: {
      buttonText () {
        return this.submitting ? 'Working' : 'Change'
      },

      buttonClass () {
        return this.submitting ? this.buttonPulse() : this.buttonStill() // @mixins.js
      }
    },

    data () {
      return {
        fields: {
          'old_password': {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'password'
            }
          },
          password: {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'password'
            }
          },
          'password_confirmation': {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'password',
              match: 'password'
            }
          }
        },
        errors: '',
        formInfo: '',
        payload: {},
        submitting: false
      }
    },

    methods: {
      // Finish changes
      alertFinished (r) {
        this.submitting = false
        this.formInfo = 'Request completed.'
        eventBus.$emit('changedPw', true)
      },

      // Close Form
      cancel () {
        eventBus.$emit('cancelEdit', 'pw')
      },

      // Show validation errors
      displayErrors () {
        this.errors = this.processValidationErrors(this.fields) // @validation.js
        this.submitting = false
        this.formInfo = ''
        this.focusFirstError() // @mixins.js
      },

      // Process errors returned by API server
      processResponseErrors (e) {
        // check error is validation
        let isErrorBag = e.response !== undefined && e.response.status === 422

        if (isErrorBag) {
          let errors = e.response.data

          for (let field in errors) {
            if (this.fields[field]) {
              this.addErrorClass(field)
              this.fields[field].errors.push(head(errors[field]))
            }
          }

          this.displayErrors()

          return false
        }

        this.errors = e.response.data ? e.response.data.message : e.message
        this.submitting = false
        this.focusFirstInput() // @mixins.js
      },

      // Clear form errors
      resetErrors () {
        for (let f in this.fields) {
          this.fields[f].errors = []
        }

        this.errors = ''
        this.removeErrorClasses() // @mixins.js
      },

      // Compose API request payload
      setPayload () {
        this.payload = {
          '_method': 'PATCH',
          'old_password': this.fields.old_password.value,
          'password': this.fields.password.value,
          'password_confirmation': this.fields.password_confirmation.value
        }
      },

      // Start the submission process
      submitForm () {
        // prevent multiple submissions
        if (this.submitting) {
          return false
        }

        this.resetErrors() // Clear the previous form errors
        this.submitting = true
        this.fields = this.validate(this.fields) // @validation.js

        if (this.validationHasErrors(this.fields)) { // @validation.js
          this.displayErrors()
          return false
        }

        this.setPayload() // prepare data for submission
        this.formInfo = 'Sending request...'

        // POST form
        ajax.post(`/api/users/${this.user.slug}`, this.payload)
            .then((r) => { this.alertFinished() })
            .catch((e) => { this.processResponseErrors(e) })
      }
    },

    mounted () {
      this.focusFirstInput() // @mixins.js
    },

    props: {
      user: {
        required: true,
        type: Object
      }
    }
  }
</script>
