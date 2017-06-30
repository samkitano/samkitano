<template lang="html">
	<div class="App-nav__Wrapper__Right_auth">
		<a @click.prevent="toggleConfirm"
		   :class="classifyRegister"
       href="#"
       title="Confirm Registration"
		   v-if="isConfirm"
		>Confirm {{ user.email }}</a>

		<a @click.prevent="showRegister"
		   :class="classifyRegister"
       href="#"
       title="Register"
		   v-if="!user.active && !isConfirm"
		>Register</a>

		<a @click.prevent="showLogin"
		   :class="classifyLogin"
       href="#"
       title="Login"
		   v-if="!user.active && !isConfirm"
		>Login</a>

		<transition name="fade">
			<login v-if="isAuthing && isLogin" />
		</transition>

		<transition name="fade">
			<register v-if="isAuthing && isRegistration" />
		</transition>

		<transition name="fade">
			<confirm :user="user" v-if="isConfirm && showConfirm" />
		</transition>
	</div>
</template>


<script type="text/ecmascript-6">
	import confirm from './_confirm.vue'
	import login from './_login.vue'
	import register from './_register.vue'

	export default {
		components: {
      confirm,
      login,
      register
    },

		computed: {
      classifyLogin () {
        let activeClass = this.isLogin ? ' active' : ''

        return `App-nav__Wrapper__Right__button${activeClass} Auth-nav`
      },

      classifyRegister () {
        let activeClass = this.isRegistration ? ' active' : ''

        return `App-nav__Wrapper__Right__button${activeClass}`
      },

			isLogin () {
				return this.mode === 'login' && !this.user.active
			},

			isRegistration () {
				return this.mode === 'register' && !this.user.active
			}
		},

		data () {
			return {
				isAuthing: false,
				isConfirm: this.registering,
				mode: false,
        showConfirm: true
			}
		},

		methods: {
			setEvent (evt) {
				eventBus.$emit(evt, true)
			},

			toggleConfirm () {
				this.showConfirm = !this.showConfirm
			},

			showLogin () {
				this.mode = this.isLogin ? false : 'login'
			},

			showRegister () {
				this.mode = this.isRegistration ? false : 'register'
			},

			unsetEvent (evt) {
				eventBus.$emit(evt, false)
			}
		},

		props: {
      registering: {
        default: false,
        required: false,
        type: Boolean
      },
			user: {
				required: true,
				type: Object
			}
		},

		watch: {
			'mode' () {
        this.isAuthing = this.mode === 'login' ? this.isLogin : this.isRegistration
        this.mode !== false ? this.setEvent('blur') : this.unsetEvent('blur')
			}
		}
	}
</script>
