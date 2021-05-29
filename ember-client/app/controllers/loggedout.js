import Controller from '@ember/controller';
import config from '../config/environment';

export default Controller.extend({
    actions: {
        check_login: function() {
            $.ajax({
                'url': config.laravel_server + '/is_logged_in',
                crossDomain: true,
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                console.log(data);
            });
        },
        register: function() {
            const register_req = $.post(config.laravel_server + '/register', {
                'name': this.get('register_name'),
                'email': this.get('register_email'),
                'password': this.get('register_password')
            });
            register_req.then(function() {
                window.location.href='/';
            });
            register_req.catch(function() {
                alert('Something went wrong');
            });
        },
        login: function() {
            $.ajax({
                method: 'POST',
                data: {
                    'email': this.get('login_email'),
                    'password': this.get('login_password')
                },
                url: config.laravel_server + '/login',
                crossDomain: true,
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                if (data.success)
                    window.location.href = '/';
                else
                    alert('Login failed')
            });
        }
    }
});
