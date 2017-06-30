import editorHtmlToolbar from './_markit-html-toolbar'

export default {
  nameSpace: 'html', // Useful to prevent multi-instances CSS conflict
  preview: true,
  previewTemplatePath: '/templates/markitup/preview/preview.html',
  tabs: '    ',
  shortcuts: {
    'Shift Enter': '<br />'
  },
  toolbar: editorHtmlToolbar
}
