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
    errors_register_name: [],
    errors_register_email: [],
    errors_register_password: [],
    actions: {
        register: function() {
            const controller = this;
            for (const key of ['name', 'email', 'password']) {
                controller.get('errors_register_' + key).setObjects([])
            }
            ajax_handler('register', {
                'name': this.get('register_name'),
                'email': this.get('register_email'),
                'password': this.get('register_password'),
                'password_confirmation': this.get('register_repeat_password')
            }).then((data) => {
                $.each(['name', 'email', 'password'], function() {
                    console.log(this);
                });
                if (data.success) {
                    window.location.href='/';
                }else{
                    if (data.error){
                        alert('Something went wrong: ' + data.error);
                        return;
                    }
                    for (let key in data.errors) {
                        controller.get('errors_register_' + key).setObjects(data.errors[key])
                        console.log(key, data.errors[key]);
                    }
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
