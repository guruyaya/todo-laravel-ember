import Controller from '@ember/controller';
import config from '../config/environment';

const ajax_handler = function (url, data) {
    let method = 'GET';
    if (data){
        method='POST';
    }
    return Ember.$.ajax({
        url: config.laravel_server + url,
        method: method,
        crossDomain: true,
        data: data,
        xhrFields: { withCredentials: true }
    });
}
export default Controller.extend({
    actions: {
        check_login: function() {
            ajax_handler('is-logged-in').then(function(data) {
                console.log(data);
            });
        },
        register: function() {
            ajax_handler('register', {
                'name': this.get('register_name'),
                'email': this.get('register_email'),
                'password': this.get('register_password')
            }).then((data) => {
                if (data.success) {
                    window.location.href='/';
                }else{
                    alert('Something went wrong: ' + data.error);
                }
            }).catch(function() {
                alert('Something went wrong');
            });
        },
        login: function() {
           Ember.$.ajax({
                method: 'POST',
                data: {
                    'email': this.get('login_email'),
                    'password': this.get('login_password')
                },
                url: config.laravel_server + 'login',
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
