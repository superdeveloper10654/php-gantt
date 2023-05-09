 window.reloadActivityFeed = function reloadActivityFeed() {
        $(".activity-feed").empty();
        // Load all tasks into array for quick ref
        var globalTasks = gantt.getTaskByTime();
        $.getJSON("beta.ajax.php?action=get_activities", function(data) {
          if (data.activities.length == 0) {
            $(".activity-feed").append("<img class='setup' src='img/svg/no-activity.svg'><h2>No activity yet</h2><p>Your activity feed will spring to life once you've done something</p>").css("text-align", "center");
            $(".no-activities").show();
          } else {
            $(".no-activities").hide();
            var count = 0,
              oddEven = "even",
              youOther = "you",
              isSelf = "false";
            $.each(data.activities, function(index) {
              if (data.activities[index].comments.length > 0) {
                var commentsString = "";
                var commentIndex = 0;
                $.each(data.activities[index].comments, function(indexComments) {
                  var commentType = "replied";
                  if (commentIndex == 0) {
                    commentType = "commented";
                  }
                  var name = data.activities[index].comments[indexComments].first_name + " " + data.activities[index].comments[indexComments].last_name;
                  if (data.activities[index].comments[indexComments].author_id == data.self_id) {
                    name = "You";
                  }
                  var created = moment.unix(data.activities[index].comments[indexComments].created);
                  var commentString = "<div class='feed-item-comment-block'>" + name + " " + commentType + " <span class='comment-block-created'> " + created.fromNow() + "</span><div class='comment'> '" + data.activities[index].comments[indexComments].text + "'</div></div>";
                  commentsString = commentsString + commentString;
                  commentIndex++;
                });
                commentsString = commentsString + "<button type='button' class='task-comment-add-trigger' href='#'><img class='header-button-icon' src='img/svg/comment.svg'> Reply</button>";
              } else {
                var commentsString = "<button type='button' data-id='" + data.activities[index].guid + "' class='task-comment-add-trigger' href='#'><img class='header-button-icon' src='img/svg/comment.svg'> Comment</button>";
              }
              var rollbackButton = "<button type='button' data-id='" + data.activities[index].guid + "' class='task-rollback-trigger'><img class='header-button-icon' src='img/svg/activity.svg'>&nbsp;Roll back</button>";
              if (count % 2 === 0) {
                oddEven = "even";
              } else {
                oddEven = "odd";
              }
              if ($("#user_id").val() == data.activities[index].user_id) {
                isSelf = "true";
              } else {
                isSelf = "false";
              }
              var tense = "active";
              var colour = "#3c3c3c";
              if (data.activities[index].active == "0") {
                tense = "inactive";
                colour = "#999";
              }
              var time = moment.unix(data.activities[index].created).fromNow();
              var timeStamp = moment.unix(data.activities[index].created).format("ddd D MMM (HH:mm)");
              var name = "You";
              var task1Name, task2Name;
              if (data.activities[index].user_id != data.self_id) {
                name = data.activities[index].first_name + " " + data.activities[index].last_name;
                youOther = "other";
              } else {
                youOther = "you";
              }
              if (data.activities[index].aux_data == null) {
                data.activities[index].aux_data = "_";
              }
              // Filters
              // Task
              if (data.activities[index].type == "task" || data.activities[index].type == "project" || data.activities[index].type == "milestone") {
                $.each(globalTasks, function(index2) {
                  if (globalTasks[index2].guid == data.activities[index].primary_object_guid) {
                    task1Name = "<a class='activity-view-task' href='#' data-id='" + data.activities[index].primary_object_guid + "'>" + globalTasks[index2].text + "</a>"
                  }
                });
                if (data.activities[index].action == "deleted" || data.activities[index].action == "added" || data.activities[index].action == "edited") {
                  task1Name = "<a class='activity-view-task' href='#' data-id='" + data.activities[index].primary_object_guid + "'>" + data.activities[index].primary_object_text; + "</a>"
                }
                var html = '<div data-self="' + isSelf + '" data-id="' + data.activities[index].id + '" class="animated fadeIn feed-item feed-item-' + oddEven + ' feed-item-' + youOther + ' feed-item-' + tense + '"><img class="feed-item-avatar-object" src="' + data.activities[index].avatar_url + '" style="width: 30px;"><div class="date">' + time + '</div><div class="text" style="color: ' + colour + '">' + name + " " + data.activities[index].ui_string + '<div class="text-muted">' + data.activities[index].aux_data + '</div></div>' + commentsString + rollbackButton + '</div>';
                
              }

              // Link
              if (data.activities[index].type == "link") {
                data.activities[index].type = "dependency";
                $.each(globalTasks, function(index2) {
                  if (globalTasks[index2].guid == data.activities[index].primary_object_guid) {
                    task1Name = "<a class='activity-view-task' href='#' data-id='" + data.activities[index].primary_object_guid + "'>" + globalTasks[index2].text + "</a>"
                  }
                  if (globalTasks[index2].guid == data.activities[index].secondary_object_guid) {
                    task2Name = "<a class='activity-view-task' href='#' data-id='" + data.activities[index].secondary_object_guid + "'>" + globalTasks[index2].text + "</a>"
                  }
                });
                var html = '<div data-self="' + isSelf + '" data-id="' + data.activities[index].id + '" class="animated fadeIn feed-item feed-item-' + oddEven + ' feed-item-' + youOther + ' feed-item-' + tense + '"><img class="feed-item-avatar-object" src="' + data.activities[index].avatar_url + '" style="width: 30px;"><div class="date">' + time + '</div><div class="text" style="color: ' + colour + '">' + name + " " + data.activities[index].ui_string + '<div class="text-muted">' + data.activities[index].aux_data + '</div></div>' + commentsString + rollbackButton + '</div>';
              }
              $(".activity-feed").append(html);
              count++;
            });
            $('.activity-feed .feed-item:after').each(function(index, obj) {
              $(this).css('background', '#999');
            });
          }
        });
      }
		
		$(document).on('click', '.task-rollback-trigger', function(e) 
		{
		console.log('A1');
		 $.getJSON("beta.ajax.php?action=rollback_versionX&id=" + $(this).data('id'), function(data) {
             //location.reload();
            //window.location.href = "beta.php?id=" + $("#programme_id").val() + "&version=" + data.guid;
			});
		
	});
		
		
