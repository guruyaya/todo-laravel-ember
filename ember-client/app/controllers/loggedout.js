import Controller from '@ember/controller';
import config from '../config/environment';
import $ from 'jquery';

const ajax_handler = function (url, data) {
    let method = 'GET';
    if (data){
        method='POST';
    }
    return $.ajax({
        url: config.laravel_server + url,
        method: method,
        crossDomain: true,
        data: data,
        xhrFields: { withCredentials: true }
    });
}
export default Controller.extend({
    actions: {
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
           $.ajax({
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
