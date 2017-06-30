const { mix } = require('laravel-mix')

const paths = {
  admin: "resources/assets/js/back/admin.js",
  bsDtPicker: "./node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js",
  holderjs: "./node_modules/holderjs/holder.js",
  jasny: "./node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.js",
  moment: "./node_modules/moment/moment.js",

  dt_jszip: "resources/assets/js/back/vendor/datatables/JSZip-3.1.3/jszip.js",
  dt_pdf: "resources/assets/js/back/vendor/datatables/pdfmake-0.1.27/build/pdfmake.js",
  dt_pdf_fts: "resources/assets/js/back/vendor/datatables/pdfmake-0.1.27/build/vfs_fonts.js",
  dt_dt: "resources/assets/js/back/vendor/datatables/DataTables-1.10.15/js/jquery.dataTables.js",
  dt_bs: "resources/assets/js/back/vendor/datatables/DataTables-1.10.15/js/dataTables.bootstrap.js",
  dt_btns: "resources/assets/js/back/vendor/datatables/Buttons-1.3.1/js/dataTables.buttons.js",
  dt_btns_bs: "resources/assets/js/back/vendor/datatables/Buttons-1.3.1/js/buttons.bootstrap.js",
  dt_colvis: "resources/assets/js/back/vendor/datatables/Buttons-1.3.1/js/buttons.colVis.js",
  dt_html5: "resources/assets/js/back/vendor/datatables/Buttons-1.3.1/js/buttons.html5.js",
  dt_prnt: "resources/assets/js/back/vendor/datatables/Buttons-1.3.1/js/buttons.print.js",
  dt_col_rdr: "resources/assets/js/back/vendor/datatables/ColReorder-1.3.3/js/dataTables.colReorder.js",
  dt_rspsv: "resources/assets/js/back/vendor/datatables/Responsive-2.1.1/js/dataTables.responsive.js",
  dt_rspsv_bs: "resources/assets/js/back/vendor/datatables/Responsive-2.1.1/js/responsive.bootstrap.js",
  dt_defs: "resources/assets/js/back/dt-defs.js"
}

mix.sass("resources/assets/sass/back/admin.sass", "public/css/admin.css")
   .sass('resources/assets/sass/back/markitup-iframe.sass', 'public/css/markitup-iframe.css')
   .sass('resources/assets/sass/front/app.sass', 'public/css/app.css')

   .js("resources/assets/js/back/admin-bootstrap.js", "public/js/admin-bootstrap.js")

   .js('resources/assets/js/front/app.js', 'public/js/app.js')
   .js('resources/assets/js/front/nav.js', 'public/js/front-nav.js')
   .js('resources/assets/js/front/articles.js', 'public/js/articles.js')
   .js('resources/assets/js/front/comments.js', 'public/js/comments.js')
   .js('resources/assets/js/front/contact.js', 'public/js/contact.js')
   .js('resources/assets/js/front/user.js', 'public/js/user.js')
   .js([
      paths.moment,
      paths.bsDtPicker,
      paths.jasny,
      paths.holderjs,
      paths.admin
   ], "public/js/admin.js")

   .extract([
     'autosize',
     'markdown-it',
     'markdown-it-highlightjs',
     'moment',
     'holderjs',
     'lodash',
     'vue'])

   .scripts([
      paths.dt_jszip,
      paths.dt_pdf,
      paths.dt_pdf_fts,
      paths.dt_dt,
      paths.dt_bs,
      paths.dt_btns,
      paths.dt_btns_bs,
      paths.dt_colvis,
      paths.dt_html5,
      paths.dt_prnt,
      paths.dt_col_rdr,
      paths.dt_rspsv,
      paths.dt_rspsv_bs,
      paths.dt_defs
   ], "public/js/dt.js")

   .babel(["resources/assets/js/front/likes.js"], "public/js/likes.js")
   .babel(["resources/assets/js/front/privacy.js"], "public/js/privacy.js")
   .babel(["resources/assets/js/front/accept-cookies.js"], "public/js/accept-cookies.js")

   .babel(["resources/assets/js/back/toolbar.js"], "public/js/toolbar.js")
   .babel(["resources/assets/js/back/dashboard.js"], "public/js/dashboard.js")

   .scripts(["public/js/admin-bootstrap.js", "public/js/admin.js", "public/js/toolbar.js"], "public/js/base.js")
