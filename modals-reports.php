<div class="modal animated fadeIn" id="modal_toggle_reports_blocks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Reports Blocks Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body" style="padding: 1.25rem 1rem;">
            <p>Choose which blocks to show in the reports</p>
            <table id="table_task_report-blocks" class="table table-sm">
              <tbody>
                <tr data-index="block-start-details">
                  <td><span>Start date & time</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-sta-det" onclick="toggleBlocksStartFunction()" checked>
                    <label class="form-check-label" for="block-sta-det" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-finish-details">
                  <td><span>Finish date & time</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-fin-det" onclick="toggleBlocksFinishFunction()" checked>
                    <label class="form-check-label" for="block-fin-det" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-duration-details">
                  <td><span>Duration</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-dur-det" onclick="toggleBlocksDurationFunction()" checked>
                    <label class="form-check-label" for="block-dur-det" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-calendar">
                  <td><span>Calendar</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-cal" onclick="toggleBlocksCalendarFunction()" checked>
                    <label class="form-check-label" for="block-cal" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-deadline">
                  <td><span>Deadline</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-dea" onclick="toggleBlocksDeadlineFunction()" checked>
                    <label class="form-check-label" for="block-dea" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-workload">
                  <td><span>Workload</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-wor" onclick="toggleBlocksWorkloadFunction()" checked>
                    <label class="form-check-label" for="block-wor" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-actual-progress">
                  <td><span>Actual progress</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-act-prog" onclick="toggleBlocksActualProgressFunction()" checked>
                    <label class="form-check-label" for="block-act-prog" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-baseline-progress">
                  <td><span>Baseline progress</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-bas-prog" onclick="toggleBlocksBaselineProgressFunction()" checked>
                    <label class="form-check-label" for="block-bas-prog" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-actual-cost">
                  <td><span>Actual cost</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-act-cost" onclick="toggleBlocksActualCostFunction()" checked>
                    <label class="form-check-label" for="block-act-cost" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-baseline-value">
                  <td><span>Baseline value</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-bas-val" onclick="toggleBlocksBaselineValueFunction()" checked>
                    <label class="form-check-label" for="block-bas-val" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-actual-cost">
                  <td><span>Earned value</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-ear-val" onclick="toggleBlocksEarnedValueFunction()" checked>
                    <label class="form-check-label" for="block-ear-val" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-productivity">
                  <td><span>Productivity</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-prod" onclick="toggleBlocksProductivityFunction()" checked>
                    <label class="form-check-label" for="block-prod" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-estimate-at-completion">
                  <td><span>Estimate at completion</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-eac" onclick="toggleBlocksEstimateAtCompletionFunction()" checked>
                    <label class="form-check-label" for="block-eac" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-estimate-to-complete">
                  <td><span>Estimate to complete</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-etc" onclick="toggleBlocksEstimateToCompleteFunction()" checked>
                    <label class="form-check-label" for="block-etc" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-schedule-variance">
                  <td><span>Schedule variance</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-sch-var" onclick="toggleBlocksScheduleVarianceFunction()" checked>
                    <label class="form-check-label" for="block-sch-var" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-cost-variance">
                  <td><span>Cost variance</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-cost-var" onclick="toggleBlocksCostVarianceFunction()" checked>
                    <label class="form-check-label" for="block-cost-var" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-schedule-efficiency">
                  <td><span>Schedule efficiency</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-sch-eff" onclick="toggleBlocksScheduleEfficiencyFunction()" checked>
                    <label class="form-check-label" for="block-sch-eff" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="block-cost-efficiency">
                  <td><span>Cost efficiency</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="block-cost-eff" onclick="toggleBlocksCostEfficiencyFunction()" checked>
                    <label class="form-check-label" for="block-cost-eff" class="label-table"></label>
                  </td>
                </tr>

              </tbody>
            </table>


          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal animated fadeIn" id="modal_toggle_reports_design_modes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-info" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Reports Design Editor</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body" style="padding: 1.25rem 1rem;">
            <p>Choose which design modes to show in the reports</p>
            <table id="table_reports_design_modes" class="table table-sm">
              <tbody>
                <tr data-index="">
                  <td><span>Dark mode</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="darkmode" checked>
                    <label class="form-check-label" for="darkmode" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Masonry layout</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="masonrylayout" checked>
                    <label class="form-check-label" for="masonrylayout" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Block titles</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="blocktitles" checked>
                    <label class="form-check-label" for="blocktitles" class="label-table"></label>
                  </td>
                </tr>
              </tbody>
            </table>
            <p>Choose which explanations to show in the blocks</p>
            <table id="table_reports_explanations" class="table table-sm">
              <tbody>
                <tr data-index="">
                  <td><span>Workload</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="workload-exp" onclick="toggleEWorkloadExplanationFunction()" checked>
                    <label class="form-check-label" for="workload-exp" class="label-table"></label>
                  </td>
                </tr>
                 <tr data-index="">
                  <td><span>Baseline value</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="baseline-value-exp" onclick="toggleBaselineValueExplanationFunction()" checked>
                    <label class="form-check-label" for="baseline-value-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Earned value</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="earned-value-exp" onclick="toggleEarnedValueExplanationFunction()" checked>
                    <label class="form-check-label" for="earned-value-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Actual cost</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="actual-cost-exp" onclick="toggleActualCostExplanationFunction()" checked>
                    <label class="form-check-label" for="actual-cost-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Schedule variance</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="schedule-variance-exp" onclick="toggleScheduleVarianceExplanationFunction()" checked>
                    <label class="form-check-label" for="schedule-variance-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Cost variance</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="cost-variance-exp" onclick="toggleCostVarianceExplanationFunction()" checked>
                    <label class="form-check-label" for="cost-variance-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Risk score</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="risk-exp" onclick="toggleRiskExplanationFunction()" checked>
                    <label class="form-check-label" for="risk-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Productivity</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="productivity-exp" onclick="toggleProductivityExplanationFunction()" checked>
                    <label class="form-check-label" for="productivity-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Estimate at completion</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="estimate-at-completion-exp" onclick="toggleEstimateAtCompletionExplanationFunction()" checked>
                    <label class="form-check-label" for="estimate-at-completion-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Estimate to complete</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="estimate-to-complete-exp" onclick="toggleEstimateToCompleteExplanationFunction()" checked>
                    <label class="form-check-label" for="estimate-to-complete-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Schedule efficiency</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="schedule-efficiency-exp" onclick="toggleScheduleEfficiencyExplanationFunction()" checked>
                    <label class="form-check-label" for="schedule-efficiency-exp" class="label-table"></label>
                  </td>
                </tr>
                <tr data-index="">
                  <td><span>Cost efficiency</span></td>
                  <td>
                    <input class="form-check-input" type="checkbox" id="cost-efficiency-complete-exp" onclick="toggleCostEfficiencyExplanationFunction()" checked>
                    <label class="form-check-label" for="cost-efficiency-complete-exp" class="label-table"></label>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close">Save</button>
      </div>
    </div>
  </div>
</div>