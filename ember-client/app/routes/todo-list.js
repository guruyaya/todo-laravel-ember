import Route from '@ember/routing/route';
import config from '../config/environment';

export default Route.extend({
    async model() {
        const todo_items = [];
        await Ember.$.ajax({
            url: config.laravel_server + '/todo-list',
            crossDomain: true,
            xhrFields: { withCredentials: true }
        }).then(function(data) {
            if (data.success == false) {
                window.location.href = '/loggedout';
            }
            $.each(data.todo_items, function() {
                todo_items.push(this);
            });
        });
        return {
            todo_items: todo_items
        }
    }
});
