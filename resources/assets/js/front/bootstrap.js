window.ajax = require('axios');
window.ajax.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.ajax.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
