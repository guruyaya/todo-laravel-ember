import Route from '@ember/routing/route';
import config from '../config/environment';

export default Route.extend({
    setupController: function(controller, mode) {
        var todo_items = controller.get('todo_items').setObjects([]);
        Ember.$.ajax({
            url: config.laravel_server + '/todo-list',
            crossDomain: true,
            xhrFields: { withCredentials: true }
        }).then(function(data) {
            if (data.success == false) {
                window.location.href = '/loggedout';
            }
            $.each(data.todo_items, function() {
                todo_items.pushObject(this);
            });
        });
    }
});
