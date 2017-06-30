<template lang="html">
  <section class="Authentication">
    <div class="H-Form" role="form">
      <form-head message="Confirm Registration"/>

      <div class="H-Form__Body">
        <p v-html="infoText"></p>

        <form-error :msg="errors" :info="formInfo"/>
      </div>

      <div class="H-Form__Footer">
        <div class="H-Form__Item H-Form__Button">
          <button @click.prevent="resendLink"
                  :class="resendClass"
                  :disabled="resending"
                  name="resend"
                  type="button"
                  v-html="resendText"
                  v-if="canResend"
          />
        </div>
      </div>
    </div>
  </section>
</template>


<script type="text/ecmascript-6">
import formError from './../form-elements/_form-error.vue'
import formHead from './../form-elements/_form-header.vue'
import formInput from './../form-elements/_form-input.vue'
import {
  assign
} from 'lodash'

export default {
  components: {
    formError,
    formHead,
    formInput
  },

  computed: {
    canResend () {
      return this.user.attempts < 3
    },

    infoText () {
      return this.user.attempts < 3 ? this.defaultInfoText : this.blownInfoText
    },

    resendClass () {
      return this.resending ? this.buttonPulse() : this.buttonStill() // @mixins.js
    },

    resendText () {
      return this.resending ? 'Working' : 'Resend Confirmation Email'
    }
  },

  data () {
    return {
      blownInfoText: 'Try checking your Spam Folder as well, please. Already sent 3 emails!',
      defaultInfoText: `Please, check your Inbox for <strong>${this.user.email}</strong> and follow provided instructions.`,
      errors: '',
      formInfo: '',
      resending: false
    }
  },

  methods: {
    incrementAttempts () {
      let currentAttempts = this.user.attempts

      this.user.attempts = currentAttempts++
      this.setLocal('registering', this.user)
    },

    processAttempts (e) {
      let attmpt = e.response.data.count

      if (attmpt) {
        this.setAttempts(attmpt)
        this.errors = e.response.data.message
      } else {
        if (this.isErrorBag(e)) {
          this.errors = `Can not process request for ${this.user.email}`
        } else {
          this.errors = e.response ? e.response.data.message : e.message
        }

        // failsafe: if for some reason store didn't record attempts. API will keep track
        if (e.response.data.error === 'http_unauthorized') {
          this.setAttempts(3)
        }
      }

      this.resending = false
      this.formInfo = ''
    },

    // Send new Link to registered user
    resendLink () {
      this.resending = true
      this.errors = ''
      this.formInfo = 'Sending request...'
      this.removeErrorClasses() // @mixins.js
      this.incrementAttempts()

      ajax.post('/resend', { email: this.user.email })
          .then((r) => { this.showResends(r.data.count) })
          .catch((e) => { this.processAttempts(e) })
    },

    setAttempts (n) {
      this.user.attempts = n
      this.setLocal('registering', this.user)
    },

    // Record attempts
    showResends (attempts) {
      this.setAttempts(attempts)

      let email = this.user.email
      let sfxd = this.ordinalize(this.user.attempts) // @mixins.js

      this.resending = false
      this.formInfo = 'Request completed!'

      this.$swal({
        title: 'Sent!',
        text: `A ${sfxd} Activation email was sent.`,
        type: 'info'
      }).then(() => {
        this.formInfo = ''
      }, (dismiss) => {
        this.formInfo = ''
      })
    },
  },

  props: {
    user: {
      required: true,
      type: Object
    }
  }
}
</script>
