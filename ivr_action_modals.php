<!-- Modal -->
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
                <!-- <div class="input-group mb-3" data-action-id="send email" data-action-name="Send Email">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                            <input type="text" class="form-control pointer select_action" readonly value="Send Email"
                                data-bs-toggle="modal" data-bs-target="#emailactionModal" />
                        </div> -->
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
                <!-- <div class="input-group mb-3" data-action-id="call" data-action-name="Call_Recording">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-phone"></i></span>
                            <input type="text" class="form-control pointer select_action" readonly
                                value="Call Recording" data-bs-toggle="modal" data-bs-target="#callactionModal" />
                        </div> -->

                <div class="input-group mb-3" data-action-id="messanger" data-action-name="Greetings">
                    <span class="input-group-text" id="basic-addon1">#</span>
                    <input type="text" class="form-control pointer select_action" readonly value="Greetings"
                        data-bs-toggle="modal" data-bs-target="#greetingactionModal" />
                </div>
                <!-- <h6>Menu</h6> -->
                <div class="input-group mb-3 condition_flow" data-action-id="if / else" data-action-name="If / Else">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-tag"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Menu"
                        data-bs-toggle="modal" data-bs-target="#conditionsactionModal" />
                </div>
                <div class="input-group mb-3 condition_flow" data-action-id="hangup" data-action-name="Hangup">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-tag"></i></span>
                    <input type="text" class="form-control pointer select_action" readonly value="Hangup"
                        data-bs-toggle="modal" data-bs-target="#hangupactionModal" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="smsactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">SMS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <P>Sends an SMS to the contact</p>
                <h6>Action Name</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control action_name" value="" />
                </div>
                <h6>Message</h6>
                <div class="input-group mb-3">
                    <textarea class="form-control" name="sms_msg"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="smsactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="emailactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Email</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <P>Sends an Email to the contact</p>
                <h6>Action Name</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control action_name" value="" />
                </div>
                <h6>Message</h6>
                <div class="input-group mb-3">
                    <textarea class="form-control" name="email_msg"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="emailactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="callactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Call Response</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <P>Respond with a Call Recorded</p>
                <h6>Action Name</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control action_name" value="" />
                </div>
                <h6>Message</h6>
                <div class="input-group mb-3">
                    <textarea class="form-control" name="sms_msg"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="callactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="callforwardactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Call Response</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <P>Forward Call</p>
                <h6>Action Name</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control action_name" value="" />
                </div>
                <h6>Number</h6>
                <div class="input-group mb-3">
                    <input class="form-control" name="callforward_msg">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="callforwardactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="greetingactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Greeting</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <P>Sends an Greeting Message</p>
                    <h6>Action Name</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control action_name" value="" />
                    </div>
                    <h6>Greeting Media</h6>
                    <div class="input-group mb-3">
                        <input type="file" id="greeting-media-upload" class="form-control" accept=".mp3,audio/*" />
                        <input type="hidden" name="greeting_media" />
                    </div>
                    <div class="input-group mb-3" id="greenting_song"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" id="savegreenting" data-modale="greetingactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="conditionsactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Condition</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="conditionsactionModalforme">
                    <P>Fork the contact's journey through this IVR based on conditions</p>
                    <!-- <h6>Condition Name</h6>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control action_name" value="" />
                            </div> -->
                    <h6>Conditions</h6>
                    <div class="accordion mb-3" id="input_branchefields_wrap">
                        <div class="accordion-item mb-3 position-relative myremove">
                            <i class="fa fa-times cross_setion remove_field"></i>
                            <div class="row p-3">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">Press</span>
                                        <input type="number" class="form-control" value="1" name="branches[]" />
                                    </div>
                                </div>
                                <!-- <div class="col-md-1">
                                            <h2 class="accordion-header" id="branch-heading0">
                                                <button class="accordion-button" style="box-shadow: none" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#branch-collapse0"
                                                    aria-expanded="true" aria-controls="branch-collapse0"></button>
                                            </h2>
                                        </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn add_branch">+ Add Condition</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="conditionsactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editconditionsactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Condition</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <P>Fork the contact's journey through this IVR based on conditions</p>
                <h6>Condition</h6>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">Press</span>
                    <input type="number" class="form-control action_name" value="" />
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="editconditionsactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="hangupactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Hangup</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Hangup</h6>
                <div class="input-group">
                    <input type="text" class="form-control action_name" value="Hangup" />
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger deleteaction d-none"
                        onclick="deleteAction(this)" data-id="-1" data-bs-dismiss="modal">Delete</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_action" data-modale="hangupactionModal"
                        data-bs-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>