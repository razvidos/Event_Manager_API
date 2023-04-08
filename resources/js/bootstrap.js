import _ from 'lodash';
import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from 'axios';

window._ = _;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if ([401, 403].includes(error.response.status)) {
            alert('You should be authorized');
        }
        return Promise.reject(error);
    },
);
