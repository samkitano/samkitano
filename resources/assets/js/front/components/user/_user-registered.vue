<template lang="html">
  <div class="User__Registered">
    <div class="User__Key">Registered</div>

    <div class="User__Val">
      {{ profile.created | dateFormat }} ({{ profile.created | fromNow }})
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    props: {
      profile: {
        required: true,
        type: Object
      }
    }
  }
</script>
