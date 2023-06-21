document.querySelectorAll('[data-trigger-id]').forEach(element => trigger_blocks.push({
    id: element.getAttribute('data-trigger-id'),
    name: element.getAttribute('data-trigger-name').toLowerCase(),
}));
document.querySelector('input[name=search_trigger').addEventListener('keyup', event => {
    let string = event.currentTarget.value.toLowerCase();

    for (let block of trigger_blocks) {
        if (block.name.includes(string)) {
            document.querySelector(`[data-trigger-id="${block.id}"]`).classList.remove('d-none');
        } else {
            document.querySelector(`[data-trigger-id="${block.id}"]`).classList.add('d-none');
        }
    }
});
document.querySelectorAll('[data-action-id]').forEach(element => action_blocks.push({
    id: element.getAttribute('data-action-id'),
    name: element.getAttribute('data-action-name').toLowerCase(),
}));
document.querySelector('input[name=search_action').addEventListener('keyup', event => {
    let string = event.currentTarget.value.toLowerCase();

    for (let block of action_blocks) {
        if (block.name.includes(string)) {
            document.querySelector(`[data-action-id="${block.id}"]`).classList.remove('d-none');
        } else {
            document.querySelector(`[data-action-id="${block.id}"]`).classList.add('d-none');
        }
    }
});
$('.select_trigger').click(function(){
    $('#selected_trigger_value').val($(this).val());
    $('#choose_trigger').val($(this).val());
    $('#trigger_index').val('new');
    $('#datatriggerindex').val($(this).attr('data-trigger-index'));
    $('#addtriggerModal').find('.deletetrigger').addClass('d-none');
    if($(this).val() == "Appointment Status"){
        $('#addtriggerModal').find('#filters_section').removeClass('d-none');
    }else{
        $('#addtriggerModal').find('#filters_section').addClass('d-none');
    }
});
$('#choose_trigger').on('change', function() {
    $('#selected_trigger_value').val(this.value);
    $('#datatriggerindex').val($('option:selected',this).attr('data-trigger-index'));
});
$('.choose_template').on('change', function() {
    let selected_temp = $(this).val();
    if(selected_temp == "test_template"){
        $('textarea[name=email_msg]').val('Thank you for your interest in Enphase IQ8 micro-inverters, the latest technology that has been making waves in the solar industry. My name is {{user.name}}, and I am a Consultant with {{location.name}}.');
        $('textarea[name=email_msg]').attr('readonly',true);
        $('textarea[name=email_msg]').attr('style','background: #80808012;');
    }else{
        $('textarea[name=email_msg]').val('');
        $('textarea[name=email_msg]').removeAttr('readonly');
        $('textarea[name=email_msg]').removeAttr('style');
    }
});
$('#save_trigger').click(function(){
    let trigger_value = $('#selected_trigger_value').val();
    let choose_trigger = $('#choose_trigger').val();
    let trigger_index = $('#trigger_index').val();
    let datatriggerindex = $('#datatriggerindex').val();
    let filters_value = $('select[name=filters]').val();
    if(trigger_index == 'new'){
        if(checkisTrigger(trigger_index,choose_trigger)){
                alert('You already have a trigger with similar configuration "'+choose_trigger+'". Please update the trigger before saving')
        }else{
            triggers.push({
                id: datatriggerindex,
                title: trigger_value,
                value: choose_trigger,
                filters_value:filters_value
            });
            renderTriggers();
            $('#addtriggerModal').modal('hide');
        }
    }else{
        if(typeof triggers[trigger_index] != undefined) {
            if(checkisTrigger(trigger_index,choose_trigger)){
                alert('You already have a trigger with similar configuration "'+choose_trigger+'". Please update the trigger before saving')
            }else{
                triggers[trigger_index]['id']=datatriggerindex;
                triggers[trigger_index]['title']=trigger_value;
                triggers[trigger_index]['value']=choose_trigger;
                triggers[trigger_index]['filters_value']=filters_value;
                renderTriggers();
                $('#addtriggerModal').modal('hide');
            }
        }
    }
});
$('.select_action').click(function(){
    $('.action_name').val($(this).val());
    let actionmodal = $(this).attr('data-bs-target');
    $(actionmodal).find('.deleteaction').addClass('d-none');
});
$('.save_action').click(function(){
    let targetmodal = $(this).attr('data-modale');
    let actionname = $('#'+targetmodal).find('.action_name').val();
    let formdt = eval("save"+targetmodal+"Form()");
    if(targetmodal == "conditionsactionModal"){
        is_create_condition = 1;
    }
    formdt['parent_id']= action_position;
    // console.log(formdt);
    if(editaction_position !== -1){
        actionss[editaction_position]={
            id: editaction_position,
            parent_id: parent_action_position,
            branch_id: branch_id,
            title: actionname,
            value: actionname,
            modal_id: targetmodal,
            formdt:formdt
        };
    }else{
        actionss.splice(action_position,0,{
            id: action_position,
            parent_id: parent_action_position,
            branch_id: branch_id,
            title: actionname,
            value: actionname,
            modal_id: targetmodal,
            formdt:formdt
        });
    }
    re_order_acction();
    renderActions();
});
$('#conditionsactionModal').on('click',".add_branch",function (e) {
    e.preventDefault();
    c++; //text box increment
    $("#input_branchefields_wrap").append(`
        <div class="accordion-item mb-3 position-relative myremove">
            <i class="fa fa-times cross_setion remove_field"></i>
            <div class="row p-3">
                <div class="col-md-11">
                    <input type="text" value="Branch" name="branches[${c}][0]" class="form-control" />
                </div>
                <div class="col-md-1">
                    <h2 class="accordion-header" id="branch-heading${c}">
                        <button class="accordion-button" style="box-shadow: none" type="button" data-bs-toggle="collapse" data-bs-target="#branch-collapse${c}" aria-expanded="true" aria-controls="branch-collapse${c}"></button>
                    </h2>
                </div>
            <div id="branch-collapse${c}" class="accordion-collapse collapse show" aria-labelledby="branch-heading${c}">
                <div class="accordion-body">
                    <strong>SEGMENTS</strong>
                    <div class="input_segmentfields_wrap${c}">
                        <div class="py-2 rounded bg-gray-200 px-3 pb-3 mt-2 position-relative myremove">
                            <i class="fa fa-times cross_setion remove_segmentfield"></i>
                            <div class="input_conditionfields_wrap${c}2">
                                <div class="row myremove" style="border-bottom: 1px solid #cbcdd1;">
                                    <div class="col-4">
                                        <label for="selectcondition" class="font-medium text-gray-700"></label>
                                        <select class="form-control" name="branches[${c}][2][0][0]" onchange="checkselectedAction(this)">
                                            <option value="">-- select --</option>
                                            <option>Address</option>
                                            <option>City</option>
                                            <option>State</option>
                                            <option>Company Name</option>
                                            <option>Country</option>
                                            <option>Date of Birth</option>
                                            <option>Email</option>
                                            <option>First Name</option>
                                            <option>Last Name</option>
                                            <option>Full Name</option>
                                            <option>Phone</option>
                                            <option>Postal Code</option>
                                            <option>Website</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="selectoperator" class="text-gray-700"></label>
                                        <select class="form-control" name="branches[${c}][2][0][1]">
                                            <option value="">--- select Operator ---</option>
                                            <option>Is</option>
                                            <option>Is not</option>
                                            <option>Contains</option>
                                            <option>Does not contain</option>
                                            <option>Is not empty</option>
                                            <option>Is empty</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="text-gray-700"></label>
                                        <input type="text" name="branches[${c}][2][0][2]" class="form-control">
                                    </div>
                                    <div class="col-1">
                                        <i class="fa fa-trash remove_conditionfield pointer" style="margin-top: 33px;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn add_condition" data-branchid="${c}" data-segments="2" data-conditions="1">+ Add Condition</button>
                            </div>
                            <div class="segmentconditionselection">
                                <input type="hidden" value="AND" name="branches[${c}][2][0][3]" />
                                <button type="button" class="btn btn-outline-info" onclick="segmentCondition(this,'AND')">And</button>
                                <button type="button" class="btn btn-outline-gray-400" onclick="segmentCondition(this,'OR')">Or</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn add_segment" data-branchid="${c}" data-segments="3">+ Add Segment</button>
                    </div>
                    <div class="branchconditionselection">
                        <input type="hidden" value="AND" name="branches[${c}][1]" />
                        <button type="button" class="btn btn-success" onclick="branchCondition(this,'AND')">And</button>
                        <button type="button" class="btn btn-outline-info" onclick="branchCondition(this,'OR')">Or</button>
                    </div>
                </div>
            </div>
        </div>
    `);
});
$("#input_branchefields_wrap").on("click", ".remove_field", function (e) {
    e.preventDefault();
    $(this).parents('.myremove').remove();
});
var branchcondiontselction = true;
$('#conditionsactionModal').on('click',".add_segment",function (e) {
    e.preventDefault();
    // console.log($(this).attr('data-branchid'));
    let segment_branchid = $(this).attr('data-branchid');
    let segment_segments = $(this).attr('data-segments');
    if($(".input_segmentfields_wrap"+segment_branchid).find('.myremove').html())
        branchcondiontselction=true;
    else
        branchcondiontselction=false;
    $(this).attr('data-segments',parseInt(segment_segments)+1);
    let condtionvalue = $(".input_segmentfields_wrap"+segment_branchid).parent().find('.branchconditionselection').find('input[type=hidden]').val();
    $(".input_segmentfields_wrap"+segment_branchid).append(`
            ${(branchcondiontselction)?'<p class="my-2 ms-3 branchconditon">'+condtionvalue+'</p>':''}
            <div class="py-2 rounded bg-gray-200 px-3 pb-3 mt-2 position-relative myremove">
                <i class="fa fa-times cross_setion remove_segmentfield"></i>
                <div class="input_conditionfields_wrap${segment_branchid}${segment_segments}">
                    <div class="row myremove" style="border-bottom: 1px solid #cbcdd1;">
                        <div class="col-4">
                            <label for="selectcondition" class="font-medium text-gray-700"></label>
                            <select class="form-control" name="branches[${segment_branchid}][${segment_segments}][0][0]" onchange="checkselectedAction(this)">
                                <option value="">-- select --</option>
                                <option>Address</option>
                                <option>City</option>
                                <option>State</option>
                                <option>Company Name</option>
                                <option>Country</option>
                                <option>Date of Birth</option>
                                <option>Email</option>
                                <option>First Name</option>
                                <option>Last Name</option>
                                <option>Full Name</option>
                                <option>Phone</option>
                                <option>Postal Code</option>
                                <option>Website</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="selectoperator" class="text-gray-700"></label>
                            <select class="form-control" name="branches[${segment_branchid}][${segment_segments}][0][1]">
                                <option value="">--- select Operator ---</option>
                                <option>Is</option>
                                <option>Is not</option>
                                <option>Contains</option>
                                <option>Does not contain</option>
                                <option>Is not empty</option>
                                <option>Is empty</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="text-gray-700"></label>
                            <input type="text" name="branches[${segment_branchid}][${segment_segments}][0][2]" class="form-control">
                        </div>
                        <div class="col-1">
                            <i class="fa fa-trash remove_conditionfield pointer" style="margin-top: 33px;"></i>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn add_condition" data-branchid="${segment_branchid}" data-segments="${segment_segments}" data-conditions="1">+ Add Condition</button>
                </div>
                <div class="segmentconditionselection">
                    <input type="hidden" value="AND" name="branches[${segment_branchid}][${segment_segments}][0][3]" />
                    <button type="button" class="btn btn-outline-info" onclick="segmentCondition(this,'AND')">And</button>
                    <button type="button" class="btn btn-outline-gray-400" onclick="segmentCondition(this,'OR')">Or</button>
                </div>
            </div>
        `);
});
$("#conditionsactionModal").on("click", ".remove_segmentfield", function (e) {
    e.preventDefault();
    // console.log($(this).parent('.myremove').prev('.branchconditon').html());
    if($(this).parent('.myremove').prev('.branchconditon').html()){
        $(this).parent('.myremove').prev('.branchconditon').remove();
    }
    let parentsdiv = $(this).parent('.myremove').parent();
    $(this).parent('.myremove').remove();
    if($(parentsdiv).children('.myremove').length == 1){
        $(parentsdiv).find('.branchconditon').remove();
    }
});
var segmentcondiontselction = true;
$('#conditionsactionModal').on('click',".add_condition",function (e) {
    e.preventDefault();
    // console.log($(this).attr('data-branchid'));
    let segment_branchid = $(this).attr('data-branchid');
    let segment_segments = $(this).attr('data-segments');
    let segment_conditions = $(this).attr('data-conditions');
    if($(".input_conditionfields_wrap"+segment_branchid+segment_segments).find('.myremove').html())
        segmentcondiontselction=true;
    else
        segmentcondiontselction=false;
    let condtionvalue = $(".input_conditionfields_wrap"+segment_branchid+segment_segments).parent().find('.segmentconditionselection').find('input[type=hidden]').val();
    $(this).attr('data-conditions',parseInt(segment_conditions)+1);
    $(".input_conditionfields_wrap"+segment_branchid+segment_segments).append(`
                ${(segmentcondiontselction)?'<p class="mb-0 mt-3 ms-3 segmentconditon">'+condtionvalue+'</p>':''}
                <div class="row myremove" style="border-bottom: 1px solid #cbcdd1;">
                    <div class="col-4">
                        <label for="selectcondition" class="font-medium text-gray-700"></label>
                        <select class="form-control" name="branches[${segment_branchid}][${segment_segments}][${segment_conditions}][0]" onchange="checkselectedAction(this)">
                            <option value="">-- select --</option>
                            <option>Address</option>
                            <option>City</option>
                            <option>State</option>
                            <option>Company Name</option>
                            <option>Country</option>
                            <option>Date of Birth</option>
                            <option>Email</option>
                            <option>First Name</option>
                            <option>Last Name</option>
                            <option>Full Name</option>
                            <option>Phone</option>
                            <option>Postal Code</option>
                            <option>Website</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="selectoperator" class="text-gray-700"></label>
                        <select class="form-control" name="branches[${segment_branchid}][${segment_segments}][${segment_conditions}][1]">
                            <option value="">--- select Operator ---</option>
                            <option>Is</option>
                            <option>Is not</option>
                            <option>Contains</option>
                            <option>Does not contain</option>
                            <option>Is not empty</option>
                            <option>Is empty</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="text-gray-700"></label>
                        <input type="text" name="branches[${segment_branchid}][${segment_segments}][${segment_conditions}][2]" class="form-control">
                    </div>
                    <div class="col-1">
                        <i class="fa fa-trash remove_conditionfield pointer" style="margin-top: 33px;"></i>
                    </div>
                </div>
        `);
});
$("#conditionsactionModal").on("click", ".remove_conditionfield", function (e) {
    e.preventDefault();
    // $(this).parent('.myremove').remove();
    // console.log($(this).parent('.myremove'));
    // console.log($($(this).parents('.myremove')[0]).prev());
    if($($(this).parents('.myremove')[0]).prev('.segmentconditon').html()){
        $($(this).parents('.myremove')[0]).prev('.segmentconditon').remove();
    }
    let parentsdiv = $($(this).parents('.myremove')[0]).parent();
    $(this).parents('.myremove')[0].remove();
    if($(parentsdiv).children('.myremove').length == 1){
        $(parentsdiv).find('.segmentconditon').remove();
    }
});