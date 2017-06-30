import swal from 'sweetalert2'

const VueSwal = {}

VueSwal.install = function(Vue) {
  Vue.prototype.$swal = swal
}

export default VueSwal
