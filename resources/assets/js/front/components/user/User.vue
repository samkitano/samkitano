<template lang="html">
  <div class="Authorized" v-if="authenticated">
    <h1 class="Pg-header">Profile</h1>

    <div class="User">
      <u-name :disabled="disable('name')" :profile="profile" :own="own"/>
      <u-mail :disabled="disable('email')" :profile="profile" v-if="own"/>
      <u-pass :disabled="disable('pw')" :profile="profile" v-if="own"/>
      <u-bio :disabled="disable('bio')" :profile="profile" :own="own"/>
      <u-pic :disabled="disable('bio')" :profile="profile" :own="own"/>
      <u-reg :profile="profile"/>
      <u-comm :profile="profile"/>
    </div>
  </div>

  <div class="Unauthorized" v-else>
    <h1 class="Pg-header">Profile</h1>

    <div class="User">
      <h2>Unauthorized! Please login to access this page.</h2>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  import uBio from './_user-bio.vue'
  import uComm from './_user-comments.vue'
  import uMail from './_user-email.vue'
  import uName from './_user-name.vue'
  import uPass from './_user-pw.vue'
  import uPic from './_user-avatar.vue'
  import uReg from './_user-registered.vue'

  export default {
    beforeDestroy () {
      eventBus.$off('editingField', this.setEditing)
    },

    components: {
      uBio,
      uComm,
      uMail,
      uName,
      uPass,
      uPic,
      uReg
    },

    computed: {
      authenticated () {
        return !!this.profile
      }
    },

    created () {
      eventBus.$on('editingField', (field) => {
        this.setEditing(field)
      })
    },

    data () {
      return {
        disabled: '',
        isEditing: false,
        own: JSON.parse(this.owned),
        profile: JSON.parse(this.user)
      }
    },

    methods: {
      disable (field) {
        if (this.isEditing) {
          return this.disabled !== field
        }

        return false
      },

      setEditing (field) {
        this.disabled = field
        this.isEditing = field !== ''
      }
    },

    props: {
      owned: {
        required: true,
        type: String
      },
      user: {
        required: true,
        type: [Boolean, String]
      }
    }
  }
</script>
