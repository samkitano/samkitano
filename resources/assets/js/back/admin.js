/**
 * Ajax XCSRF header setup
 */
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})

/**
 * Init sweetalert 2
 */
import swal from 'sweetalert2'


/**
 * Init bootstrap dropdown
 */
$('.dropdown-toggle').dropdown()


/**
 * Markitup editor
 */
import MarkItUp from 'markitup'
import mkHtmlConfig from './_markit-html-config'
import mkMdConfig from './_markit-md-config'

const markitupHtml = new MarkItUp('textarea.html', mkHtmlConfig)
const markitupMark = new MarkItUp('textarea.markdown', mkMdConfig)


/**
 * Refresh preview on Editor
 */
$('.markitup-icon-preview').on('click', function (e) {
  e.preventDefault()

  let $mk = $('body').find('textarea')

  if ($mk.hasClass('html')) {
    markitupHtml.refreshPreview()
  }

  if ($mk.hasClass('markdown')) {
    markitupMark.refreshPreview()
  }
})

/**
 * Pictures manager dialog
 */
import picDialog from './_pic-dialog-manager'

$('.markitup-icon-picture').on('click', function (e) {
  picDialog.init()
})


/**
 * Init Select2
 */
$(".select-tags").select2({
  placeholder: "Select a tag",
  allowClear: true
})

$(".select-album").select2({
  placeholder: "Select an album",
  allowClear: true
})


/**
 * Prevent actions on 'no-action' anchors
 */
$('a.no-action').on('click', function (e) {
  e.preventDefault()
})


/**
 * Menu Clear Cache - top nav
 */
$('#clear_cache').on('click', function (e) {
  $.post("admin/clear-cache", { "_method": "POST" })
   .done(function (data) {
        swal({
          title: 'Info',
          type: 'info',
          text: data.message,
          timer: 4000
        }).catch(swal.noop)
   })

  e.preventDefault()
})
