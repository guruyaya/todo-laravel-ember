import Route from '@ember/routing/route';
import config from '../config/environment';

export default Route.extend({
    async model() {
        // onload set disabled button states
        $(document).on('keyup', '#new_task_input', function(){
            const disabled_status = ($(this).val().trim() == '');
            $('#new_task_button').attr('disabled', disabled_status);
        });
        const todo_items = [];
        await Ember.$.ajax({
            url: config.laravel_server + 'todo-list',
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
