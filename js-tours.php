<script>
  
  $("#initiate-tour-tweak-display").click(function() {
                          var tour = new Tour({
                            name: "tour-display-settings",
                            onEnd: function(tour) {
                              window.location.href = "beta.php?id=" + $("#programme_id").val();
                            },
                            //template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                            orphan: true,
                            backdrop: true,
                            storage: false
                          });
                          tour.addSteps([{
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only-explanation'></div><div class='tour-action-this-explanation'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,
                              title: "Tweak your display",
                              content: "<span>For the best tour experience, please follow the prompts on-screen.<br><br>When you see the eye icon, please simply click on the 'next' button to continue.<br><br>When you see the edit icon, please edit the field as indicated.<br><br><hr><br><br><br>",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,
                              title: "Display panels",
                              content: "The display is divided into two panels, which are the Gantt panel at the top and the Resource panel below it.<br><br>You can adjust the widths and heights of these panels by simply dragging them left, right, up or down.",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_grid:first",
                              placement: "right",
                              title: "Left-hand Gantt panel",
                              content: "Most of your interactions will take place here, because this is where you'll open your tasks to edit them, or drag them up or down the resequence them.<br><br>You can also select which columns you want to display.",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_layout_cell.timeline_cell.gantt_layout_outer_scroll.gantt_layout_outer_scroll_vertical.gantt_layout_outer_scroll.gantt_layout_outer_scroll_horizontal",
                              placement: "left",
                              title: "Right-hand Gantt panel",
                              content: "These bars and lines represent the information on the left.<br><br>You can drag these bars left or right to change the task dates, and quickly adjust the dependencies between your tasks.",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_layout_cell.resourceGrid_cell.gantt_layout_outer_scroll.gantt_layout_outer_scroll_vertical.gantt_layout_cell_border_right",
                              placement: "right",
                              title: "Left-hand Resource panel",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_layout_cell.resourceTimeline_cell.gantt_layout_outer_scroll.gantt_layout_outer_scroll_vertical.gantt_layout_outer_scroll.gantt_layout_outer_scroll_horizontal",
                              placement: "left",
                              title: "Right-hand Resource panel",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_resizer_x:first",
                              placement: "right",
                              title: "Try this now",
                              content: "Hover your mouse cursor over this line, then drag this line left or right to adjust the panel widths.",
                            },
                            {
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_resizer_x:last",
                              placement: "right",
                              title: "Try this now",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_resizer_y:first",
                              placement: "top",
                              title: "Try this now",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },

                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,

                              title: "It's that simple",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                          ]);

                          // Initialize the tour
                          tour.init();
                          // Start the tour
                          tour.start();
                        });
                        
        $("#initiate-tour-new-project").click(function() {
                          var tour = new Tour({
                            name: "tour-new-project",
                            onEnd: function(tour) {
                              window.location.href = "beta.php?id=" + $("#programme_id").val();
                            },
                            storage: false,
                            autoScroll: true,
                          });
                          tour.addSteps([{
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only-explanation'></div><div class='tour-action-this-explanation'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,
                              autoScroll: true,
                              title: "Create a new project",
                              content: "<span>For the best tour experience, please follow the prompts on-screen.<br><br>When you see the eye icon, please simply click on the 'next' button to continue.<br><br>When you see the edit icon, please edit the field as indicated.<br><br><hr><br><br><br>",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              placement: "top",
                              autoScroll: true,
                              //element: "button#insert-project",
                              element: ".gantt_row:last-child .gantt_cell:last-child .gantt_add",
                              title: "'New Project' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              /*onNext: function(tour) {
                                $('button#insert-project').click();
                              },*/
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              placement: "left",
                              element: "#task_edit_name",
                              title: "Project name",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button.save-task').click();
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: true,
                              smartPlacement: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_task_row:last-child",
                              title: "Here's your project",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                // $('button#insert-task').click();
                              },
                            },
                            {
                              onShow: function(tour) {},
                              backdrop: true,
                              smartPlacement: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".gantt_row:last-child .gantt_cell:last-child .gantt_add",
                              title: "xxxxx",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                //$('button.save-task').click();
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              backdrop: true,
                              smartPlacement: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "div#panel1",
                              title: "Give your new task a name",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button.save-task').click();
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              placement: "right",
                              element: ".gantt_task_row:last-child",
                              title: "Here's your new task",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              backdrop: true,
                              orphan: true,
                              smartPlacement: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              title: "It's that simple",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                          ]);

                          // Initialize the tour
                          tour.init();
                          // Start the tour
                          tour.start();
                        });


                        $("#initiate-tour-new-calendar").click(function() {
                          var tour = new Tour({
                            name: "tour-new-calendar",
                            onEnd: function(tour) {
                              window.location.href = "beta.php?id=" + $("#programme_id").val();
                            },
                            //template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                            orphan: true,
                            backdrop: true,
                            storage: false
                          });
                          tour.addSteps([{
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only-explanation'></div><div class='tour-action-this-explanation'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,
                              title: "Create a new calendar",
                              content: "<span>For the best tour experience, please follow the prompts on-screen.<br><br>When you see the eye icon, please simply click on the 'next' button to continue.<br><br>When you see the edit icon, please edit the field as indicated.<br><br><hr><br><br><br>",
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "ul#header",
                              placement: "bottom",
                              title: "Settings tab",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('a#settings-tab').click();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "button#settings-manage-calendars",
                              placement: "right",
                              title: "'Manage Calendars' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button#settings-manage-calendars').click();
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "div#modal_edit_calendars_header",
                              placement: "left",
                              title: "Calendars modal",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "button#calendars-modal-new-calendar",
                              placement: "left",
                              title: "'New Calendar' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button#calendars-modal-new-calendar').click();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "a#panel-cal-general",
                              placement: "left",
                              title: "Calendar's general details",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: ".calendar_edit_name",
                              placement: "left",
                              title: "Calendar's name",
                              content: "Type the name of your new calendar (e.g. Weekend Evenings)",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "div#calendar_type",
                              placement: "left",
                              title: "Type of calendar",
                              content: "Select the calendar type (is this calendar for tasks or resources?)",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "#calendar_enabled",
                              placement: "left",
                              title: "Enable this calendar",
                              content: "Tick the box if you want to enable this calendar, or leave it unticked to disable it",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "#calendar_default",
                              placement: "left",
                              title: "Type of calendar",
                              content: "Tick the box if you want this calendar to be the default calendar",
                              onNext: function(tour) {
                                $('a#panel-cal-working').click();
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "a#panel-cal-working",
                              placement: "left",
                              title: "Working days & times",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "#normal_days",
                              placement: "left",
                              title: "Working days",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "#calendar_edit_start_time",
                              placement: "left",
                              title: "Working times",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('a#panel-cal-overrides').click();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "a#panel-cal-overrides",
                              placement: "left",
                              title: "Non-working days & times",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "#calendar_edit_override_start_date",
                              placement: "left",
                              title: "Non-working dates",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "button#add_calendar_override",
                              placement: "right",
                              title: "'Add' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "button#save_calendar",
                              placement: "right",
                              title: "'Save' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button#save_calendar').click();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "div#settings-calendars-section",
                              placement: "top",
                              title: "'Manage Calendars' button",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('button#settings-manage-calendars').click();
                              },
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "div#modal_edit_calendars_header",
                              placement: "left",
                              title: "Calendars modal",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                            {
                              delay: 500,
                              backdrop: false,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              element: "table#table_calendars tbody tr:nth-child(2)",
                              placement: "right",
                              title: "Edit calendars",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                              onNext: function(tour) {
                                $('#modal_calendar_editor').modal('hide');
                                // window.location.href = "beta.php?id=" + $("#programme_id").val();
                              },
                            },
                            {
                              backdrop: true,
                              template: "<div class='popover tour' style='border-radius: 5px; z-index: 8000'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='tour-view-only'></div><div class='tour-action-this'></div><div class='popover-navigation'></button><button class='btn' data-role='end'>Finish</button><button class='btn' data-role='next'>Next <img src='img/svg/arrow-next.svg'></button></div></div>",
                              orphan: true,
                              smartPlacement: true,

                              title: "It's that simple",
                              content: "The Editor has opened on the General tab, but feel free to double-left click on the other tabs to take a look around.<br><br>You'll need to click on the 'Next' button below to interact with the inputs.",
                            },
                          ]);

                          // Initialize the tour
                          tour.init();
                          // Start the tour
                          tour.start();
                        });

  
  
</script>