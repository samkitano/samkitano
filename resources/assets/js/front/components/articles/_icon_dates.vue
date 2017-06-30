<template lang="html">
  <div class="Article__Footer__Icon Article__Footer__Icon_dates">
    <div class="Svg">
  		<svg aria-label="Published"
           class="Svg__Icon"
           height="16"
           role="img"
           viewBox="0 0 60 60"
  		     width="16"
  		>
        <path d="M57 4h-7V1c0-.553-.447-1-1-1h-7c-.553 0-1 .447-1 1v3H19V1c0-.553-.447-1-1-1h-7c-.553 0-1 .447-1
        1v3H3c-.553 0-1 .447-1 1v54c0 .553.447 1 1 1h54c.553 0 1-.447 1-1V5c0-.553-.447-1-1-1zM43 2h5v6h-5V2zM12
        2h5v6h-5V2zM4 6h6v3c0 .553.447 1 1 1h7c.553 0 1-.447 1-1V6h22v3c0 .553.447 1 1 1h7c.553 0 1-.447
        1-1V6h6v9H4V6zm0 52V17h52v41H4z"/>
        <path d="M38 23H11v29h38V23H38zm-7 2h7v7h-7v-7zm7
        16h-7v-7h7v7zm-16-7h7v7h-7v-7zm0-9h7v7h-7v-7zm-9 0h7v7h-7v-7zm0 9h7v7h-7v-7zm7 16h-7v-7h7v7zm9 0h-7v-7h7v7zm9
        0h-7v-7h7v7zm9 0h-7v-7h7v7zm0-9h-7v-7h7v7zm0-16v7h-7v-7h7z"/>

        <title>Published</title>
      </svg>

      <time pubdate :datetime="article.created">&nbsp;{{ article.created | dateFormat }}</time>

      <span class="Article__Dates__Created_humanized" v-if="humanized">
          &nbsp;({{ article.created | fromNow }})
      </span>
    </div>

    <div class="Article__Dates__Updated" v-if="article.updated && article.updated !== article.created">
        &nbsp;Updated: {{ article.updated | dateFormat }}

        <span class="Article__Dates__Updated_humanized" v-if="humanized">
            ({{ article.updated | fromNow }})
        </span>
    </div>
  </div>
</template>


<script type="text/ecmascript-6">
  export default {
    props: {
      article: {
        required: true,
        type: Object
      },
      humanized: {
        default: false,
        type: Boolean
      }
    }
  }
</script>