$(document).on('click', '.activity-view-task', function(e) {
        if (e.target.className == "task-comment-add-trigger") {
          // Add comment
          e.preventDefault();
          e.stopPropagation();
          $("#comment_activity_id").val($(this).data("id"));
          $("#modal_add_activity_comment").modal('show');
          //     } else if (window.ibex_gantt_config.selfCanRollback == true) {
          // Add comment
          $.getJSON("beta.ajax.php?action=rollback_version&id=" + $(this).data('id'), function(data) {
            //  location.reload();
            window.location.href = "beta.php?id=" + $("#programme_id").val() + "&version=" + data.guid;
          });
          e.preventDefault();
          e.stopPropagation();
        } else {
          var tasks = gantt.getTaskByTime();
          for (var i = 0; i < tasks.length; i++) {
            if (tasks[i].guid == $(this).data("id")) {
              gantt.showLightbox(tasks[i].id);
              break;
            }
          };
        }
      });

                                           
                                           $(document).on('click', '.confirm-rollback-task', function(e) {
        $.getJSON("beta.ajax.php?action=rollback_version&id=" + $("#rollback_version_id").val(), function(data) {
          //  location.reload();
          //window.location.href = "beta.php?id=" + $("#programme_id").val() + "&version=" + data.guid;
        });
      });
      $(document).on('click', '.feed-item', function(e) {
        if (e.target.className == "task-comment-add-trigger") {
          // Add comment
          e.preventDefault();
          e.stopPropagation();
          $("#comment_activity_id").val($(this).data("id"));
          $("#modal_add_activity_comment").modal('show');
        } else {
          if (e.target.className == "task-rollback-trigger") 
			 {
				 
            $.getJSON("beta.ajax.php?action=rollback_version_by_id&id=" + $(this).data('id'), function(data) {
				
             window.location.href = "beta.php?id=" + $("#programme_id").val() + "&version=" + data.guid;
            });
          }
        }
      });
                                           
                                           $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr("href") == "#activity") {
          reloadActivityFeed();
        }
      });
                                           
                                           $("#modal_add_activity_comment").on("shown.bs.modal", function() {
        $("#comment_activity_text").focus();
      });

      $(document).on("click", ".add-comment-activity", function() {
        if ($("#comment_activity_text").val() != "") {
          $.getJSON("beta.ajax.php?action=add_version_comment&id=" + $("#comment_activity_id").val() + "&text=" + $("#comment_activity_text").val(), function(data) {
            $("#comment_activity_text").val('');
            $("#modal_add_activity_comment").modal('hide');
            reloadActivityFeed();
          });
        }
      });
                                           