import Controller from '@ember/controller';

export default Controller.extend({
    actions: {
        check_login: function() {
            $.ajax({
                'url': 'http://localhost:8000/is_logged_in',
                crossDomain: true,
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                console.log(data);
            });
        },
        register: function() {
            const register_req = $.post('http://localhost:8000/register', {
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
                url: 'http://localhost:8000/login',
                crossDomain: true,
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                window.location.href = '/';
            });
        }
    }
});
