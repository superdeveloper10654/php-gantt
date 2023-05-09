<script>
  $(".add-file-to-task").click(function(e) {
    $("#task_file_handler").click();
  });

  window.reloadFiles = function reloadFiles() {
    $("#table_files").empty();
    // Load all tasks into array for quick ref
    var globalTasks = gantt.getTaskByTime();
    $.getJSON("beta.ajax.php?action=get_files", function(data) {
      if (data.files.length == 0) {
        $('.no-files').show();
      } else {
        $('.no-files').hide();
        $.each(data.files, function(index) {
          var countFile = 0;
          $.each(globalTasks, function(indexTask) {
            var taskFiles = globalTasks[indexTask].files;
            if (taskFiles != null) {
              var test = taskFiles.includes(data.files[index].id);
              if (test == true) {
                countFile++;
              }
            }
          });
          var linkText = "";
          if (countFile == 0) {
            linkText = "";
          }
          if (countFile == 1) {
            /* linkText = "Attached to 1 task | "; */
          }
          if (countFile > 1) {
            /* linkText = "Attached to " + countFile + " tasks | "; */
          }
          var downloadURL = data.files[index].url;
          var dataFilename = data.files[index].name;
          var uploaded = moment.unix(data.files[index].uploaded).format("YYYY-MM-DD HH:mm:ss");
          var iconURL = "img/svg/file-generic.svg";
          if (data.files[index].extension == "pdf") {
            iconURL = "img/svg/file-pdf.svg";
          }
          if (data.files[index].extension == "docx") {
            iconURL = "img/svg/file-word.svg";
          }
          if (data.files[index].extension == "xls" || data.files[index].extension == "xlsx") {
            iconURL = "img/svg/file-excel.svg";
          }
          if (data.files[index].extension == "jpg" || data.files[index].extension == "JPG") {
            iconURL = "img/svg/file-jpg.svg";
          }
          if (data.files[index].extension == "png") {
            iconURL = "img/svg/file-png.svg";
          }
          if (data.files[index].extension == "zip") {
            iconURL = "img/svg/file-zip.svg";
          }
          if (data.files[index].extension == "png") {
            iconURL = "img/svg/file-png.svg";
          }
          if (data.files[index].extension == "txt") {
            iconURL = "img/svg/file-txt.svg";
          }
          if (data.files[index].extension == "ppt") {
            iconURL = "img/svg/file-ppt.svg";
          }
          if (data.files[index].extension == "gif") {
            iconURL = "img/svg/file-gif.svg";
          }
          if (data.files[index].extension == "svg") {
            iconURL = "img/svg/file-svg.svg";
          }
          if (data.files[index].extension == "ai") {
            iconURL = "img/svg/file-ai.svg";
          }
          if (data.files[index].extension == "html") {
            iconURL = "img/svg/file-html.svg";
          }
          if (data.files[index].extension == "sql") {
            iconURL = "img/svg/file-sql.svg";
          }
          if (data.files[index].extension == "css") {
            iconURL = "img/svg/file-css.svg";
          }
          $('#table_files').append('<button style="background-size: cover !important; background: url(' + data.files[index].url + ')" class="file-edit" data-id="' + data.files[index].id + '"><div class="file-wrapper"><img style="width: 40px; float: left;" src="' + iconURL + '"</div><div class="file-name">' + data.files[index].name + '</div><div class="file-author">' + data.files[index].user_first_name + " " + data.files[index].user_last_name + ' (' + moment(uploaded).fromNow() + ')</div><div class="linktext">' + linkText + ' <a class="download-file" download="' + dataFilename + '" href="' + downloadURL + '"><img class="download-file" src="img/svg/download.svg" title="download this file"></a></div></button>').addClass('mx-auto');
        });
      }
    });
  }

  $('#file_task_links').select2({
    minimumResultsForSearch: -1,
    placeholder: function() {
      $(this).data('placeholder');
    }
  });

  $('#file_task_links').on("select2:select", function(e) {
    $.getJSON("beta.ajax.php?action=link_file_to_task&file_id=" + $("#file_id").val() + "&task_id=" + e.params.data.id, function(data) {
      setTimeout(function() {
        reloadFiles();
      }, 2000);
    });
  });

  $('#file_task_links').on("select2:unselect", function(e) {
    $.getJSON("beta.ajax.php?action=unlink_file_from_task&file_id=" + $("#file_id").val() + "&task_id=" + e.params.data.id, function(data) {
      setTimeout(function() {
        reloadFiles();
      }, 2000);
    });
  });

  $(document).on('click', '.delete-file', function(e) {
    $.getJSON("beta.ajax.php?action=delete_file&file_id=" + $("#file_id").val(), function(data) {
      setTimeout(function() {
        reloadFiles();
      }, 1000);
      $("#modal_edit_file").modal('hide');
    });
  });

  $(document).on('click', '.add-file-unique', function(e) {
    $("#file_handler_unique").click();
  });

  window.uploadUniqueFile = function uploadUniqueFile(file) {
    $(".upload-percentage").show();
    var file = document.getElementById("file_handler_unique").files[0];
    var formData = new FormData();
    file.src = URL.createObjectURL(event.target.files[0]);
    formData.append("file", file);
    $.ajax({
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        // Upload progress
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            //Do something with upload progress
            if (percentComplete >= 0.98) {
              $(".upload-percentage").hide();
            }
            $(".upload-percentage").html("Uploading " + Number(percentComplete * 100).toFixed(0) + "%");
          }
        }, false);
        return xhr;
      },
      url: "beta.ajax.php?action=add_file_to_programme&programme_id=" + $("#programme_id").val(),
      dataType: 'script',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'post',
      success: function(data) {
        reloadFiles();
      },
    })
  }

  window.updateTaskFile = function updateTaskFile(file) {
    $(".add-file-to-task").html('<img class="" src="img/svg/paperclip-uploading.svg">').addClass('uploading');
    var file = document.getElementById("task_file_handler").files[0];
    var formData = new FormData();
    formData.append("file", file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', "beta.ajax.php?action=add_file_to_task&programme_id=" + $("#programme_id").val() + "&task_guid=" + window.ibex_gantt_config.activeTaskGUID, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        // File(s) uploaded.
        var data = JSON.parse(this.response);
        $('#task_edit_files > tbody').empty();
        for (i = 0; i < data.files.length; i++) {
          $('#task_edit_files >tbody').append('<tr><td>' + data.files[i].name + '</td><td><span><a download="' + data.files[i].name + '" href="' + data.files[i].url + '"><img src="img/svg/download.svg" class="download-task-file" title="download this file" data-id="' + data.files[i].id + '"></img></a><img src="img/svg/bin-1.svg" class="remove-task-file" data-id="' + data.files[i].id + '"></img></span></td></tr>');
        }
        $(".add-file-to-task").html('<img class="" src="img/svg/paperclip.svg">').removeClass('uploading');
        reloadFiles();
      } else {}
    };
    xhr.send(formData);
  }

  $(document).on('click', '.file-edit', function(e) {
    if (e.target.className == "download-file") {} else {
      var tasks = gantt.getTaskByTime();
      $("#file_id").val($(this).data("id"));
      $.getJSON("beta.ajax.php?action=get_file&id=" + $(this).data("id"), function(data) {
        var uploaded = moment.unix(data.file.uploaded).format("YYYY-MM-DD HH:mm:ss");
        $(".file-intro").html("<span class='title'>" + data.file.name + "</span><br><br>" + data.file.user_first_name + " " + data.file.user_last_name + " (" + moment(uploaded).fromNow() + ")");
        var arraySorted = tasks.sort(function(a, b) {
          var textA = a.text.toUpperCase();
          var textB = b.text.toUpperCase();
          return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
        });
        $('#file_task_links').empty();
        $.each(arraySorted, function(i, item) {
          // Check if file in this task array
          if (item.files != "" && item.files != null) {
            var check = item.files.includes($("#file_id").val());
            if (check == true) {
              var o = new Option(item.text, item.id);
              o.selected = true;
              $("#file_task_links").append(o);
            } else {
              var o = new Option(item.text, item.id);
              $("#file_task_links").append(o);
            }
          } else {
            var o = new Option(item.text, item.id);
            $("#file_task_links").append(o);
          }
        });
        $("#modal_edit_file").modal('show');
      });
    }
  });

  $(document).on('click', '.remove-task-file', function(e) {
    $.getJSON("beta.ajax.php?action=remove_task_file&file_id=" + $(this).data('id') + "&task_guid=" + window.ibex_gantt_config.activeTaskGUID, function(data) {
      var files = data.files;
      var taskID = data.task_id;
      var filesArray = [];
      var thisTask = gantt.getTask(taskID);
      $('#task_edit_files > tbody').empty();
      for (i = 0; i < files.length; i++) {
        filesArray.push(files[i].id);
        $('#task_edit_files >tbody').append('<tr><td>' + files[i].name + '</td><td><span><a download="' + files[i].name + '" href="' + files[i].url + '"><img src="img/svg/download.svg" class="download-task-file" title="download this file" data-id="' + files[i].id + '"></img> </a> &nbsp; <img src="img/svg/bin-1.svg" class="remove-task-file" data-id="' + files[i].id + '"></img></span></td></tr>');
      }
      thisTask.files = JSON.stringify(filesArray);
      gantt.updateTask(thisTask.id);
    });
  });

  var timerFile;
  $('#file_search_text').keyup(function() {
    clearTimeout(timerFile);
    timerFile = setTimeout(function() {
      var searchText = $("#file_search_text").val().toLowerCase();
      $.each($(".file-edit"), function() {
        if ($(this).text().toLowerCase().indexOf(searchText) === -1) {
          $(this).closest('.file-edit').hide();
        } else {
          $(this).closest('.file-edit').show();
        }
      });
    }, 400);
  });

  $(".clear-file-search").click(function() {
    $("#file_search_text").val('');
    $.each($(".text"), function() {
      $(this).closest('.file-edit').show();
    });
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    if ($(e.target).attr("href") == "#files") {
      reloadFiles();
    }
  });

  /*** PROFILE IMAGE ***/
  $(document).on('click', '.set-profile-image', function(e) {
    $("#file_handler_profile").click();
  });
  window.setProfileImage = function setProfileImage(file) {
	   $('.set-profile-image').text('Uploading...');
    var file = document.getElementById("file_handler_profile").files[0];
    var formData = new FormData();
    formData.append("file", file);
    $.ajax({
      url: "beta.ajax.php?action=set_profile_image",
      dataType: 'script',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'post',
      success: function(data) {
        var response = JSON.parse(data);
        var avatarURL = response.avatar_url;
        $(".set-profile-image").attr("src", avatarURL);
        $(".display-profile-image").attr("src", avatarURL);
		   $('.set-profile-image').text('Upload');
      },
    })
  }
  var loadAvatar = function(event) {
    var output = document.getElementById('avatar');
    output.src = URL.createObjectURL(event.target.files[0]);
  };


   /*** PROFILE IMAGE UI ***/
  $(document).on('click', '.set-profile-image-ui', function(e) {
    $("#file_handler_profile_ui").click();
  });
  window.setProfileImageUI = function setProfileImageIO(file) {
    var file = document.getElementById("file_handler_profile_ui").files[0];
    var formData = new FormData();
    formData.append("file", file);
    $.ajax({
      url: "beta.ajax.php?action=set_profile_image",
      dataType: 'script',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'post',
      success: function(data) {
        var response = JSON.parse(data);
        var avatarURL = response.avatar_url;
        $(".set-profile-image").attr("src", avatarURL);
        $(".display-profile-image").attr("src", avatarURL);
      },
    })
 }
 
 
 


  /*** BACKGROUND IMAGE ***/
  $(document).on('click', '.set-background-image', function(e) {
    $("#file_handler_background").click();
  });
  window.setBackgroundImage = function setBackgroundImage(file) {
    var file = document.getElementById("file_handler_background").files[0];
    var formData = new FormData();
    formData.append("file", file);
    $.ajax({
      url: "beta.ajax.php?action=set_background_image",
      dataType: 'script',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'post',
      success: function(data) {
        var response = JSON.parse(data);
        var backgroundImageURL = response.background_url;
        $(".set-background-image").attr("src", backgroundImageURL);
        $(".display-background-image").attr("src", backgroundImageURL);
      },
    })
  }
  var loadBackground = function(event) {
    var output = document.getElementById('background');
    output.src = URL.createObjectURL(event.target.files[0]);
  };


  /*** BACKGROUND OPACITY ***/
  $(document).on('click', '#opacity-2', function(e) {
    $("#background-image").css("opacity", "0.2");
    $(".display-background-image").css("opacity", "0.2");
    $("#opacity-2").addClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.2");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-3', function(e) {
    $("#background-image").css("opacity", "0.3");
    $(".display-background-image").css("opacity", "0.3");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").addClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.3");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-4', function(e) {
    $("#background-image").css("opacity", "0.4");
    $(".display-background-image").css("opacity", "0.4");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").addClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.4");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-5', function(e) {
    $("#background-image").css("opacity", "0.5");
    $(".display-background-image").css("opacity", "0.5");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").addClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.5");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-6', function(e) {
    $("#background-image").css("opacity", "0.6");
    $(".display-background-image").css("opacity", "0.6");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").addClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.6");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
