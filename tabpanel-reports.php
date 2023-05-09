<div role="tabpanel" class="tab-pane animated fadeIn" id="reports" aria-labelledby="files-tab" style="padding: 50px;">
  <div class="no-reports" style="display: none">
    <div class="no-reports-wrapper">
      <h1 style="color: <?=$_SESSION['user']['opacity_font']?>">Nothing to report yet</h1>
      <h6 style="color: <?=$_SESSION['user']['opacity_font']?>">
        <?=$_SESSION['user']['first_name']?>, please insert your first task
      </h6>
      <!--
      <div class="row no-files-add-now" style="margin-top: 50px;">
        <button class="col-md-4 mx-auto huge add-file add-file-unique pulse">
          <img class="setup " src="img/svg/file-upload-cloud.svg">
          <h4>Upload File</h4>
          <p>(no size limit)</p>
        </button>
        <input type="file" style="display: none" id="file_handler_unique" onchange='uploadUniqueFile()' divpt=".png, .jpg, .jpeg">
      </div>
-->
    </div>
  </div>

      <div class="col-md-12" style="padding: 0 0 20px 0;">
        <h4 id="task-text"></h4>
        <p>Here's your real-time task report</p>
        </div>
  

  <div class="row" style="padding: 0;">
    <div class="col-md-2" id="block-start-details">
      <div class="inner">
        <p class="block-status-widget" id="block-start-status"></p>
        <div class="block-title">Schedule</div>
        <div class="block-body">
          <p id="start-explanation" style="margin: 0"></p>
          <p><span id="start-date"></span><br><span id="start-time"></span></p>
          <p style="margin: 0; padding: 1rem 0 0 0; border-top: solid 1px #ddd;" id="finish-explanation"></p>
          <p><span id="finish-date"></span><br><span id="finish-time"></span></p>
          <p style="margin: 0; padding: 1rem 0 0 0; border-top: solid 1px #ddd;" id="duration-explanation"></p>
          <p><span id="duration-days-number"></span><br><span id="duration-hours-number"></span></p>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-3" id="block-actual-progress-gauge">
        <div class="inner" >
<p class="block-status-widget" id="block-progress-status-gauge"></p>
          <div class="block-title">
            Progress
          </div>
          <div class="block-body" style="text-align: center;">
          <div class="mx-auto">
            <div class="GaugeMeter" id="GaugeMeter_progress" data-fill="" data-back="#ddd" data-append="%" data-size="140" data-theme="Black" data-animate_gauge_colors=true
              data-animate_text_colors=true></div>
      </div>
            
          </div>
          </div>
      </div>
        <div class="col-md-9" id="block-actual-progress-chart">
        <div class="inner" >
