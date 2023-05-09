window.outdentTask = function outdentTask(task_id, initialIndexes, initialSiblings) {
        var task = gantt.getTask(task_id);
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = "Outdented";
        window.ibex_gantt_config.activityAction = "edited"
        window.ibex_gantt_config.activityType = task.type;
        forceSnapshotUpdate();
        var cur_task = gantt.getTask(task_id);
        var old_parent = cur_task.parent;
        if (gantt.isTaskExists(old_parent) && old_parent != gantt.config.root_id) {
          var index = gantt.getTaskIndex(old_parent) + 1;
          //var prevSibling = initialSiblings[task_id].first;
          //if(gantt.isSelectedTask(prevSibling)){
          //	index += (initialIndexes[task_id] - initialIndexes[prevSibling]);
          //}
          gantt.moveTask(task_id, index, gantt.getParent(cur_task.parent));
          if (!gantt.hasChild(old_parent))
            gantt.getTask(old_parent).type = "project";
          gantt.updateTask(task_id);
          gantt.updateTask(old_parent);
          return task_id;
        }
        return null;
      }

      window.indentTask = function indentTask(task_id) {
        var task = gantt.getTask(task_id);
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = "Indented";
        window.ibex_gantt_config.activityAction = "edited"
        window.ibex_gantt_config.activityType = task.type;
        forceSnapshotUpdate();
        var prev_id = gantt.getPrevSibling(task_id);
        while (gantt.isSelectedTask(prev_id)) {
          var prev = gantt.getPrevSibling(prev_id);
          if (!prev) break;
          prev_id = prev;
        }
        if (prev_id) {
          var new_parent = gantt.getTask(prev_id);
          gantt.moveTask(task_id, gantt.getChildren(new_parent.id).length, new_parent.id);
          new_parent.type = gantt.config.types.project;
          new_parent.$open = true;
          gantt.updateTask(task_id);
          gantt.updateTask(new_parent.id);
          return task_id;
        }
        return null;
      }

      window.updateTaskIndices = function updateTaskIndices() {
        var order_array = [];
        var tasks = gantt.getTaskByTime();
        for (var i = 0; i < tasks.length; i++) {
          var task = gantt.getTask(tasks[i].id);
          var id = task.id;
          var index = getIndexModified(task.id);
          task.order_ui = index;
          var obj = {
            'id': id,
            'index': index
          };
          order_array.push(obj);
        }
        var orderData = JSON.stringify(order_array);
        $.post("beta.ajax.php?action=set_ui_order", {
          data: orderData
        }, function(result) {
        });
      }
      window.moveTaskUp = function moveTaskUp(taskID) {
        var task = gantt.getTask(taskID);
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = "Position was updated";
        window.ibex_gantt_config.activityAction = "edited"
        window.ibex_gantt_config.activityType = task.type;
        forceSnapshotUpdate();
        gantt.moveTask(taskID, gantt.getTaskIndex(taskID) - 1, gantt.getParent(taskID));
        updateTaskIndices();
      }

      window.moveTaskDown = function moveTaskDown(taskID) {
        var task = gantt.getTask(taskID);
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = "Position was updated";
        window.ibex_gantt_config.activityAction = "edited"
        window.ibex_gantt_config.activityType = task.type;
        forceSnapshotUpdate();
        gantt.moveTask(taskID, gantt.getTaskIndex(taskID) + 1, gantt.getParent(taskID));
        updateTaskIndices();
      }

      window.scheduleAfterPrevious = function scheduleAfterPrevious(taskID) {
        var prev_id = gantt.getPrevSibling(taskID);
        var prevTask = gantt.getTask(prev_id);
        var thisTask = gantt.getTask(taskID);
        window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = true;
        gantt.addLink({
          programme_id: $("#programme_id").val(),
          source: prevTask.id,
          source_guid: prevTask.guid,
          target: thisTask.id,
          target_guid: thisTask.guid,
          type: "0",
          offset_minutes: "0",
          offset_type: "1",
          // created: moment().unix(),
          created: moment().format("ddd D MMM YYYY"), //Added by RB 12.04.19
        });
        autoScheduleTasks();
      }
      
      window.getIndexModified = function getIndexModified(id) {
        var result = -1;
        var index = 0;
        gantt.eachTask(function(ch) {
          if (ch.id == id)
            result = index;
          index++;
        });
        return result;
      }

      window.refreshUIOrder = function refreshUIOrder() {
        if (window.ibex_gantt_config.rollBackActive === false) {
          var order_array = [];
          var tasks = gantt.getTaskByTime();
          for (var i = 0; i < tasks.length; i++) {
            var task = gantt.getTask(tasks[i].id);
            var id = task.id;
            var index = getIndexModified(task.id);
            task.order_ui = index;
            var obj = {
              'id': id,
              'index': index
            };
            order_array.push(obj);
          }
          var orderData = JSON.stringify(order_array);
          $.post("beta.ajax.php?action=set_ui_order", {
            data: orderData
          }, function(result) {
          });
        }
      }
  
