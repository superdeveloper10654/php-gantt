<div id="ibex-support" class="tab-content" style="display: none">
    <div id="support-home-wrapper" style="display: none">
      <button type="button" class="close-support" id="close-support-home"><span aria-hidden="true"><img src="img/svg/close.svg"> &nbsp; Close</span></button>
      <h1>Ibex Support </h1>
      <h6>How can we help you, <?=$_SESSION['user']['first_name']?>?</h6>
      <p class="small text-muted">support@ibex.software</p>
      <br>
      <hr>
      <div class="row">
        <div class="col support">
          <h5>
            Getting Started
          </h5>
          <p id="get-started-1">About Ibex Gantt</p>
          <p id="get-started-2">How Ibex works</p>
          <p id="get-started-3">Using Ibex's features</p>
          <p id="get-started-4">Glossary & definitions</p>
          <p id="get-started-5">Managing your account</p>
        </div>
        <div class="col support">
          <h5>
            Article Topics
          </h5>
          <p class="open-articles-scheduling">Scheduling</p>
          <p>Resourcing</p>
          <p>Collaborating</p>
          <p>Administrating</p>
        </div>
      </div>
    </div>
    <div id="support-articles-wrapper" style="display: none">
      <button type="button" class="close-support close-support-article"><span aria-hidden="true"><img src="img/svg/close.svg"> &nbsp; Close</span></button>
      <h1>Support Articles </h1>
      <h6>Let's get you some help</h6>
      <p class="small text-muted">support@ibex.software</p>
      <br>
      <hr>
      <div class="row">
        <div class="col support">
          <h5>
            Scheduling
          </h5>
          <p id="open-article-1">Using the <strong>Task Editor</strong></p>
          <p>Working with task <strong>Calendars</strong></p>
          <p>Switching between <strong>Duration Units</strong></p>
          <p>Inserting <strong>Projects</strong>, <strong>Tasks</strong> and <strong>Milestones</strong></p>
          <p>Working with <strong>Dependencies</strong></p>
          <p>Scheduling a task's<strong> Start</strong> and <strong>Duration</strong></p>
          <p>Managing <strong> Workloads</strong> and <strong> Progress</strong></p>
          <p>Using <strong> Baselines</strong></p>
        </div>
        <div class="col support">
          <h5>
            Resourcing
          </h5>
          <p>Using the resources editor</p>
          <p>Allocating resources to tasks</p>
          <p>Adding resource groups</p>
          <p>Adding resource items</p>
          
        </div>
        <div class="col support">
          <h5>
            Collaborating
          </h5>
          <p>Working with others in real-time</p>
          <p>Using the activity feed</p>
          <p>Sending direct messages</p>
          <p>Recieving direct messages</p>
          <p>Recieving broadcast messages</p>
          <p>Working with files</p>
          <p>Adding your comments </p>
        </div>
        <div class="col support">
          <h5>
            Administrating
          </h5>
          <p>Tweaking your account settings</p>
          <p>Managing your account subscriptions</p>
          <p>Broadcasting messages to your teams</p>
        </div>
      </div>
  </div>
    <div class="support-article" id="article-1" style="display: none">
      <button type="button" class="close-support close-support-article"><span aria-hidden="true"><img src="img/svg/close.svg"> &nbsp; Close</span></button>
      <h2>Using the Task Editor</h2>
      <h6>How to open the Task Editor and use it effectively</h6>
      <hr>
      <div class="row" style="text-align: left;">
        <div class="col-md-4 support">
          <p class="support-breadcrumbs"><span class="">Ibex Support</span> > <span class="">Support Articles</span> > <span class="open-articles-scheduling opened">Scheduling</span></p>
          <br>
          <h6>
            Related Articles
          </h6><br>
          <p>Working with task <strong>Calendars</strong></p>
          <p>Switching between <strong>Duration Units</strong></p>
          <p>Inserting <strong>Projects</strong>, <strong>Tasks</strong> and <strong>Milestones</strong></p>
          <p>Working with <strong>Dependencies</strong></p>
          <p>Scheduling a task's<strong> Start</strong> and <strong>Duration</strong></p>
          <p>Managing <strong> Workloads</strong> and <strong> Progress</strong></p>
          <p>Using <strong> Baselines</strong></p>
        </div>
        <div class="col-md-8 article-content"><br>
          <h5>Opening the Task Editor</h5>
          <p>To open the Task Editor, simply double-click on the row of the task which you want to edit.<br>
              Once you've finished editing, you may either click on the <i>Save</i> button to save your changes to the task's information or simply click on the cross in the top-right corner to dismiss the editor without saving your changes (if any).</p>
          <p>In the example below, we've clicked on the demo project's <i>Excavations</i> task, then clicked on the Task Editor's <i>General</i> section header.</p>
          <p>This opens the General information about this task, which is the absolute minimum that Ibex needs to schedule it.</p>
          <img class="article-image" src="img/gif/Open-Task-Editor.gif">
          <h5>The General section</h5>
          <p>This entry is a <i>Task</i>, which represents an item of work with the following general information.</p>
          <p>Required:</p>
          <ul>
            <li>Type</li>
            <li>Calendar</li>
            <li>Name</li>
            <li>Start Date</li>
            <li>Start Time</li>
            <li>Duration</li>
          </ul>
          <p>Optional:</p>
          <ul>
            <li>Bar Colour</li>
          </ul>
          <p>The Type can be either a Project, Task or Milestone.</p>
          <p>The demo project uses a default Calendar to the determine the appropriate ranges for the Start Date & Time.</p>
          <p>Ibex automagically calculates the Finish Date & Time according to the Start Date & Time and the Duration.</p>
          <p>The Duration may be entered as a known figure or this can be calculated using the Workload and Resources.</p>
          <p>The Duration unit may be toggled to show the Duration as either hours & minutes, days, nights, shifts or custom units (singular and plural).<br></p>
          <hr>
          <h5>The Workload section</h5>
          <p>We've developed the Workload section to help you quantify the <i>effort</i> required to complete this task.</p>
          <p>You will see that Total Quantity is the sum of the quantities per working day below.<br>This task <i>Excavations</i> is scheduled for 5 days, so Ibex has added these ready for you to populate.</p>
          <p>In the Unit of Measure, we've listed the most common units for you to select from.</p>
          <p>For example, your task's Workload may entail 5,000 m3 of excavating.<br>You may enter this as the Total Quantity figure, then click on <i>Distribute Workload evenly?</i> to divide this 5,000 evenly as 1,000 per day.<br>
            Or you may choose to enter the daily quantities as say 800; 800; 1,200; 1,200; 1,000 (Ibex will add-up the Total Quantity for you).</p>
          <p>Next to each daily quantity, you will see the Status indicator for each working day:</p>              
          <p><img src="img/svg/check-complete.svg" style="padding: 5px;"> Workload quantity should have been completed by now</p>
          <p><img src="img/svg/in-progress.svg" style="padding: 5px;"> Workload quantity should be completed today</p>
          <p><img src="img/svg/pending.svg" style="padding: 5px;"> Workload quantity is pending (due soon)</p>
          <p>Next to the working day, you will see the Workload target icon:<img src="img/svg/crosshairs.svg" style="padding: 5px;"> Click on this to add a Workload target for that day.</p>
          <p>Select the target period (hour [restricted to follow this task's Calendar times] and target minute). Enter the target quantity then click 'Add' to create that target. To create multiple targets, simply repeat this process.</p>
          <p>Once the target period has passed, you will see this icon:<img src="img/svg/check-complete-time.svg" style="padding: 5px;"></p>
          <hr>
          <h5>The Resources section</h5>
          <p>You may allocate a resource group to this task by selecting one from the list. <br>Our demo project has 4 resource groups, being People; Vehicles; Plant and Misc.<br>You may create your own Resource groups to cluster your Resource items together to make it easier to schedule them.</p>
          <p>Click on <i>New Resource group?</i> to jump to the Resource Groups Editor.</p>
          <p>You may also choose to add individual Resource items by finding them in the search bar, then selecting each one in turn.</p>
          <p>Click on <i>New Resource item?</i> to jump to the Resources tab. We recommend that you check your current Resource items, Resource Groups and Resource Calendars before you create a new Resource item.</p>
        <hr>
          <h5>Calculating the Duration</h5>
        <p>Now that we've covered the Workload and Resources sections, let's explore how we can get Ibex to calculate this task's Duration.</p>
        <p>Remember that our demo project has this task (<i>Excavations</i>) scheduled for 5 days, but is this realistic? How would we know?</p>
        <p>This is where we consider the Workload and the Resources that we have allocated to undertake this 5,000 m3 of excavating. </p>
          <p>For Ibex to calculate the Duration, it needs to match-up this task's allocated Resource Group with it's Workload. <br>Ibex actually needs the units of measure need to match.</p>
          <p>Let's open your account in a new browser tab or window, so that you get hands-on experience with calculating the Duration.</p>
          <p>Are you ready? Good ..</p>
          <p>Open the Resources tab, then click on the <i>New Group</i> button to open the Resource Groups Editor.<p>
          <p>Enter 'Excavating Crew' as the Group name, then scroll down to the Outputs section to enter '800' as the min ouput value and '1000' as the max output value. Select 'Cubic Meter (m3)' as the unit of measure, and select 'Day (day)' as the time period. <br>Click 'Save' to save this Resourc Group.</p>
          <p>Click on the 'View Groups' button to check that it has saved properly.</p>
          <p>You should now have a new Resource Group called 'Excavating Crew' which is capable of outputs between 800 to 1,000 m3 per day.</p>
          <p>The next step is to add Resource Items to that new Resource Group. Let's edit some existing demo Resource items for simplicity.</p>
          <p>In the Resources Tab, you will see the Resource Items displayed in a card formation. <br>One at a time, click on Foreman, Operative #1, Operative #2 and 360 Mini Excavator to open their respective Resource Editors.<br>Click the General section header to edit the Group. Select 'Excavating Crew' then click 'Save'.</p>
      <p>Make your way to the Gantt tab, then scroll down to open Task Editor for 'Excavations'.</p>
          <p>Click on the Workload section header then enter the Workload as '8000' and select 'Cubic Meter (m3)'.<br>Note that you're increasing the Quantity from 5,000 m3.</p>
          <p>Click on the Resources section header then select 'Excavating Crew' as the Resource Group.</p>
          <p>Click on the General section header then click <i>Auto-calculate Duration?</i> which will save this task, and reload the Gantt with this task's new duaration, being 8 days.</p>
          <p>If you go back to the Workload section then click on <i>Distribute Workload evenly?</i> you'll see the 8,000 m3 gets entered into the 8 days below as 1,000 per day.</p>
          <p>Note that individal Resource items will not affect this calculation - Ibex only considers your Resource Groups because the max outputs are required. Make sure that this task's Resource group only has Resource items that have outputs. If you have Resource items without outputs which won't affect the Duration (e.g. materials), then you should allocate them as individal Resource items.</p>
        </div>
  </div>
</div>
</div>