<p class="block-status-widget" id="block-progress-status-chart"></p>
          <div class="block-title">
            Progress
          </div>
          <div class="block-body" style="text-align: center;">
          <div class="mx-auto">
            <div class="chart-holder" id="line1" ></div>
      </div>
            
          </div>
          </div>
      </div>
      </div>
    <div class="col" id="block-spi">
        <div class="inner" style="padding-bottom: 0;">
          <p class="block-status-widget" id="block-spi-status"></p>
          <div class="block-title">
            SPI
          </div>
          <div class="block-body" style="text-align: center;">
            <div class="GaugeMeter mx-auto" id="GaugeMeter_spi" data-back="#ddd" data-style="Arch" data-size="140" data-append="%" data-theme="Black" data-animate_gauge_colors=true data-animate_text_colors=true></div>
      <!--<div class="exp" id="schedule-efficiency-explanation"></div>-->
      </div>
      </div>
      </div>
    <div class="col" id="block-cpi">
        <div class="inner" style="padding-bottom: 0;">
          <p class="block-status-widget" id="block-cpi-status"></p>
          <div class="block-title">
            CPI
          </div>
          <div class="block-body" style="text-align: center;">
            <div class="GaugeMeter mx-auto" id="GaugeMeter_cpi" data-back="#ddd" data-style="Arch" data-size="140" data-append="%" data-theme="Black" data-animate_gauge_colors=true data-animate_text_colors=true></div>
        <!--<div class="exp" id="cost-efficiency-explanation"></div>-->
          </div>
        </div>
      </div>
  </div>
    <div class="col-md-12">
      <div class="row" style="padding: 30px 0;">
      <div class="col" id="block-workload">
        <div class="inner">
          <div class="block-title">
            Workload
          </div>
          <div class="block-body" style="padding-bottom: 0;">
            <p id="workload-progressed-quantity"></p>
            <p id="workload-total-quantity"></p>
            <p id="workload-unit"></p>
            
            <!--<small class="exp" id="workload-explanation" style="margin-top: 20px;"></small>-->
          </div>
          <div class="chart-holder-small" id="bar-workload" style="height: 55px;"></div>
        </div>
      </div>
        <div class="col" id="block-productivity">
        <div class="inner">
          <div class="block-title">
            Productivity
          </div>
          <div class="block-body">
          <p id="productivity"></div>
            <!--<div class="exp" id="productivity-explanation"></div>-->
        </div>
           </div>
      <div class="col" id="block-actual-cost">
        <div class="inner">
<div class="block-status-widget" id="block-actual-cost-status"></div>
          <div class="block-title">
            Actual Cost
          </div>
          <div class="block-body" style="padding-bottom: 0;">
          <p id="actual-cost"></p>
          <p id="final-cost"></p>
            <!--<div class="exp" id="actual-cost-explanation"></div>-->
        </div>
          <div class="chart-holder-small" id="bar-actual-cost" style="height: 55px;"></div>
             </div>
        </div>
          <div class="col" id="block-earned-value">
        <div class="inner">
          <div class="block-title">
            Earned value
          </div>
          <div class="block-body">
          <p id="earned-value"></p>
            <!--<div class="exp" id="earned-value-explanation"></div>-->
        </div>
         </div></div>
      </div>
      
      <div class="row" style="padding: 30px 0;">
        
      </div>
      
      </div>
  </div>
      <div class="col-md-6">

    <div class="col" id="block-resource-costs">
        <div class="inner">
          <div class="block-title">
            Resource Costs
          </div>
          <div class="block-body">
        <table id="table-resources-names" style="width: 100%;">
                  <tbody>
        </tbody>
    </table>    
<div class="chart-holder" id="bar1" ></div>
    </div>
  </div>
    </div>

    <div class="col" id="block-comments">
        <div class="inner">
          <div class="block-title">
            Comments
          </div>
          <div class="block-body">
      <table class="table table-hover" id="task_report_comments">
                        <tbody>
                        </tbody>
                      </table>
    </div>
  </div>
    </div>

    <div class="col" id="block-deadline">
        <div class="inner">
<div class="block-status-widget" id="block-deadline-status"></div>
          <div class="block-title">
            Deadline
          </div>
          <div class="block-body">
    <div id="reports-deadline" >
      <div id="deadline-date"></div>
      <div id="deadline-time"></div>
    </div>
          </div>
      </div>
        </div>
    
    </div>
    
  <div class="col-md-3" id="reports-sidebar">
        
      </div>
       

  <div class="row">
  
   
  <div class="col-md-6" style="padding-right: 0">
  
    <div class="block-container">    
    <div class="block" id="block-cost-analysis-chart">
        <div class="inner">
        <div class="block-title">
      Cost analysis
    </div>
          <div class="block-body" style="text-align: left;">
    
          </div>
          </div>
    </div>
      <div class="block" id="block-cost-analysis-legend">
        <div class="inner">
          <div class="block-body" style="text-align: left;">
            <div class="bar-legend">
              <div id="bar-legend-budget-at-completion"></div>
              <div id="bar-legend-final-cost"></div>
            <div id="bar-legend-baseline-value"></div>
            <div id="bar-legend-earned-value"></div>
            <div id="bar-legend-actual-cost"></div>
              <div id="bar-legend-cost-to-complete"></div>
              <div class="exp" id="bar-legend-explanation" style="height: auto;"></div>
        </div>
        </div></div>
    </div>
      
    
      
      
    </div>
     <div class="block-container">
    <div class="block col" id="block-resources">
      <div class="inner">
        <div class="block-title">
            Resources
          </div>
        <div class="block-body">
    
  </div>
      </div></div>
       
    </div>
    <div class="block-container" style="display: none;">
    <div class="block" id="block-files">
      <div class="inner">
        <div class="block-title">
            Files
          </div>
    <table id="table-files-names" class="table">
                  <tbody>
        </tbody>
    </table>
  </div>
    </div>
    </div>

