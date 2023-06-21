<!-- Modal -->
<div class="modal fade" id="addworkflowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Workflow Trigger</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Adds a workflow trigger, and on execution, the contact gets added to the workflow</p>
                <input type="search" class="form-control" name="search_trigger" placeholder="Search Triggers" />
                <h6 class="mt-2">Appointment</h6>
                <div class="input-group mb-3" data-trigger-id="appointment status"
                    data-trigger-name="Appointment Status">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Appointment Status" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="customer booked appointment"
                    data-trigger-name="Customer Booked Appointment">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-check-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Customer Booked Appointment" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <h6>Contact</h6>
                <div class="input-group mb-3" data-trigger-id="birthday reminder" data-trigger-name="Birthday Reminder">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Birthday Reminder" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="contact changed" data-trigger-name="Contact Changed">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Contact Changed" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="contact created" data-trigger-name="Contact Created">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user-plus"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Contact Created" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="contact dnd" data-trigger-name="Contact DND">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-times-circle"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Contact DND" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="contact tag" data-trigger-name="Contact Tag">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-tag"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Contact Tag" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="custom date reminder"
                    data-trigger-name="Custom Date Reminder">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Custom Date Reminder" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="note added" data-trigger-name="Note Added">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Note Added" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="task added" data-trigger-name="Task Added">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Task Added" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
                <div class="input-group mb-3" data-trigger-id="task reminder" data-trigger-name="Task Reminder">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar-o"></i></span>
                    <input type="text" class="form-control select_trigger" style="cursor: pointer;" readonly
                        value="Task Reminder" data-bs-toggle="modal" data-bs-target="#addtriggerModal" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addtriggerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Workflow Trigger</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Adds a workflow trigger, and on execution, the contact gets added to the workflow</p>
                <h6>Choose a workflow trigger</h6>
                <select class="form-select mb-3" id="choose_trigger">
                    <option value="Appointment Status">Appointment Status</option>
                    <option value="Customer Booked Appointment">Customer Booked Appointment</option>
                    <option value="Birthday Reminder">Birthday Reminder</option>
                    <option value="Contact Changed">Contact Changed</option>
                    <option value="Contact Created">Contact Created</option>
                    <option value="Contact DND">Contact DND</option>
                    <option value="Contact Tag">Contact Tag</option>
                    <option value="Custom Date Reminder">Custom Date Reminder</option>
                    <option value="Note Added">Note Added</option>
                    <option value="Task Added">Task Added</option>
                    <option value="Task Reminder">Task Reminder</option>
                </select>
                <h6>Workflow Trigger Name</h6>
                <input type="hidden" value="new" id="trigger_index" class="form-control mb-3" />
                <input type="text" value="" id="selected_trigger_value" class="form-control mb-3" />
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deletetrigger d-none"
                        onclick="deleteTrigger(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_trigger">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h4 modal-title">
                    Actions
                    <p>Pick an action for this step.</p>
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="search" class="form-control" name="search_action" placeholder="Search Action" />
                <h6 class="mt-2">IVR Responses</h6>
                <div class="input-group mb-3" data-action-id="send email" data-action-name="Send Email">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Send Email"
                        data-bs-toggle="modal" data-bs-target="#emailactionModal" />
                </div>
                <div class="input-group mb-3" data-action-id="send sms" data-action-name="Send SMS">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-comment-o"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Send SMS"
                        data-bs-toggle="modal" data-bs-target="#smsactionModal" />
                </div>
                <div class="input-group mb-3" data-action-id="call forward" data-action-name="Call_Forward">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-mail-forward"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Call Forward"
                        data-bs-toggle="modal" data-bs-target="#callforwardactionModal" />
                </div>
                <div class="input-group mb-3" data-action-id="call" data-action-name="Call_Recording">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-phone"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Call Recording"
                        data-bs-toggle="modal" data-bs-target="#callactionModal" />
                </div>
               
                <div class="input-group mb-3" data-action-id="messanger" data-action-name="Greetings">
                    <span class="input-group-text" id="basic-addon1">#</span>
                    <input type="text" class="form-control pointer select_action" readonly value="Greetings"
                        data-bs-toggle="modal" data-bs-target="#messageractionModal" />
                </div>
                <!-- <h6>Menu</h6> -->
                <div class="input-group mb-3 condition_flow" data-action-id="if / else" data-action-name="If / Else">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-tag"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Menu"
                        data-bs-toggle="modal" data-bs-target="#conditionsactionModal"  />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>