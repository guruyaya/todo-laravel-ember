import Controller from '@ember/controller';
import config from '../config/environment';

export default Controller.extend({
    todo_items: [],
    actions: {
        'set_completed': function(task_id, isDone) {
            $('#todo_item_' + task_id).attr('disabled', true);
            const controller = this;

            Ember.$.ajax({
                url: config.laravel_server + '/set_completed',
                method: 'POST',
                crossDomain: true,
                data: {
                    task_id: task_id,
                    isDone: isDone
                },
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                if (!data.success) {
                    alert('Something went wrong');
                    controller.get('todo_items').setObjects(controller.todo_items);
                    return false;
                }
                var todo_items = controller.get('todo_items').setObjects([]);
                $.each(data.todo_items, function() {
                    todo_items.pushObject(this);
                });
            }).catch(function() {
                alert('Something went wrong');
                $('#todo_item_' + task_id).attr('disabled', false);
                return false;
            });
        }
    }
});
