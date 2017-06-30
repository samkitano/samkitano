<template lang="html">
  <div class="Contact-Form">
    <div class="H-Form" role="form">
      <form-head message="Speak your mind!"/>

      <div class="H-Form__Body">
        <form-input v-if="!hasUser"
                   v-model="fields.name.value"
                   name="name"
                   :disabled="submitting || locked || (startTimer && !btnTimedout)"/>

        <form-input v-if="!hasUser"
                   type="email"
                   v-model="fields.email.value"
                   name="email"
                   :disabled="submitting || locked || (startTimer && !btnTimedout)"/>

        <div class="H-Form__Item">
          <label for="y_message">Your Message*</label>

          <textarea name="body"
                    id="body"
                    cols="30"
                    rows="10"
                    class="Field"
                    v-model="fields.body.value"
                    :disabled="submitting || locked || (startTimer && !btnTimedout)"
         	></textarea>
        </div>

        <form-no-spam v-model="nospam"/>

        <form-error :msg="errors" :info="formInfo"/>
      </div>

      <div class="H-Form__Footer">
        <div class="H-Form__Item H-Form__Button">
          <timed-btn :startTimer="startTimer"
                     text="Send"
                     :working="submitting"
                     name="send"
                     v-on:clicked="submitForm"
                     v-on:locked="lockForm"
                     v-on:timedout="enableFields"/>
        </div>
      </div>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  import formError from './../form-elements/_form-error.vue'
  import formHead from './../form-elements/_form-header.vue'
  import formInput from './../form-elements/_form-input.vue'
  import formNoSpam from './../form-elements/_no-spam.vue'
  import moment from 'moment'
  import timedBtn from './../form-elements/_timed-btn.vue'
  import {
    head
  } from 'lodash'

  export default {
    components: {
      formError,
      formInput,
      formHead,
      formNoSpam,
      timedBtn
    },

    computed: {
      hasUser () {
        return this.userIsAuthed(this.storedUser)
      },

      storedUser () {
        return this.user ? JSON.parse(this.user).user : false
      }
    },

    data () {
      return {
        errors: '',
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
          body: {
            errors: [],
            value: '',
            validation: {
              required: true,
              type: 'none'
            }
          }
        },
        formInfo: '',
        locked: false,
        nospam: '',
        payload: {},
        startTimer: false,
        submitting: false,
        btnTimedout: true
      }
    },

    methods: {
      enableFields (e) {
        this.btnTimedout = e
        if (e) {
          this.startTimer = false
          this.formInfo = ''
        }
      },

      // Show validation errors
      displayErrors () {
        this.errors = this.processValidationErrors(this.fields) // @validation.js
        this.submitting = false
        this.startTimer = false
        this.formInfo = ''
        this.focusFirstError() // @mixins.js
      },

      // Finish submission, unfreeze form
      finish () {
        this.submitting = false
        this.startTimer = false
        this.formInfo = ''
      },

      // Prevent re-submission & Inform user.
      freezeForm (isBot = false) {
        this.submitting = false
        this.startTimer = true

        let name = this.hasUser ? this.storedUser.name : this.fields.name.value
        let msg = `Thanks, ${name}. Your message has been sent.
                  Expect a confirmation email. This form will be disabled for 60 seconds.`

        this.resetForm()
        this.formInfo = isBot ? 'Thanks, Mr. Robot. Your message has been ignored just fine.' : msg
      },

      lockForm (state) {
        if (state) {
          this.locked = true
          this.formInfo = 'Sorry, this form is currently locked. Please wait..'
        } else {
          this.locked = false
        }
      },

      // POST to API
      postData () {
        ajax.post("/api/contact", this.payload)
            .then((r) => { this.freezeForm() })
            .catch((e) => { this.processResponseErrors(e) })
      },

      // Process errors returned by API
      processResponseErrors (e) {
        let isErrorBag = e.response.status === 422 // check error is validation

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
        this.startTimer = false
        this.focusFirstInput() // @mixins.js
      },

      // Clear all errors
      resetErrors () {
        for (let f in this.fields) {
          this.fields[f].errors = []
        }

        this.removeErrorClasses() // @mixins.js
        this.errors = ''
      },

      // reset form elements to empty string
      resetForm () {
        this.fields.name.value = ''
        this.fields.email.value = ''
        this.fields.body.value = ''
      },

      // Compose API request payload
      setPayload () {
        this.payload = {
          body: this.fields.body.value,
          email: this.hasUser ? this.storedUser.email : this.fields.email.value.trim(),
          isuser: this.hasUser ? this.storedUser.slug : null,
          name: this.hasUser ? this.storedUser.name : this.fields.name.value.trim()
        }
      },

      // Start the submission process
      submitForm () {
        if (this.nospam !== '' || this.isFreezed || this.submitting) {
          this.freezeForm(true)

          return false
        }

        this.resetErrors() // Clear previous form errors

        this.submitting = true
        this.formInfo = 'Sending request...'
        this.fields = this.validate(this.fields) // @validation.js

        if (this.validationHasErrors(this.fields)) { // @validation.js
          this.displayErrors()
          return false
        }

        this.setPayload() // Prepare data to submit
        this.postData()
      }
    },

    mounted () {
      this.focusFirstInput() // @mixins.js
    },

    props: {
      user: {
        required: true,
        type: String
      }
    }
  }
</script>
