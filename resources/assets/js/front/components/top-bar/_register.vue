<template lang="html">
  <section class="Authentication">
    <div class="H-Form" role="form">
      <form-head message="Register"/>

      <div class="H-Form__Body">
        <form-input name="name"
                    v-model="fields.name.value"/>

        <form-input name="email"
                    type="email"
                    v-model="fields.email.value"/>

        <form-input @keyEnter="submitForm"
                    name="password"
                    type="password"
                    v-model="fields.password.value"/>

        <form-input @keyEnter="submitForm"
                    name="password_confirmation"
                    type="password"
                    v-model="fields.password_confirmation.value"/>

        <form-no-spam v-model="nospam"/>

        <form-error :msg="errors" :info="formInfo"/>
      </div>

      <div class="H-Form__Footer">
        <div class="H-Form__Item H-Form__Button">
          <button type="submit"
                  name="button"
                  v-html="buttonText"
                  :class="submitClass"
                  :disabled="submitting"
                  @click.prevent="submitForm"/>
        </div>
      </div>
    </div>
  </section>
</template>


<script type="text/ecmascript-6">
  import formError from './../form-elements/_form-error.vue'
	import formHead from './../form-elements/_form-header.vue'
	import formInput from './../form-elements/_form-input.vue'
  import formNoSpam from './../form-elements/_no-spam.vue'
  import {
    head
  } from 'lodash'

  export default {
    components: {
      formError,
      formInput,
      formHead,
      formNoSpam
    },

    computed: {
      // submit button text
      buttonText () {
        return this.submitting ? 'Working' : 'Register'
      },

      // submit button class
      submitClass () {
        return this.submitting ? this.buttonPulse() : this.buttonStill()
      }
    },

    data () {
      return {
        fields: {
          name: {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'name'
            }
          },
          email: {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'email'
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
        nospam: '',
        payload: false,
        submitting: false
      }
    },

    methods: {
      // Show validation errors
      displayErrors () {
        this.errors = this.processValidationErrors(this.fields) // @validation.js
        this.submitting = false
        this.focusFirstError() // @mixins.js
      },

      // Process errors returned by API
      processResponseErrors (e) {
        this.formInfo = ''
        
        if (this.isErrorBag(e)) { // @mixins.js
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

      // reset form
      resetForm () {
        this.submitting = false
        this.errors = ''
        this.removeErrorClasses() // @mixins.js
        this.focusFirstInput() // @mixins.js
      },

      // Clear all errors
      resetErrors () {
        for (let f in this.fields) {
          this.fields[f].errors = []
        }

        this.errors = ''
        this.formInfo = ''
        this.removeErrorClasses() // @mixins.js
      },

      // save registration to local storage
      // will be used later for confirmation
      saveRegistration () {
        this.setLocal('registering', {
          name: this.payload.name,
          email: this.payload.email,
          attempts: 1
        })

        this.submitting = false
        this.formInfo = 'Request completed!'

        this.$swal({
          title: 'Check your inbox',
          text: 'To activate your account.',
          type: 'success'
        }).then(
          () => { location.reload() },
          (dismiss) => { location.reload() }
        )
      },

      // Compose API request payload
      setPayload () {
        this.payload = {
          name: this.fields.name.value.trim(),
          email: this.sanitizeEmail(this.fields.email.value), // @mixins.js
          password: this.fields.password.value,
          'password_confirmation': this.fields.password_confirmation.value
        }
      },

      // Start the submission process
      submitForm (e) {
        if (this.nospam !== '' || this.submitting) {
          return false
        }

        if (e.target.id === 'password') {
          // focus #password_confirmation if key.enter in #password
          this.focus('password_confirmation')
          return false
        }

        this.resetErrors()
        this.submitting = true
        this.formInfo = 'Sending request...'
        this.fields = this.validate(this.fields) // @validation.js

        if (this.validationHasErrors(this.fields)) { // @validation.js
          this.displayErrors()
          return false
        }

        this.setPayload() // Compose API request payload

        // Submit
        ajax.post('/register', this.payload)
            .then((r) => { this.saveRegistration() })
            .catch((e) => { this.processResponseErrors(e) })
      }
    },

    mounted () {
      this.resetForm()
    }
  }
</script>