$(document).on('click', '#opacity-7', function(e) {
    $("#background-image").css("opacity", "0.7");
    $(".display-background-image").css("opacity", "0.7");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").addClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.7");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-8', function(e) {
    $("#background-image").css("opacity", "0.8");
    $(".display-background-image").css("opacity", "0.8");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").addClass("selected");
    $("#opacity-9").removeClass("selected");
    $("#background_opacity").val("0.8");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  $(document).on('click', '#opacity-9', function(e) {
    $("#background-image").css("opacity", "0.9");
    $(".display-background-image").css("opacity", "0.9");
    $("#opacity-2").removeClass("selected");
    $("#opacity-3").removeClass("selected");
    $("#opacity-4").removeClass("selected");
    $("#opacity-5").removeClass("selected");
    $("#opacity-6").removeClass("selected");
    $("#opacity-7").removeClass("selected");
    $("#opacity-8").removeClass("selected");
    $("#opacity-9").addClass("selected");
    $("#background_opacity").val("0.9");
    $.getJSON("beta.ajax.php?action=set_background_opacity&background_opacity=" + $("#background_opacity").val(), function(data) {});
  });
  
  
  
  /*** OPACITY FONT COLOUR ***/
  $(document).on('click', '#opacity-font-default', function(e) {
    $("#opacity-font-default").addClass("selected");
    $("#opacity-font-white").removeClass("selected");
    $("#opacity-font-yellow").removeClass("selected");
    $("#opacity-font-blue").removeClass("selected");
    $("#opacity-font-red").removeClass("selected");
    $("#opacity_font").val("rgb(51,51,51)").css("color", "rgb(51,51,51)");
    $("#background-example-color").css("color", "rgb(51,51,51)");
    $.getJSON("beta.ajax.php?action=set_opacity_font&opacity_font=" + $("#opacity_font").val(), function(data) {});
  });
  $(document).on('click', '#opacity-font-white', function(e) {
    $("#opacity-font-default").removeClass("selected");
    $("#opacity-font-white").addClass("selected");
    $("#opacity-font-yellow").removeClass("selected");
    $("#opacity-font-blue").removeClass("selected");
    $("#opacity-font-red").removeClass("selected");
    $("#opacity_font").val("rgb(255,255,255)");
    $("#background-example-color").css("color", "rgb(255,255,255)");
    $.getJSON("beta.ajax.php?action=set_opacity_font&opacity_font=" + $("#opacity_font").val(), function(data) {});
  });
  $(document).on('click', '#opacity-font-yellow', function(e) {
    $("#opacity-font-default").removeClass("selected");
    $("#opacity-font-white").removeClass("selected");
    $("#opacity-font-yellow").addClass("selected");
    $("#opacity-font-blue").removeClass("selected");
    $("#opacity-font-red").removeClass("selected");
    $("#opacity_font").val("rgb(255,255,0)");
    $("#background-example-color").css("color", "rgb(5255,255,0)");
    $.getJSON("beta.ajax.php?action=set_opacity_font&opacity_font=" + $("#opacity_font").val(), function(data) {});
  });
  $(document).on('click', '#opacity-font-blue', function(e) {
    $("#opacity-font-default").removeClass("selected");
    $("#opacity-font-white").removeClass("selected");
    $("#opacity-font-yellow").removeClass("selected");
    $("#opacity-font-blue").addClass("selected");
    $("#opacity-font-red").removeClass("selected");
    $("#opacity_font").val("rgb(0,0,255)");
    $("#background-example-color").css("color", "rgb(0,0,255)");
    $.getJSON("beta.ajax.php?action=set_opacity_font&opacity_font=" + $("#opacity_font").val(), function(data) {});
  });
  $(document).on('click', '#opacity-font-red', function(e) {
    $("#opacity-font-default").removeClass("selected");
    $("#opacity-font-white").removeClass("selected");
    $("#opacity-font-yellow").removeClass("selected");
    $("#opacity-font-blue").removeClass("selected");
    $("#opacity-font-red").addClass("selected");
    $("#opacity_font").val("rgb(255,17,0)");
    $("#background-example-color").css("color", "rgb(255,17,0)");
    $.getJSON("beta.ajax.php?action=set_opacity_font&opacity_font=" + $("#opacity_font").val(), function(data) {});
  });


  /*** RESOURCE ITEM'S IMAGE ***/
  $(document).on('click', '.set-resource-image', function(e) {
    $("#file_handler_resource_image").click();
  });
  window.setResourceImage = function setResourceImage(file) {
	  $('.set-resource-image').text('Uploading...');
    var file = document.getElementById("file_handler_resource_image").files[0];
    var formData = new FormData();
	 formData.append("guid", $("#resource_edit_guid").val());
    formData.append("file", file);
    $.ajax({
      url: "beta.ajax.php?action=set_resource_image",
      dataType: 'script',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      type: 'post',
      success: function(data) {
        var response = JSON.parse(data);
        var resourceImageURL = response.resource_image_url;
        $(".set-resource-image").attr("src", resourceImageURL);
        $(".display-resource-image").attr("src", resourceImageURL);
		  $("#resource_edit_id").val(response.resource_id);
		  $('.set-resource-image').text('Upload');
      },
    })
  }
  var loadResourceImage = function(event) {
    var output = document.getElementById('resource-image');
    output.src = URL.createObjectURL(event.target.files[0]);
  };
</script>