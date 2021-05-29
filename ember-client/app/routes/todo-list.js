import Route from '@ember/routing/route';
import config from '../config/environment';

export default Route.extend({
    model: function() {
        $.ajax({
            'url': config.laravel_server + '/is_logged_in',
            crossDomain: true,
            xhrFields: { withCredentials: true }
        }).then(function(data) {
            if (data.logged_in == false) {
                window.location.href = '/loggedout'
            }
        });
    }
});
