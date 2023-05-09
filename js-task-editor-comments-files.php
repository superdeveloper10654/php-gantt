$(".insert-task-comment").click(function(e) {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        var comments = [];
        if (task.comments != "" && task.comments != undefined) {
          comments = JSON.parse(task.comments);
        }
        var newComment = {};
        newComment['text'] = $("#new_comment_text").val();
        newComment['added'] = moment().format("ddd D MMM YYYY @ HH:mm"),
          comments.unshift(newComment);
        task.comments = JSON.stringify(comments);
        gantt.updateTask(task.id);
        $("#new_comment_text").val('');
        $('#task_edit_comments > tbody').empty();
        for (i = 0; i < comments.length; i++) {
          $('#task_edit_comments >tbody').append('<tr><td>' + comments[i].text + '<br><span class="text-muted" style="font-size: 0.9em">' + comments[i].added + '</span></td></tr>');
        }
      });
  var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
  var comments = [];
  if (task.comments != "" && task.comments != undefined) {
    comments = JSON.parse(task.comments);
  }
  $('#task_edit_comments > tbody').empty();
  for (i = 0; i < comments.length; i++) {
    $('#task_edit_comments >tbody').append('<tr><td>"' + comments[i].text + '"<br><span class="text-muted" style="font-size: 0.9em">' + comments[i].added + '</span></td></tr>');
  }

  var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
  var files = [];
  if (task.files != "" && task.files != undefined) {
    files = JSON.parse(task.files);
  }
  $('#task_edit_files > tbody').empty();
  for (i = 0; i < files.length; i++) {
    var fileID = files[i];
    $.each(window.ibex_gantt_config.files, function(index) {
      if (window.ibex_gantt_config.files[index].id == fileID) {
        $('#task_edit_files >tbody').append('<tr><td>' + window.ibex_gantt_config.files[index].name + '</td><td><span><a download="' + window.ibex_gantt_config.files[index].name + '" href="' + window.ibex_gantt_config.files[index].url + '"><img src="img/svg/download.svg" class="download-task-file" data-id="' + fileID + '"></i> </a> &nbsp; <img src="img/svg/bin-1.svg" class="remove-task-file" data-id="' + fileID + '"></img></span></td></tr>');
      }
    });
  }

