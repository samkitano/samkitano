<template lang="html">
  <div class="User__Avatar">
    <div class="User__Key" v-html="'Avatar'"/>

    <div class="User__Val" v-if="own">
      <div class="Editable Editable_clickable hint--right"
           :aria-label="label"
           v-if="profile.avatar"
           @click="change"
      ><img :src="profile.avatar" :alt="profile.name"></div>

      <div class="Editable Editable_clickable hint--right"
           :aria-label="label"
           role="link"
           v-else
           @click="change">
         <avatar/>
     </div>
    </div>

    <div class="User__Val" v-else>
      <avatar/>
    </div>

    <div class="User__Error" v-html="error"></div>
  </div>
</template>


<script type="text/ecmascript-6">
  import avatar from '../../components/_icons/_avatar.vue'

  export default {
    components: {
      avatar
    },

    computed: {
      label () {
        return 'Click to update'
      }
    },

    data () {
      return {
        error: ''
      }
    },

    methods: {
      change () {
        if (this.disabled) {
          return false
        }

        this.$swal({
          confirmButtonText: 'Update',
          html: 'Create/Update your <a target="_blank" href="http://gravatar.com/">Gravatar</a>' +
          ' account, and hit <strong>Update</strong>',
          showCancelButton: true,
          title: 'Change Avatar',
          type: 'info'
        }).then(
          () => { this.update() },
          (dismiss) => {}
        )
      },

      processResponseErrors (e) {
        if (e.response) {
          this.error = e.response.data ? e.response.data.message : e.message
        }
      },

      processUpdate (r) {
        if (r.data.hasOwnProperty('avatar')) {
          let avatar = r.data.avatar

          if (avatar && avatar !== this.profile.avatar) {
            this.setAvatar(avatar) // @vuex mutation
            this.saveUser() // @vuex action
            this.error = this.checkMark('Avatar updated') // @mixins.js

            setTimeout(() => {
              this.error = ''
            }, 3000)
          } else {
            this.error = this.errorMark('Nothing updated') // @mixins.js

            setTimeout(() => {
              this.error = ''
            }, 3000)
          }
        }
      },

      update () {
        let payload = {
          '_method': 'PATCH',
          avatar: 'gravatar'
        }

        ajax.post(`/api/users/${this.profile.slug}`, payload)
            .then((r) => { this.processUpdate(r) })
            .catch((e) => { this.processResponseErrors(e) })
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
