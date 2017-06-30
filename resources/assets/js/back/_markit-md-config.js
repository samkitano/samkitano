import mdToolbar from './_markit-md-toolbar.js'
import marked from 'marked'
import hljs from 'highlight.js'

marked.setOptions({
  highlight: function (code) {
    return hljs.highlightAuto(code).value
  }
})

export default {
  beforePreviewRefresh: function (content, callback) {
    callback(refreshPreview(content))
  },
  nameSpace: 'markdown',
  preview: true,
  previewRefreshOn: [ 'markitup.insertion', 'keyup' ],
  shortcuts: {
    'Ctrl Shift R': function (e) {
      this.refreshPreview()
      e.preventDefault()
    }
  },
  tabs: '    ',
  toolbar: mdToolbar
}

function refreshPreview(content) {
  let markDown = marked(content, { sanitize: true })

  $('.markdown-preview').html(markDown)

  return markDown
}
