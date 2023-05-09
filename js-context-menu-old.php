
        var globalMenu;
        gantt.attachEvent("onContextMenu", function(taskId, linkId, e) {

var actionsArray = '';

          gantt.eachTask(function(task) {
            if (task.id == taskId) {

             var projectName = "'" + task.text + "'";


              if (task.order_ui == "0" && task.parent == "0" && task.type == "project") {         // First project at the top of the gantt
                actionsArray = [{
                    name: 'Edit ' + projectName,
                    onClick: function() {
                      globalMenu.destroy();
                      gantt.showLightbox(taskId);
                    }
                  },
                  {
                    name: 'Move down',
                    onClick: function() {
                      globalMenu.destroy();
                      moveTaskDown(taskId);
                    }
                  },
                  {
                    name: 'Delete',
                    onClick: function() {
                      globalMenu.destroy();
                      window.ibex_gantt_config.activeTaskID = taskId;
                      $("#task_id_to_delete").val(taskId);
                      $("#modal_delete_task").modal('show');
                      gantt.render();
                    }
                  },
                ];
              }



if (task.order_ui != "0" && task.parent == "0" && task.type == "project") {         // Other project which isn't at the top of the gantt
                actionsArray = [{
                    name: 'Edit x ' + projectName,
                    onClick: function() {
                      globalMenu.destroy();
                      gantt.showLightbox(taskId);
                    }
                  },
                  {
                    name: 'Move down',
                    onClick: function() {
                      globalMenu.destroy();
                      moveTaskDown(taskId);
                    }
                  },
                  {
                    name: 'Delete',
                    onClick: function() {
                      globalMenu.destroy();
                      window.ibex_gantt_config.activeTaskID = taskId;
                      $("#task_id_to_delete").val(taskId);
                      $("#modal_delete_task").modal('show');
                      gantt.render();
                    }
                  },
                ];
              }



 if (task.parent != "0" && task.order_ui == "1") { // If task is within a project, and is the first task within that project, then don't show 'move up'
                var parent_order_ui = Number(gantt.getTask(task.parent).order_ui);
                var child_order_ui = Number(task.order_ui);

              //  if ((parent_order_ui - child_order_ui) == -1) {

                  actionsArray = [{
                      name: 'Edit',
                      onClick: function() {
                        globalMenu.destroy();
                        gantt.showLightbox(taskId);
                      }
                    },
                    {
                      name: 'Move down',
                      onClick: function() {
                        globalMenu.destroy();
                        moveTaskDown(taskId);
                      }
                    },

                    /* NEEDS MORE WORK
                  {
                    name: 'Convert to subtask',
                    onClick: function() {
                      globalMenu.destroy();
                      indentTask(taskId);
                    }
                  }, {
                    name: 'Convert to task',
                    onClick: function() {
                      globalMenu.destroy();
                       outdentTask(taskId);
                    }
                  },
                  {
                    name: 'Schedule after task above',
                    onClick: function() {
                      globalMenu.destroy();
                      scheduleAfterPrevious(gantt.config.rightClickedTaskID);
                    }
                  },
                  */
                    {
                      name: 'Delete',
                      onClick: function() {
                        globalMenu.destroy();
                        window.ibex_gantt_config.activeTaskID = taskId;
                        $("#task_id_to_delete").val(taskId);
                        $("#modal_delete_task").modal('show');
                        gantt.render();
                      }
                    },
                  ];
              //  }











}






 if (task.parent != "0" && task.order_ui != "1") { // If task is not the first task within that project, then show 'move up'
                var parent_order_ui = Number(gantt.getTask(task.parent).order_ui);
                var child_order_ui = Number(task.order_ui);

              //  if ((parent_order_ui - child_order_ui) == -1) {

                  actionsArray = [{
                      name: 'Edit',
                      onClick: function() {
                        globalMenu.destroy();
                        gantt.showLightbox(taskId);
                      }
                    },
                    {
                      name: 'Move down',
                      onClick: function() {
                        globalMenu.destroy();
                        moveTaskDown(taskId);
                      }
                    },
{
                      name: 'Move up',
                      onClick: function() {
                        globalMenu.destroy();
                        moveTaskUp(taskId);
                      }
                    },
                    /* NEEDS MORE WORK
                  {
                    name: 'Convert to subtask',
                    onClick: function() {
                      globalMenu.destroy();
                      indentTask(taskId);
                    }
                  }, {
                    name: 'Convert to task',
                    onClick: function() {
                      globalMenu.destroy();
                       outdentTask(taskId);
                    }
                  },
                  {
                    name: 'Schedule after task above',
                    onClick: function() {
                      globalMenu.destroy();
                      scheduleAfterPrevious(gantt.config.rightClickedTaskID);
                    }
                  },
                  */
                    {
                      name: 'Delete',
                      onClick: function() {
                        globalMenu.destroy();
                        window.ibex_gantt_config.activeTaskID = taskId;
                        $("#task_id_to_delete").val(taskId);
                        $("#modal_delete_task").modal('show');
                        gantt.render();
                      }
                    },
                  ];
              //  }











}





















            }



          if (globalMenu) {
            globalMenu.destroy();
          }
          if (taskId) {
            globalMenu = new BootstrapMenu('#gantt_here', {
              actions: actionsArray
            });
          }
        });



});











