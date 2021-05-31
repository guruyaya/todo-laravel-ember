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
    todo_items: [],
    actions: {
        new_item () {
            const controller = this;

           $('#new_task_button').attr('disabled', true);
            ajax_handler('new-item', {
                'task': this.get('task')
            }).then((data) => {
               $('#new_task_button').attr('disabled', false);
                if (!data.success) {
                    alert('Error: ' + data.error);
                    if (data.todo_items){
                        controller.get('model.todo_items').setObjects(data.todo_items);
                    }
                    return false;
                }
               $('#new_task_input').val('');
               $('#new_task_button').attr('disabled', true);
                controller.get('model.todo_items').setObjects(data.todo_items);
            }).catch(function() {
               $('#new_task_button').attr('disabled', false);
                alert('Something went wrong');
                return false;
            });
        },
        set_completed (task_id, is_done) {
           $('#todo_item_' + task_id).attr('disabled', true);
            const controller = this;

            ajax_handler('set-completed', {
                task_id: task_id,
                is_done: is_done
            }).then(function(data) {
               $('#todo_item_' + task_id).attr('disabled', false);
                if (!data.success) {
                    alert('Error: ' + data.error);
                    if (data.todo_items){
                        controller.get('model.todo_items').setObjects(data.todo_items);
                    }
                    return false;
                }
                controller.get('model.todo_items').setObjects(data.todo_items);
            }).catch(function() {
               $('#todo_item_' + task_id).attr('disabled', false);
                alert('Something went wrong');
                return false;
            });
        },
        logout(){
            ajax_handler('logout', {'do_logout': 1}).then((data) => {
                if (data.success) {
                    window.location.href = '/loggedout';
                    return;
                }
                alert('Error logging out');
            }).catch(function() {
                alert('Something went wrong');
                return false;
            });

        }
    }
});
