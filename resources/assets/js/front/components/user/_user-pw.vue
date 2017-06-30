<template lang="html">
  <div class="User__Pw">
    <div class="User__Key" v-html="'Password'"/>

    <div class="User__Val">
      <button class="Button Button_default Button_small"
              type="button"
              v-html="'Change'"
              :disabled="!canEdit"
              name="change"
              @click.prevent="change"/>
    </div>

    <div class="User__Error" v-html="error"/>

    <transition name="fade">
      <chg-pw :user="profile" v-if="show && canEdit"/>
    </transition>
  </div>
</template>


<script type="text/ecmascript-6">
  import chgPw from './_chg-pw.vue'

  export default {
    beforeDestroy () {
      eventBus.$off('cancelEdit', this.setCancelEdit)
      eventBus.$off('changedPw', this.setChanged)
    },

    components: {
      chgPw
    },

    computed: {
      canEdit () {
        return !this.disabled
      }
    },

    created () {
      eventBus.$on('cancelEdit', (field) => {
        this.setCancelEdit(field)
      })

      eventBus.$on('changedPw', (status) => {
        this.setChanged(status)
      })
    },

    data () {
      return {
        error: '',
        show: false
      }
    },

    methods: {
      change () {
        this.show = !this.show
      },

      setCancelEdit (field) {
        this.show = false
        eventBus.$emit('editingField', '')
      },

      setChanged (status) {
        this.show = false
        this.error = this.checkMark('Password updated') // @vue-mixins

        setTimeout(() => {
          this.error = ''
        }, 3000)
      }
    },

    props: {
      disabled: {
        required: true,
        type: Boolean
      },
      profile: {
        required: true,
        type: Object
      }
    },

    watch: {
      'show' () {
        this.show ? eventBus.$emit('editingField', 'pw')
                  : eventBus.$emit('editingField', '')
      }
    }
  }
</script>
