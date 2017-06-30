<template lang="html">
	<div class="H-Form__Item">
		<h3 v-if="info" v-html="info"/>

		<label v-if="isRequired" class="Form__Group__Label" :for="name">{{ capitalizedName }}<sup>*</sup></label>

		<label v-else class="Form__Group__Label" :for="name">{{ capitalizedName }}</label>

		<input :disabled="disabled"
		       :id="name"
		       :name="name"
		       :placeholder="capitalizedName"
		       :required="isRequired"
           class="Field"
		       type="email"
		       v-if="type==='email'"
		       v-bind:value="value"
           v-on:focus="selectAll"
		       v-on:input="updateValue($event.target.value)"
		       v-on:keyup.enter="enterPressed">

		<input :disabled="disabled"
		       :id="name"
		       :name="name"
		       :placeholder="capitalizedName"
		       :required="isRequired"
           class="Field"
		       type="text"
		       v-if="type==='text'"
		       v-bind:value="value"
           v-on:focus="selectAll"
		       v-on:input="updateValue($event.target.value)"
		       v-on:keyup.enter="enterPressed">

		<input :disabled="disabled"
		       :id="name"
		       :name="name"
		       :placeholder="capitalizedName"
		       :required="isRequired"
           class="Field"
		       type="password"
		       v-if="type==='password'"
		       v-bind:value="value"
           v-on:focus="selectAll"
		       v-on:input="updateValue($event.target.value)"
		       v-on:keyup.enter="enterPressed">
	</div>
</template>


<script type="text/ecmascript-6">
	import {
    replace,
    startCase
  } from 'lodash'

	export default {
		data () {
			return {
				capitalizedName: startCase(replace(this.name, '_', ' '))
			}
		},

		methods: {
			enterPressed (e) {
				this.$emit('keyEnter', e)
			},

			selectAll (e) {
				setTimeout(() => {
          e.target.select()
        }, 0)
			},

			updateValue (value) {
				this.$emit('input', value)
			}
		},

    name: 'FormInput',

		props: {
			disabled: {
				default: false,
				required: false,
				type: Boolean
			},
      info: {
				required: false,
				type: String,
				default: ''
			},
			isRequired: {
				required: false,
				default: true,
				type: Boolean
			},
      name: {
				required: true,
				type: String,
				default: 'email'
			},
			type: {
				required: false,
				default: 'text',
				type: String
			},
      value: {
				required: true,
				type: String,
				default: ''
			}
		}
	}
</script>
