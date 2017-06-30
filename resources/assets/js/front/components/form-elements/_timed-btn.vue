<template lang="html">
  <button @click.prevent="process"
          :class="btnClass"
          :disabled="disabled"
          :name="name"
          type="submit"
          v-html="btnText"/>
</template>


<script type="text/ecmascript-6">
    export default {
      computed: {
        btnClass () {
          return this.working ? this.buttonPulse() : this.buttonStill() // @mixins.js
        },

        btnText () {
          if (this.working) {
            return 'Working'
          }

          if (!this.secsRemaining) {
            return this.defaultText
          }

          setTimeout(() => {
            this.secsRemaining--
          }, 1000)

          if (this.secsRemaining < 0) {
            this.finish()
            return this.defaultText
          }

          this.setLocal(this.localRefreshName, new Date())

          return this.secsRemaining
        },

        disabled () {
          return this.secsRemaining
        },

        localRefreshName () {
          return `refresh_${this.name}`
        },

        localResetName () {
          return `reset_${this.name}`
        }
      },

      created () {
        this.start()
      },

      data () {
        return {
          defaultText: this.text,
          secsRemaining: false
        }
      },

      methods: {
        finish () {
          this.unsetLocal(this.localResetName)
          this.unsetLocal(this.localRefreshName)
          this.secsRemaining = false
          this.$emit('timedout', true)
          this.$emit('locked', false)
        },

        process () {
          this.$emit('clicked', this.name)
          this.$emit('timedout', false)
        },

        start () {
          // nothing to do
          if (!this.startTimer && !this.getLocal(this.localResetName)) {
            return false
          }

          // we have a timer going on
          if (this.getLocal(this.localResetName)) {
            let seconds = this.secondsSince(this.getLocal(this.localResetName))
            let refreshSeconds = this.secondsSince(this.getLocal(this.localRefreshName))

            // timer expired
            if (seconds > this.timeout) {
              this.finish()
              this.$emit('locked', false)
              return false
            }

            // timer continues
            this.setLocal(this.localRefreshName, new Date())
            this.secsRemaining = this.timeout - (seconds + refreshSeconds)
            this.$emit('timedout', false)
            this.$emit('locked', true)
          } else {
            // start a new timer
            let d = new Date()
            this.setLocal(this.localResetName, d)
            this.setLocal(this.localRefreshName, d)
            this.secsRemaining = this.timeout
          }
        }
      },

      props: {
        name: {
          required: true,
          type: String
        },
        startTimer: {
          required: true,
          type: Boolean
        },
        text: {
          required: true,
          type: String
        },
        timeout: {
          default: 60,
          required: false,
          type: Number
        },
        working: {
          required: true,
          type: Boolean
        }
      },

      watch: {
        'secsRemaining' () {
          if (!this.secsRemaining && this.getLocal(this.localResetName)) {
            this.finish()
          }
        },

        'startTimer' () {
          if (this.startTimer) {
            this.start()
          }
        }
      }
    }
</script>
