<template lang="html">
	<div class="H-Form__Item__error">
    <transition name="slide" v-if="msg.length">
  		<span class="Form-error" v-html="msg"></span>
    </transition>

    <transition name="slide" v-if="info.length">
  		<span class="Form-info" v-html="info"></span>
    </transition>
	</div>
</template>


<script type="text/ecmascript-6">
	export default {
		name: 'FormError',

		props: {
			msg: {
        default: '',
				required: false,
				type: String
			},
      info: {
        default: '',
				required: false,
				type: String
      }
		}
	}
</script>
