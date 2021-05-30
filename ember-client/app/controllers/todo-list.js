import Controller from '@ember/controller';
import config from '../config/environment';


export default Controller.extend({
    todo_items: [],
    actions: {
        'set_completed': function(task_id, is_done) {
            $('#todo_item_' + task_id).attr('disabled', true);
            const controller = this;

            Ember.$.ajax({
                url: config.laravel_server + '/set-completed',
                method: 'POST',
                crossDomain: true,
                data: {
                    task_id: task_id,
                    is_done: is_done
                },
                xhrFields: { withCredentials: true }
            }).then(function(data) {
                $('#todo_item_' + task_id).attr('disabled', false);
                if (!data.success) {
                    alert('Error: ' + data.error);
                    if (data.todo_items){
                        controller.get('model.todo_items').setObjects(data.todo_items);
                    }
                    return false;
                }
                var todo_items = controller.get('model.todo_items').setObjects([]);
                $.each(data.todo_items, function() {
                    todo_items.pushObject(this);
                });
            }).catch(function() {
                alert('Something went wrong');
                return false;
            });
        }
    }
});
