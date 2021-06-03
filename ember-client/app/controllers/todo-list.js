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
    share_users: [],
    actions: {
        delete_are_you_sure (item) {
            this.set('modal_question_wait', false);
            this.set('delete_task_title', item.task);
            this.set('item_id_to_delete', item.id);

            this.set('are_you_sure_modal', true);
        },
        delete_task(item_id_to_delete) {
            this.set('modal_question_wait', true);
            const controller = this;

            ajax_handler('delete-item', {
                item_id: item_id_to_delete
            }).then((data) => {
                if (!data.success) {
                    alert('Error: ' + data.error);
                }
                if (data.todo_items){
                    controller.get('model.todo_items').setObjects(data.todo_items);
                }
                this.set('modal_question_wait', false);
                this.set('are_you_sure_modal', false);
            }).catch(() => {
                alert('There was an unknown error');
                this.set('modal_question_wait', false);
                this.set('are_you_sure_modal', false);
            });
            this.set('are_you_sure_modal', false);
        },
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

        },
        submit_share_item(item_id) {
            let users_marked_inputs = $('#share_task_list li input:checked');
            let users_marked = [];
            let controller = this;

            $.each(users_marked_inputs, function() {
                users_marked.push($(this).attr('name'));
            });
            this.set('modal_share_wait', true);
            ajax_handler('share-item', {
                'item_id': item_id,
                'users_to_share': users_marked.join(',')
            }).then((data) => {
                if (!data.success) {
                    alert('Error: ' + data.error);
                }
                if (data.share_users){
                    this.set('modal_share_wait', false);
                    controller.get('share_users').setObjects(data.share_users);
                    if (data.todo_items){
                        controller.get('model.todo_items').setObjects(data.todo_items);
                    }
                }
            }).catch(() => {
                alert('There was an unknown error');
                this.set('modal_share_wait', false);
                this.set('share_modal', false);
            });
        },
        share_task(item) {
            this.set('share_modal', true);
            this.set('modal_share_wait', true);
            this.set('item_id_to_share', item.id);
            const controller = this;

            ajax_handler('ask-for-share?item_id=' + item.id).then((data) => {
                this.set('modal_share_wait', false);
                controller.get('share_users').setObjects(data.share_users);
            }).catch(() => {
                alert('There was an unknown error');
                this.set('modal_share_wait', false);
                this.set('share_modal', false);
            });
        }
    }
});
