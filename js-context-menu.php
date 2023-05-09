               var globalMenu;
                gantt.attachEvent("onContextMenu", function(taskId, linkId, e) {
                  var actionsArray = '';
                  gantt.eachTask(function(task) {
                    if (task.id == taskId) {
                      var name = "'" + task.text + "'";


// **** PROJECTS *** ///


                      // First project at the top
                      if (task.type == "project" && task.parent == 0 && task.order_ui == 0) {
                        actionsArray = [{
                            name: 'Edit first project ' + name,
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


                      // Other project not at the top, has no parent
                      if (task.type == "project" && task.parent == 0 && task.order_ui != 0) {
                        actionsArray = [{
                            name: 'Edit project ' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
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

                      
                      // Other project not at the top, has a parent
                      if (task.order_ui != 0 && task.parent != 0 && task.type == "project" && task.is_summary == 0) {
                        actionsArray = [{
                            name: 'Edit offspring project' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
                            }
                          },
                          {
                            name: 'Oudent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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



// **** SUMMARY TASKS *** ///


                      // Summary task not at the top, has a parent
                      if (task.order_ui != 0 && task.parent != 0 && task.type == "project" && task.is_summary == 1) {
                        actionsArray = [{
                            name: 'Edit summary offspring ' + name,
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
                            }
                          },
                          {
                            name: 'Outdent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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


                      // Summary task not at the top, has a parent - convert to a project
                      if (task.order_ui != 0 && task.parent != 0 && task.type == "task" && task.is_summary == 1) {
                        actionsArray = [{
                            name: 'Edit h ' + name,
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
                            name: 'Outdent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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


                      // Summary task not within a project
                      if (task.parent == 0 && task.type == "task" && task.is_summary == 1) {
                        actionsArray = [{
                            name: 'Edit summary top' + name,
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
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


// **** TASKS *** ///


                      // Task is within a project, and is the first task within that project
                      if (task.parent != 0 && task.order_ui == 1 && task.type == "task") {
                        actionsArray = [{
                            name: 'Edit first task ' + name,
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
                            name: 'Outdent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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


                      // Task is within a project, and is not the first task within that project
                      if (task.parent != 0 && task.order_ui != 1 && task.type == "task" && task.is_summary == 0) {
                        actionsArray = [{
                            name: 'Edit task offspring ' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
                            }
                          },
                          {
                            name: 'Outdent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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


                      // Task is not within a project, and is at the top
                      if (task.parent == 0 && task.type == "task" && task.is_summary == 0 && task.order_ui == 0) {
                        actionsArray = [{
                            name: 'Edit first task ' + name,
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


                       // Task is not within a project, and is not at the top
                      if (task.parent == 0 && task.type == "task" && task.is_summary == 0 && task.order_ui != 0) {
                        actionsArray = [{
                            name: 'Edit task free ' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
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


// **** MILESTONES *** ///


                      // If this milestone is at the top
                      if (task.order_ui == 0 && task.type == "milestone") {
                        actionsArray = [{
                            name: 'Edit milestone top ' + name,
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


                      // Milestone is within a project (or summary task)
                      if (task.type == "milestone" && task.parent != 0 && task.order_ui != 0) {
                        actionsArray = [{
                            name: 'Edit milestone offspring ' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Outdent',
                            onClick: function() {
                              globalMenu.destroy();
                              outdentTask(taskId);
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


                      // Milestone is not within a project (or summary task)
                      if (task.type == "milestone" && task.parent == 0 && task.order_ui != 0) {
                        actionsArray = [{
                            name: 'Edit milestone orphan ' + name,
                            onClick: function() {
                              globalMenu.destroy();
                              gantt.showLightbox(taskId);
                            }
                          },
                          {
                            name: 'Move up',
                            onClick: function() {
                              globalMenu.destroy();
                              moveTaskUp(taskId);
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
                            name: 'Indent',
                            onClick: function() {
                              globalMenu.destroy();
                              indentTask(taskId);
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





                    }
                  });
                  if (globalMenu) {
                    globalMenu.destroy();
                  }
                  if (taskId) {
                    globalMenu = new BootstrapMenu('#gantt_here', {
                      actions: actionsArray
                    });
                  }
                });