</div>
    <div class="col-md-3" style="padding: 0">
    <div class="block" id="block-narrative" style="width: 100%; padding: 10px 30px 10px 10px;">
        <div class="inner">
        <div class="block-title">
      Narrative
    </div>
          <div class="block-body" style="text-align: left;">
        <table id="table-report-narrative" class="table">
                  <tbody>
        </tbody>
    </table>
          </div>
      </div>
    </div>
    <div class="block" id="block-calendar" style="width: 100%; padding: 10px 30px 10px 10px;">
        <div class="inner">
          <div class="block-title">
            Calendar
          </div>
          <div class="block-body">
      <div id="calendar-name"></div>
            <div id="calendar-details"></div>
            </div>
          </div>
          </div>
    
    </div>
  </div>
   <div id="actual-cost"></div>
            <div class="exp" id="actual-cost-explanation"></div>

           
     
<div class="block" id="block-baseline-progress">
        <div class="inner" style="padding-bottom: 0;">
          <div class="block-title">
            Baseline progress
          </div>
          <div class="block-body" style="padding:10px 0 0 0">
          <div class="mx-auto">
            <div class="GaugeMeter" id="GaugeMeter_baseline_progress" data-fill="" data-back="#ddd" data-append="%" data-size="140" data-theme="White" data-animate_gauge_colors=true
              data-animate_text_colors=true></div>
      </div>
        </div>
  </div></div>

      <div class="block" id="block-baseline-value">
        <div class="inner">
          <div class="block-title">
            Baseline value
          </div>
          <div class="block-body">
          <div id="baseline-value"></div>
          <div class="exp" id="baseline-value-explanation"></div>
        </div>
  </div></div>
      <div class="block" id="block-budget">
        <div class="inner">
          <div class="block-title">
            Budget
          </div>
          <div class="block-body">
          <div id="budget"></div>
            <div class="exp" id="budget-explanation"></div>
        </div>
           </div></div>

         

         <div class="block" id="block-final-cost">
        <div class="inner">
          <div class="block-title">
            Final cost
          </div>
          <div class="block-body">
          <div id="final-cost"></div>
            <div class="exp" id="final-cost-explanation"></div>
          </div></div>
      </div>
         <div class="block" id="block-cost-to-complete">
        <div class="inner">
          <div class="block-title">
            Cost to complete
          </div>
          <div class="block-body">
          <div id="cost-to-complete"></div>
            <div class="exp" id="cost-to-complete-explanation"></div>
          </div></div>
      </div>
     

   
        <div class="block" id="block-risk">
        <div class="inner">
        <div class="block-title">
      Risk Score
    </div>
          <div class="block-body">
    <div class="GaugeMeter mx-auto" id="GaugeMeter_risk_score" data-style="Arch" data-stripe="5" data-back="#ddd" data-size="140" data-theme="Green-Gold-Red" data-animate_gauge_colors=true data-animate_text_colors=true></div>
       <div class="exp" id="risk-explanation" style="margin-top: -20px;"></div>
          </div>
          
        </div></div>
      <div class="chart-holder" id="bar2" ></div>
  </div>
</div>