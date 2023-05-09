<div id="account-setup" class="tab-content" style="display: none">
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-welcome" id="welcome-tab" aria-labelledby="welcome-tab">
    <div id="welcome-wrapper">
      <h1>Welcome to Ibex!</h1><br>
      <h6>Let's setup your Ibex account, starting with your name
      </h6>
      <p>We've automated our setup process to help you get started</p><br>
      <div class="row">
                  <div class="col-md-4 mx-auto">
                    <div class="md-form">
                      <input type="text" id="setup_first_name" class="form-control" value="<?=$_SESSION['user']['first_name']?>">
                      <label for="setup_first_name" class="active">First Name</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mx-auto">
                    <div class="md-form">
                      <input type="text" id="setup_last_name" class="form-control" value="<?=$_SESSION['user']['last_name']?>">
                      <label for="setup_last_name" class="active">Last Name</label>
                    </div>
                  </div>
                </div>
                       <div class="row" style="margin: 20px 0 0 0"> 
                         <button class="mx-auto" id="continue_setup_calendars">Continue</button>
                      </div>                 
        </div>
      </div>

  
  
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-setup-calendars" id="setup-calendars-tab" aria-labelledby="setup-calendars-tab">
    <div id="setup-calendars-wrapper">
      <h1>Calendars</h1><br>
      <h6>We've created a couple of default calendars
      </h6>
      <p>You can edit these or create your own later</p><br>
      <div class="row">
      <table id="table_calendars" class="col-md-4 mx-auto table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
              </table>
    </div>
      <div class="row" style="margin: 20px 0 0 0"> 
                         <button class="mx-auto continue_setup_resources_groups" id="continue_setup_resources">Continue</button>
                      </div>             
  </div>
  </div>
  
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-setup-resources" id="setup-resources-tab" aria-labelledby="setup-resources-tab">
    <div id="setup-resources-wrapper">
      <h1>Resources</h1><br>
      <h6>Here's your default resource group and resource item
      </h6>
      <p>You can edit these or create your own later</p><br>
      <div class="row">
        <table id="setup_table_resource_groups" class="col-md-4 mx-auto table">
              <thead>
                <tr>
                  <th>Resource group</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
      </div>
      <div class="row">
      <table id="setup_table_resources" class="col-md-4 mx-auto table">
                <thead>
                <tr>
                  <th>Resource item</th>
                </tr>
              </thead>
        <tbody>
              </table>
    </div>
      <div class="row" style="margin: 20px 0 0 0"> 
                         <button class="mx-auto" id="continue_setup_first_project">Continue</button>
                      </div>             
  </div>
  </div>
  
  
  
  
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-setup-first-project" id="setup-first-project-tab" aria-labelledby="setup-first-project-tab">
    <div id="setup-first-project-wrapper">
      <h1>First Project</h1><br>
      <h6>Let's get started with your first project
      </h6>
      <p>You can edit these or create your own later</p><br>
      <div class="row">
                  <div class="col-md-4 mx-auto">
                    <div class="md-form">
                      <input type="text" id="setup_first_project" class="form-control" placeholder="Give your project a name">
                      <label for="setup_first_project" class="active">Project name</label>
                    </div>
                  </div>
                </div>
                       <div class="row" style="margin: 20px 0 0 0"> 
                         <button class="mx-auto" id="continue_setup_finish">Finish</button>
                      </div>                 
        </div>
      </div>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-setup-experience" id="setup-experience-tab" aria-labelledby="setup-experience-tab">
    <div id="setup-experience-wrapper">
      <h1>Your Experience</h1><br>
      <h6>How much experience do you have with project management software?
      </h6>
      <p>This includes scheduling techniques and allocating resources</p><br>
      <div class="row">
        <button class="col setup-answer mx-auto" id="continue_no_experience">
          <img class="setup no-experience" src="img/svg/no-experience.svg">
          <h4>No experience</h4>
          <p>You need to start from scratch</p>
        </button>
         <button class="col setup-answer mx-auto" id="continue_some_experience">
          <img class="setup some-experience" src="img/svg/some-experience.svg">
          <h4>Some experience</h4>
          <p>You just need some guidance</p>
        </button>
         <button class="col setup-answer mx-auto" id="continue_lots_experience">
          <img class="setup lots-experience" src="img/svg/lots-experience.svg">
          <h4>Lots of experience</h4>
          <p>You know what you're doing</p>
        </button>
      </div>
    </div>
  </div>
  
  
  
  
  
  
  
  
  
  
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-defaults-or-tweaks" id="defaults-or-tweaks" aria-labelledby="defaults-or-tweaks-tab">
    <div id="defaults-or-tweaks-wrapper">
      <h1>Scheduling Defaults</h1><br>
      <h6>Would you like to use our default settings or make your own tweaks?
      </h6>
      <p>These determine how Ibex schedules your tasks</p><br>
      <div class="row">
        <div class="col setup-answer mx-auto continue_to_work_envOLD">
          <img class="setup settings-check" src="img/svg/use-defaults.svg">
          <h4>Use defaults</h4>
          <p>Load our default settings (you can change them later)</p>
        </div>
        <div class="col setup-answer mx-auto" id="continue_make_tweaks">
          <img class="setup settings-tweak" src="img/svg/tweak.svg">
          <h4>Make tweaks</h4>
          <p>Tweak the default settings to suit your own needs</p>
        </div>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-has-no-experience" id="has-no-experience" aria-labelledby="has-no-experience-tab">
    <div id="has-no-experience-wrapper">
      <h3>No worries, <span class="capitalise"><?=$_SESSION['user']['first_name']?></span>!
      </h3><br>
      <h6>We'll load our support pages to help you learn how Ibex works
      </h6>
      <p>We'll take you through some basic scheduling techniques</p><br>
      <div class="row">
        <button class="mx-auto load_support">Continue</button>
      </div>
      <div class="row">
        <a class="mx-auto small go-back-experience">Go back</a>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-has-some-experience" id="has-some-experience" aria-labelledby="has-some-experience-tab">
    <div id="has-some-experience-wrapper">
      <h3>That's great, <span class="capitalise"><?=$_SESSION['user']['first_name']?></span>!
      </h3><br>
      <h6>You have some experience, but would you like to test your knowledge?
      </h6>
      <p>We'll take you through some questions to check what you need</p><br>
      <div class="row">
        <button class="mx-auto btn-green" id="continue_load_quiz">Continue</button>
      </div>
      <div class="row">
        <a class="mx-auto small go-back-experience">Go back</a>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-quiz" id="quiz" aria-labelledby="quiz-tab">
    <div id="quiz-wrapper">
      <div id="quiz-into">
        <h3>Quiz</h3><br>
        <h6>Let's test your knowledge and understanding of project management techniques
        </h6>
        <p>We'll take you through some quetions with multiple-choice answers</p><br>
        <div class="row">
          <button class="mx-auto btn-green" id="continue_load_quiz">Continue</button>
        </div>
        <div class="row">
          <a class="mx-auto small go-back-experience">Go back</a>
        </div>
      </div>
      <div id="quiz-question-1" style="display: none">
        <h3>Calendars</h3><br>
        <h6>Calendars are used to define the working and non-working period/s of your tasks
        </h6>
        <p>Please select one answer below</p><br>
        <div class="row">
          <button id="quiz_answer_1a">True</button>
          <p class="quiz-reveal-text quiz-question-1-reveal-correct" style="display: none">Yes, it's true</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_1b">False</button>
          <p class="quiz-reveal-text quiz-question-1-reveal-incorrect" style="display: none">Sorry, try again</p>
        </div>
        <br>
        <br>
        <div class="row">
          <p class="info" id="quiz_question_1_info" style="display: none">You may create multiple calendars, and apply them to each task as needed</p>
        </div>
        <br>
        <div class="row">
          <button class="mx-auto btn-green" id="quiz_question_1_next" style="display: none">Next</button>
        </div>
      </div>
      <div id="quiz-question-2" style="display: none">
        <h3>Dependency Types</h3><br>
        <h6>Which of the following is a <i>Finish to Start</i> dependency?
        </h6>
        <p>Please select one answer below</p><br>
        <div class="row">
          <button id="quiz_answer_2a">Once task B has started, task A may finish</button>
          <p class="quiz-reveal-text quiz-question-2a-reveal-incorrect" style="display: none">Sorry, try again</p>
          <p class="quiz-reveal-text" id="quiz_question_2a_hint" style="color: #999 !important; display: none">This is a <i>Start to Finish</i> dependency</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_2b">Once task A has started, task B may start</button>
          <p class="quiz-reveal-text quiz-question-2b-reveal-incorrect" style="display: none">Sorry, try again</p>
          <p class="quiz-reveal-text" id="quiz_question_2b_hint" style="color: #999 !important; display: none">This is a <i>Start to Start</i> dependency</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_2c">Once task A is finished, task B may start</button>
          <p class="quiz-reveal-text quiz-question-2c-reveal-correct" style="display: none">Yes, that's correct</p>
        </div>
        <br>
        <br>
        <div class="row">
          <p class="info" id="quiz_question_2_info" style="display: none"><i>Finish to Start</i> dependencies are the most common</p>
        </div>
        <br>
        <div class="row">
          <button class="mx-auto btn-green" id="quiz_question_2_next" style="display: none">Next</button>
        </div>
      </div>
      <div id="quiz-question-3" style="display: none">
        <h3>Predecessors</h3><br>
        <h6>A <i>predecessor</i> is a task which is scheduled to start ..
        </h6>
        <p>Please select one answer below</p><br>
        <div class="row">
          <button id="quiz_answer_3a">before a successor task</button>
          <p class="quiz-reveal-text quiz-question-3-reveal-correct" style="display: none">Yes, that's correct</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_3b">after a successor task</button>
          <p class="quiz-reveal-text quiz-question-3-reveal-incorrect" style="display: none">Sorry, try again</p>
        </div>
        <br>
        <br>
        <div class="row">
          <p class="info" id="quiz_question_3_info" style="display: none">A predecessor will always be scheduled to start before a successor</p>
        </div>
        <br>
        <div class="row">
          <button class="mx-auto btn-green" id="quiz_question_3_next" style="display: none">Next</button>
        </div>
      </div>
      <div id="quiz-question-4" style="display: none">
        <h3>Lag & Lead</h3><br>
        <h6>Which of the following statements are true?
        </h6>
        <p>Please select one answer below</p><br>
        <div class="row">
          <button id="quiz_answer_4a"><i>Lag</i> means the intended delay between tasks</button>
          <p class="quiz-reveal-text quiz-question-4-reveal-correct" style="display: none">Yes, that's correct</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_4b"><i>Lead</i> means the intended delay between tasks</button>
          <p class="quiz-reveal-text quiz-question-4-reveal-incorrect" style="display: none">Sorry, try again</p>
        </div>
        <br>
        <br>
        <div class="row">
          <p class="info" id="quiz_question_4_info" style="display: none">You may add <i>Lead</i> to a dependency to start task B before task A finishes</p>
        </div>
        <br>
        <div class="row">
          <button class="mx-auto btn-green" id="quiz_question_4_next" style="display: none">Next</button>
        </div>
        <br>
      </div>
      <div id="quiz-question-5" style="display: none">
        <h3>Milestones</h3><br>
        <h6>Can a milestone have a duration with resources allocated to it?
        </h6>
        <p>Please select one answer below</p><br>
        <div class="row">
          <button id="quiz_answer_5a">Yes - a duration with resources</button>
          <p class="quiz-reveal-text quiz-question-5a-reveal-incorrect" style="display: none">Sorry, try again</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_5b">Yes - a duration, but not resources </button>
          <p class="quiz-reveal-text quiz-question-5b-reveal-incorrect" style="display: none">Sorry, try again</p>
        </div>
        <br>
        <div class="row">
          <button id="quiz_answer_5c">No duration or resources</button>
          <p class="quiz-reveal-text quiz-question-5c-reveal-correct" style="display: none">That's right</p>
        </div>
        <br>
        <br>
        <div class="row">
          <p class="info" id="quiz_question_5_info" style="display: none">A milestone will never have a duration or resources (it's not a task)</p>
        </div>
        <br>
        <div class="row">
          <button class="mx-auto btn-green" id="quiz_question_5_next" style="display: none">Next</button>
        </div>
      </div>
    </div>
    <div id="quiz-finished">
      <h3>Well done, <span class="capitalise"><?=$_SESSION['user']['first_name']?></span>!
      </h3><br>
      <h6>You've passed the quiz! You're almost ready to start scheduling
      </h6>
      <p>Let's check your scheduling settings</p><br>
      <div class="row">
        <button class="mx-auto continue_defaults_or_tweaks">Continue</button>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-has-lots-experience" id="has-lots-experience" aria-labelledby="has-lots-experience-tab">
    <div id="has-lots-experience-wrapper">
      <h1>Rock on!</h1>
      </h1><br>
      <h6>Great, you know what you're doing. Let's get you sorted
      </h6>
      <p>We just need to know a few more things to help you along</p><br>
      <div class="row">
        <button class="mx-auto continue_defaults_or_tweaks">Continue</button>
      </div>
      <div class="row">
        <a class="mx-auto small go-back-experience">Go back</a>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-work-environment" id="work-environment" aria-labelledby="work-environment-tab">
    <div id="work-environment-wrapper">
      <h1>Work Environment</h1><br>
      <h6>Would you like to collaborate with other people or work alone?
      </h6>
      <p>This includes direct messaging and real-time team editing</p><br>
      <div class="row">
        <button class="col setup-answer mx-auto continue_to_collaboration">
          <img class="setup" src="img/svg/invite-others.svg">
          <h2>Invite others</h2>
          <p>Bring your people to your account</p>
        </button>
        <!--<div class="col setup-answer mx-auto continue_to_finish">-->
        <button class="col setup-answer mx-auto continue_create_project">
          <img class="setup" src="img/svg/work-alone.svg">
          <h2>Work alone</h2>
          <p>Keep this private (change anytime)</p>
        </button>
      </div>
    </div>
    <div id="setup-teams-wrapper">
      <h1>Your Teams</h1><br>
      <h6>Let's setup your teams and their access permissions</h6>
      <p>You can make changes to your teams later</p>
      <div class="row">
      <div class="col-md-4 mx-auto">
        
      <div class="md-form">
        <p class="text-muted" style="margin: 5px; text-align: left;">Enter a team name, one at a time</p>
                        <input type="text" id="setup_new_user_group_name" class="form-control" placeholder="e.g. Electricians">
                        <span class="input-group-append input-group-text setup-add-user-group">Add</span>
                    </div>
        </div></div>
                       <div class="row">
      <div class="col-md-4 mx-auto" style="height: 200px; overflow: auto; margin-top: 20px;">
                        <p class="text-muted" style="margin: 5px; text-align: left;">These are your teams:</p>
                         <table id="setup_table_groups" class="table table-sm">
                                  <tbody>
                                    <?php
								 foreach ($user_groups as $user_group)
								 {
									 ?>
                                      <tr>
                                        <td style="">
                                          <?=$user_group['name']?>
                                        </td>
                                        <td style="float: right;">
                                          <?php if(isset($user_group[' id '])) { ?>
                                            <div class='' style='float: right;' data-index='<?=$user_group[' id ']?>'></div>
                                            <div class='delete-group' data-index='<?=$user_group[' id ']?>'><img class="action-icons" src="img/svg/bin-1.svg"></div>
                                          <?php } ?>
                                        </td>
                                      </tr>
                                      <?php
							 }
							 ?>
                                  </tbody>
                                </table>
                      </div>                 
        </div>
      <div class="row"> 
                        <button class="mx-auto continue_to_invite" style="margin-top: 20px;">Continue</button>
                      </div>
  </div>
    <div id="setup-invite-wrapper">
      <h1>Invite People</h1><br>
      <h6>Let's invite others to join your account as team members</h6>
      <p>Simply enter their email addresses below to send their invites</p>
      <div class="row">
      <div class="col-md-4 mx-auto">
        
      <div class="md-form">
        <p class="text-muted" style="margin: 5px; text-align: left;">Enter their email addresses, one at a time</p>
                        <input type="text" id="setup_new_user_add_email_address" class="form-control" placeholder="e.g. someone@example.com">
                        <span class="input-group-append input-group-text setup-add-new-user">Add</span>
                    </div>
        </div></div>
                       <div class="row">
      <div class="col-md-4 mx-auto" style="height: 200px; overflow: auto; margin-top: 20px;">
                        <p class="text-muted" style="margin: 5px; text-align: left;">These are your team members:</p>
                         <table id="setup_table_group_members"></table>           
                         </div></div>
      <div class="row"> 
                        <!--<button class="mx-auto continue_to_finish" style="margin-top: 20px;">Continue</button>-->
        <button class="mx-auto continue_create_project" style="margin-top: 20px;">Continue</button>
                      </div>
  </div>
</div>

  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-setup-complete" id="setup-complete" aria-labelledby="setup-complete-tab">
    <div id="setup-complete-wrapper">
      <h1>Setup Complete</h1><br>
      <h6>Thank you <span class="capitalise"><?=$_SESSION['user']['first_name']?></span> for taking the time to complete your account setup
      </h6>
      <p>Would you like to see our demo project or shall we create a project from scratch? </p><br>
      <div class="row">
        <div class="col setup-answer mx-auto continue_load_demo">
          <img class="setup " src="img/svg/load-demo.svg">
          <h2>Demo project</h2>
          <p>We'll load our demo for you to play with</p>
        </div>
        <div class="col setup-answer mx-auto continue_create_project">
          <img class="setup " src="img/svg/new-project-construction.svg">
          <h2>New project</h2>
          <p>Let's create a new project from scratch</p>
        </div>
      </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane animated fadeIn tab-pane-create-project" id="create-project" aria-labelledby="create-project-tab">
    <div id="create-project-wrapper">
      <h1>Your Project</h1><br>
      <h6>Right then. Let's create your first project 
      </h6>
      <p>Please enter your project's name below </p><br>
      <div class="row">
        <div class="col-md-4 mx-auto">
                      <div class="md-form">
                        <p class="text-muted" style="margin: 5px; text-align: left;">Be descriptive but concise</p>
                        <input type="text" id="setup_project_name" name="Name" class="form-control task_edit_name" placeholder="e.g. St Mary's school refurb">
                        <span class="input-group-append input-group-text create-project">Next</span>
                      </div>                 

        </div>
      </div>
    </div>
  </div>
</div>