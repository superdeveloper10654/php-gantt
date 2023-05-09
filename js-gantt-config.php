<script>
      window.ibex_gantt_config = {
        resources: null,
        initialDataLoaded: false,
        activeTaskID: 0,
        activeTaskGUID: 0,
        timingUnit: 1,
        taskDurationUnit: 1,
        editingCalendarID: 0,
        calendarOverrides: null,
        overrideLinkBeforeAddBehaviour: false,
        baselinesShown: false,
        deadlinesShown: false,
        processAutoSchedulingAfterLinkChanges: false,
        draggedTaskOriginalObject: null,
        rangeStartDate: moment($("#range_start_date").val()).toDate(),
        rangeEndDate: moment($("#range_end_date").val()).toDate(),
        gridWidthSetting: 1,
        baselinesVisible: false,
        deadlinesVisible: false,
        taskBeingDragged: false,
        draggedTaskEndDate: null,
        globalNonWorkingDays: [],
        activeUserGroupID: 0,
        userGroups: null,
        defaultPermissionGroups: null,
        teamMembers: [],
        summaryTaskArray: [],
        suppressParentUpdateID: null,
        linkTaskAfterInsert: false,
        newObjectType: null,
        suppressDynamicTaskMode: false,
        periodDescriptor: 1,
        taskInsertionMethod: 1,
        periodDescriptorTextSingular: null,
        periodDescriptorTextPlural: null,
        resourceColumnArray: [],
        snapshotData: null,
        lastSnapshotTime: 0,
        activityPrimaryGUID: null,
        activitySecondaryGUID: null,
        activityDescription: null,
        activityAdditionalDescription: null,
        activityAction: null,
        activityType: null,
        activityInfo: null,
        originalTaskEditorObject: null,
        beforeTaskEditorForm: null,
        rollbackActive: false,
        lastSaveTime: moment().unix() + 1,
        activeTaskResources: [],
		  activeCalendarOverrides: [],
        reloadPageAfterScheduling: 0,
        currentZoomLevel: "day", // was week
        testDTPicker: null,
        autoSchedulerRunning: false,
        filterType: 1,
        filterValue: "",
        resourceConfig: null,
      };

  gantt.config.start_date = window.ibex_gantt_config.rangeStartDate;
      gantt.config.end_date = window.ibex_gantt_config.rangeEndDate;
  
  gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
      gantt.config.show_errors = false;
      gantt.config.order_branch = false;
      gantt.config.order_branch_free = false;
  
   function shouldHighlightResource(resource) {

        var selectedTaskId = gantt.getState().selected_task;
        if (gantt.isTaskExists(selectedTaskId)) {
          var selectedTask = gantt.getTask(selectedTaskId),
            selectedResource = selectedTask[gantt.config.resource_property];

          if (resource.id == selectedResource) {
            return true;
          } else
          if (gantt.$resourcesStore.isChildOf(selectedResource, resource.id)) {
            return true;
          }
        }
        return false;
      }

		
      var resourceTemplates = {
        grid_row_class: function(start, end, resource) {
          var css = [];
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("folder_row");
            css.push("group_row");
          }
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          return css.join(" ");
        },
        task_row_class: function(start, end, resource) {
          var css = [];
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("group_row");
          }

          return css.join(" ");

        }
      };

  gantt.config.layout = {
          css: "gantt_container",
          rows: [{
              gravity: 2,
              id: "main-gantt", // Added by RB 02.12.18
              cols: [{
                  view: "grid",
                  group: "grids",
                  scrollY: "scrollVer",
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "timeline",
                  scrollX: "scrollHor",
                  scrollY: "scrollVer"
                },
                {
                  view: "scrollbar",
                  id: "scrollVer",
                  group: "vertical"
                }
              ]
            },
            {
              resizer: true,
              width: 1,
              next: "resources-gantt"
            },
            {
              gravity: 1,
              id: "resources-gantt",
              config: window.ibex_gantt_config.resourceConfig,
              templates: resourceTemplates,
              cols: [{
                  view: "resourceGrid",
                  group: "grids",
                  scrollY: "resourceVScroll"
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "resourceTimeline",
                  scrollX: "scrollHor",
                  scrollY: "resourceVScroll"
                },
                {
                  view: "scrollbar",
                  id: "resourceVScroll",
                  group: "vertical"
                }
              ]
            },
            {
              view: "scrollbar",
              id: "scrollHor"
            }
          ]
        };
  
</script>