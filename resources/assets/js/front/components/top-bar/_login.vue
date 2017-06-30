<template lang="html">
	<section class="Authentication">
		<div class="H-Form" role="form">
			<form-head :message="formTitle"/>

			<div class="H-Form__Body">
				<form-input :disabled="resetting && (startTimer || !btnTimedout || locked)"
                    :info="infoText"
                    name="email"
                    type="email"
                    v-model="fields.email.value"/>

				<form-input @keyEnter="submitForm"
                    name="password"
                    type="password"
                    v-if="!resetting"
				            v-model="fields.password.value"/>

        <form-checkbox class="slider"
                       label="Remember"
                       v-if="!resetting"
                       v-model="remember"/>

        <form-no-spam v-model="nospam"/>

				<form-error :msg="errors" :info="formInfo"/>
			</div>

			<div class="H-Form__Footer">
				<div class="H-Form__Item H-Form__Button_group">
					<button @click.prevent="toggleForm"
                  :class="buttonStill()"
                  :disabled="submitting || sending"
                  name="resend"
                  type="button"
					        v-html="forgotText"/>

					<button @click.prevent="submitForm"
                  :class="submitClass"
                  :disabled="submitting || (resetting && !startTimer)"
					        name="button"
                  type="submit"
                  v-if="!resetting"
					        v-html="submitText"/>

          <timed-btn :startTimer="startTimer"
                     :text="submitText"
                     :working="sending"
                     name="reset"
                     v-else
                     v-on:clicked="submitForm"
                     v-on:locked="lockForm"
                     v-on:timedout="enableForm"/>
				</div>
			</div>
		</div>
	</section>
</template>


<script type="text/ecmascript-6">
  import formCheckbox from './../form-elements/_form-checkbox.vue'
	import formError from './../form-elements/_form-error.vue'
	import formHead from './../form-elements/_form-header.vue'
	import formInput from './../form-elements/_form-input.vue'
  import formNoSpam from './../form-elements/_no-spam.vue'
  import timedBtn from './../form-elements/_timed-btn.vue'

	export default {
		components: {
      formCheckbox,
      formError,
      formInput,
      formHead,
      formNoSpam,
      timedBtn
    },

		computed: {
      // text for left button
			forgotText () {
				return this.resetting ? 'Login' : 'Forgot Pw'
			},

      // form title
			formTitle () {
				return this.resetting ? 'Reset Password' : 'Login'
			},

      // form heading
			infoText () {
				return this.resetting ? 'Enter your email' : 'Enter your credentials'
			},

      // class for right button
			submitClass () {
				if (this.resetting) {
          return this.sending ? this.buttonPulse() : this.buttonStill()
        }

				return this.submitting ? this.buttonPulse() : this.buttonStill()
			},

      // text for right button
			submitText () {
        if (this.resetting) {
          return this.sending ? 'Working' : 'Reset'
        }

        return this.submitting ? 'Working' : 'Login'
			}
		},

		data () {
			return {
        btnTimedout: true,
        errors: '',
				fields: {
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
          }
				},
        formInfo: '',
        locked: false,
        nospam: '',
				payload: {},
				remember: false,
				resetting: false,
				sending: false,
        startTimer: false,
				submitting: false
			}
		},

		methods: {
      // login succesful
      authorize (user) {
        this.finishProcessing()
        this.$swal({
          title: 'You are in!',
          text: `Welcome back, ${user.name}`,
          type: 'success',
          timer: 3000
        }).then(
          () => { location.reload() },
          (dismiss) => { location.reload() }
        )
      },

      // enable/disable form
      enableForm (e) {
        this.btnTimedout = e
        if (e) {
          this.startTimer = false
        }
      },

      // output form erros
			displayErrors () {
        this.finishProcessing()
				this.errors = this.processValidationErrors(this.fields) // @validation.js
				this.focusFirstError() // @mixins.js
			},

      // stop 'Working'
      finishProcessing () {
        this.submitting = false
        this.sending = false
        this.formInfo = 'Request completed!'
      },

      lockForm (state) {
        if (state) {
          this.locked = true
          this.formInfo = 'Sorry, this form is currently locked. Please wait..'
        } else {
          this.locked = false
          this.formInfo = ''
        }
      },

			// POST Login data
			postLogin () {
        this.formInfo = 'Sending request...'
        this.errors = ''
        this.submitting = true
				this.setPayload()

        ajax.post('/login', this.payload)
					  .then((r) => { this.authorize(r.data.user) })
			      .catch((e) => { this.processResponseErrors(e) })
			},

			// POST reset data
			postReset () {
        this.formInfo = 'Sending request...'
        this.errors = ''
				this.sending = true
				this.setPayload()

				ajax.post('/password/email', this.payload)
						.then((r) => { this.processReset(r) })
						.catch((e) => { this.processResponseErrors(e) })
			},

      // Reset password link has been sent
      processReset (r) {
        this.finishProcessing()
        this.sending = false
        this.startTimer = true
        this.formInfo = ''

        this.$swal({
          title: `Check your Inbox`,
          text: r.data.email + this.payload.email,
          type: 'info'
        })
      },

			// Process errors returned by API server
			processResponseErrors (e) {
        this.formInfo = ''
        this.errors = ''

				if (this.isErrorBag(e)) { // @mixins.js
					let errors = e.response.data

          for (let field in errors) {
						if (this.fields[field]) {
							this.addErrorClass(field)
							this.fields[field].errors.push(errors[field])
						}
					}

					this.displayErrors()

					return false
				}

				this.errors = e.response.data ? e.response.data.message : e.message

        if (this.errors === 'Already Authenticated. Page will be refreshed...') {
          setTimeout(() => {
            location.reload()
          }, 3000)

          return false
        }

				this.finishProcessing()
				this.focusFirstInput() // @mixins.js
			},

			// reset form
			resetForm () {
				this.errors = ''
        this.finishProcessing()
				this.setFields()
				this.removeErrorClasses() // @mixins.js
				this.focusFirstInput() // @mixins.js
			},

			// Clear form errors
			resetErrors () {
				for (let f in this.fields) {
					this.fields[f].errors = []
				}

				this.errors = ''
        this.formInfo = ''
				this.removeErrorClasses() // @mixins.js
			},

			// Toggle form mode
			toggleForm () {
				this.resetting = !this.resetting

        if (!this.resetting) {
          this.formInfo = ''
        }
			},

			// Set up fields to process
			setFields () {
				if (this.resetting) {
					delete this.fields['password']
				} else {
          this.fields.password = {
            errors: [],
						value: '',
						validation: {
							required: true,
							type: 'password'
						}
					}
				}
			},

			// Compose API request payload
			setPayload () {
				let email = this.sanitizeEmail(this.fields.email.value)

        if (this.resetting) {
          this.payload = { email: email }
        } else {
          this.payload = {
            email: email,
            password: this.fields.password.value
          }
          if (this.remember) {
            this.payload.remember = true
          }
        }
			},

			// Start the submission process
			submitForm (e) {
				if (this.nospam !== '' || this.sending || this.submitting) {
          return false
        }

				this.resetErrors()
				this.fields = this.validate(this.fields) // @validation.js

				if (this.validationHasErrors(this.fields)) { // @validation.js
					this.displayErrors()
					return false
				}

				this.resetting ? this.postReset() : this.postLogin()
			}
    },

		mounted () {
			this.resetForm()
      this.formInfo = ''
		},

		watch: {
			'resetting' () {
				this.resetForm()
			}
		}
	}
</script>
