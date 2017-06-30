<template lang="html">
  <span class="No__s_p_a_m__please">Do not phill this phield
    <input name="url"
           type="text"
           v-on:input="updateValue($event.target.value)"></span
  >
</template>


<script type="text/ecmascript-6">
  export default {
    methods: {
      updateValue (value) {
        this.$emit('input', value)
      }
    },

    name: 'NoSpam',
  }
</script>
