function renderActions(){
    let acttion = "";
    is_create_condition = -1;
    actionsss = [{
        "id": 0,
        "parent_id": -1,
        "branch_id": -1,
        "title": "Send Email",
        "value": "Send Email",
        "modal_id": "emailactionModal",
        "formdt": {
            "email_msg": "dfdf",
            "parent_id": 0
        }
    }, {
        "id": 1,
        "parent_id": 0,
        "branch_id": -1,
        "title": "Call Forward",
        "value": "Call Forward",
        "modal_id": "callforwardactionModal",
        "formdt": {
            "callforward_msg": "45654",
            "parent_id": 1
        }
    }, {
        "id": 2,
        "parent_id": 1,
        "branch_id": -1,
        "title": "Press 1",
        "value": "Press 1",
        "modal_id": "conditionsactionModal",
        "formdt": {
            "branches": ["Press 1", "Press 145"],
            "parent_id": 2
        }
    }, {
        "id": 3,
        "parent_id": 1,
        "branch_id": -1,
        "title": "Press 2",
        "value": "Press 2",
        "modal_id": "conditionsactionModal",
        "formdt": {
            "branches": ["Press 1", "Press 145"],
            "parent_id": 2
        }
    }];
    // console.log('actionss');
    // console.log(JSON.stringify(actionss));
    // Build the tree from the nestedLists
    treeData = buildTree(actionss);
    // console.log(treeData);
    buildTreeDiagram(treeData);
    return true;
    for (let x in actionss) {
        if(actionss[x]){
            $('#toggle_actionbtn').addClass('d-none');
            if(actionss[x]['modal_id'] == 'conditionsactionModal'){
                is_create_condition = 1;
                let subnods = [];
                acttion +=`<button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x})"><i class="fa fa-envelopes"></i> ${actionss[x]['title']}</button>
                <div class="action-paire">`;
                for (let c in actionss[x]['formdt']['branches']) {
                    acttion +=`<div class="me-5" id="branch_${c}">
                            <button type="button" class="btn btn-primary btn-branchactive d-block mt-4 m-auto btn-acction">
                                <i class="fa fa-envelopes"></i> ${actionss[x]['formdt']['branches'][c][0][0]}
                            </button>
                            <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(0,0,'branch_${c}')" data-id="${parseInt(x)+1}" data-bs-toggle="modal" data-bs-target="#addactionModal">
                                <i class="fa fa-plus"></i>
                            </button>`;
                        subnods.push({'id':actionss[x]['formdt']['parent_id'],"branch_id":"branch_"+c});
                        let child_node_actions = ishave_parent_acction(actionss[x]['formdt']['parent_id'],'branch_'+c);
                        for (let x in child_node_actions) {
                            acttion += `<div class="action-paire"><div><button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x},0,'branch_${c}')"><i class="fa fa-envelopes"></i> ${child_node_actions[x]['title']}</button>
                            <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(${parseInt(x)+1},${x},'branch_${c}')" data-id="${parseInt(x)+1}" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
                            </div></div>`;
                        }
                        acttion += `<div class="m-auto" style="width:3rem;height:3rem;margin-top: 19px !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--file-icons w-full h-full" width="100%" height="100%" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" data-icon="file-icons:donejs" data-inline="false"><path d="M306.062 212.468l-18.418 67.977h-76.013l18.083-67.977h76.348zM512 58.098l-83.045 310.08H65.967l-58.6 85.724l-7.367-.67L106.15 58.098H512zm-280.947 62.954h206.273l8.037-31.812H239.424l-8.371 31.812zm-58.6-.335c8.371 0 17.078-7.032 19.422-15.404c2.343-8.706-2.68-15.403-11.386-15.403c-19.75 1.108-29.4 29.227-8.036 30.807zm-41.188 0c21.987-1.247 27.145-30.698 8.037-30.807c-8.372 0-17.078 7.032-19.422 15.403c-2.01 8.372 3.013 15.404 11.385 15.404zm315.437 91.751l15.739-59.27h-64.293l-16.073 59.27h-76.013l15.738-59.27h-76.013l-16.073 59.27h-76.348l15.738-59.27h-64.293l-15.738 59.27h64.293l-18.083 67.977H70.99l-17.413 64.293h64.294l17.412-64.293h76.348l-17.413 64.293h76.348l17.078-64.293h76.348l-17.413 64.293h64.628l17.413-64.293h-64.628l18.083-67.977h64.627z" fill="currentColor"></path></svg>
                    </div></div>`;
                }
                acttion +=`<div class="" id="elsebranch_${x}">
                <button type="button" class="btn btn-danger btn-branchnone d-block mt-4 m-auto btn-acction">
                    <i class="fa fa-envelopes"></i> None
                </button>
                <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(0,0,'elsebranch_${c}')" data-id="${parseInt(x)+1}" data-bs-toggle="modal" data-bs-target="#addactionModal">
                    <i class="fa fa-plus"></i>
                </button>`;
                let child_node_elseactions = ishave_parent_acction(actionss[x]['formdt']['parent_id'],'elsebranch_'+c);
                for (let x in child_node_elseactions) {
                    acttion += `<div class="action-paire"><div><button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x},0,'elsebranch_${c}')"><i class="fa fa-envelopes"></i> ${child_node_elseactions[x]['title']}</button>
                    <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(${parseInt(x)+1},${x},'elsebranch_${c}')" data-id="${parseInt(x)+1}" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
                    </div></div>`;
                }
                acttion += `<div class="m-auto" style="width:3rem;height:3rem;margin-top: 19px !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--file-icons w-full h-full" width="100%" height="100%" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" data-icon="file-icons:donejs" data-inline="false"><path d="M306.062 212.468l-18.418 67.977h-76.013l18.083-67.977h76.348zM512 58.098l-83.045 310.08H65.967l-58.6 85.724l-7.367-.67L106.15 58.098H512zm-280.947 62.954h206.273l8.037-31.812H239.424l-8.371 31.812zm-58.6-.335c8.371 0 17.078-7.032 19.422-15.404c2.343-8.706-2.68-15.403-11.386-15.403c-19.75 1.108-29.4 29.227-8.036 30.807zm-41.188 0c21.987-1.247 27.145-30.698 8.037-30.807c-8.372 0-17.078 7.032-19.422 15.403c-2.01 8.372 3.013 15.404 11.385 15.404zm315.437 91.751l15.739-59.27h-64.293l-16.073 59.27h-76.013l15.738-59.27h-76.013l-16.073 59.27h-76.348l15.738-59.27h-64.293l-15.738 59.27h64.293l-18.083 67.977H70.99l-17.413 64.293h64.294l17.412-64.293h76.348l-17.413 64.293h76.348l17.078-64.293h76.348l-17.413 64.293h64.628l17.413-64.293h-64.628l18.083-67.977h64.627z" fill="currentColor"></path></svg>
                    </div></div></div>`;
                // $('#default_flag').hide();
                // break;
            }else{
                if(-1 == actionss[x]['branch_id']){
                    acttion += `<div class="action-paire"><div><button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x})"><i class="fa fa-envelopes"></i> ${actionss[x]['title']}</button>
                            <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(${parseInt(x)+1},${x})" data-id="${parseInt(x)+1}" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
                            </div></div>`;
                }
            }
        }
    }
    document.getElementById("append_action").innerHTML = acttion;
}function ishave_parent_acction(parent_index,branddid){
    let have_parent_acction = []; 
    // console.log(branddid);
    for (let x in actionss) {
        // console.log(actionss[x]);
        if(actionss[x]){
            // if(parent_index != actionss[x]['id'] &&  (parent_index == actionss[x]['parent_id'] && branddid == actionss[x]['branch_id'])){
            if(branddid == actionss[x]['branch_id']){
                have_parent_acction.push(actionss[x]);
            }
        }
    }

    return have_parent_acction;
}function acction_post(index,parent_index=-1,branchid=-1){
    action_position = index;
    parent_action_position = parent_index;
    branch_id = branchid;
    editaction_position = -1;
    // if(is_create_condition != -1)
    //     $('.condition_flow').hide();
    // else
    //     $('.condition_flow').show();
    // if(branch_id != -1 || is_create_condition != -1)
    //     $('.condition_flow').hide();
    // else
    //     $('.condition_flow').show();
}function re_order_acction(){
    for (let x in actionss) {
        if(actionss[x]){
            let xy = parseInt(x);
            actionss[x]['id'] = xy;
            if(actionss[xy+1])
                actionss[xy+1]['parent_id'] = actionss[x]['id'];
        }
    }
    // return have_parent_acction;
}function acction_edit(index,modals='',parent_index=0,branchid=-1){
    console.log(treeData,index);
    console.log(actionss[index]);
    console.log(actionss);
    editaction_position = index;
    $('#'+modals).modal('show');
    return true;
    $('.action_name').val(treeData[index]['title']);
    $('#'+treeData[index]['modal_id']).find('.deleteaction').removeClass('d-none');
    $('#'+treeData[index]['modal_id']).find('.deleteaction').attr('data-id',index);
    switch(treeData[index]['modal_id']){
        case 'emailactionModal':{
            $('input[name=email_fromname]').val(treeData[index]['formdt']['fromname']);
            $('input[name=email_fromemail]').val(treeData[index]['formdt']['fromemail']);
            $('input[name=email_subject]').val(treeData[index]['formdt']['email_subject']);
            $('textarea[name=email_msg]').val(treeData[index]['formdt']['email_msg']);
        }break;
        case 'smsactionModal':{
            $('textarea[name=sms_msg]').val(treeData[index]['formdt']['sms_msg']);
        }break;
        case 'callactionModal':{
            $('textarea[name=call_msg]').val(treeData[index]['formdt']['call_msg']);
        }break;
        case 'messageractionModal':{
            $('textarea[name=messenger_msg]').val(treeData[index]['formdt']['messenger_msg']);
        }break;
        case 'instagramactionModal':{
            $('textarea[name=instagram_msg]').val(treeData[index]['formdt']['instagram_msg']);
        }break;
        case 'smsactionModal':{
            $('textarea[name=manualcall_msg]').val(treeData[index]['formdt']['manualcall_msg']);
        }break;
        case 'addcontacttagactionModal':{
            $('input[name=add_contact_tags]').tagsinput('add',treeData[index]['formdt']['add_contact_tags'])
        }break;
        case 'removecontacttagactionModal':{
            $('input[name=remove_contact_tags]').tagsinput('add',treeData[index]['formdt']['remove_contact_tags'])
        }break;
        case 'conditionsactionModal':{
            // $('input[name=remove_contact_tags]').tagsinput('add',treeData[index]['formdt']['remove_contact_tags'])
            $("#input_branchefields_wrap").html('');
            let conditonaldata = '';
            for (let c in treeData[index]['formdt']['branches']) {
                console.log(treeData[index]['formdt']['branches'][c]);
                conditonaldata += `
                    <div class="accordion-item mb-3 position-relative myremove">
                        <i class="fa fa-times cross_setion remove_field"></i>
                        <div class="row p-3">
                            <div class="col-md-11">
                                <input type="text" value="${treeData[index]['formdt']['branches'][c][0][0]}" name="branches[${c}][0]" class="form-control" />
                            </div>
                            <div class="col-md-1">
                                <h2 class="accordion-header" id="branch-heading${c}">
                                    <button class="accordion-button" style="box-shadow: none" type="button" data-bs-toggle="collapse" data-bs-target="#branch-collapse${c}" aria-expanded="true" aria-controls="branch-collapse${c}"></button>
                                </h2>
                            </div>
                        <div id="branch-collapse${c}" class="accordion-collapse collapse show" aria-labelledby="branch-heading${c}">
                            <div class="accordion-body">
                                <strong>SEGMENTS</strong>
                                <div class="input_segmentfields_wrap${c}">`;
                        let segment = 2;
                        let is_first_segmd = true;
                for (let cc in treeData[index]['formdt']['branches'][c][1][0]) {
                    if(!is_first_segmd){
                        conditonaldata +=`<p class="my-2 ms-3 branchconditon">${treeData[index]['formdt']['branches'][c][0][1]}</p>`;
                    }
                    is_first_segmd = false;
                    conditonaldata += `<div class="py-2 rounded bg-gray-200 px-3 pb-3 mt-2 position-relative myremove">
                                        <i class="fa fa-times cross_setion remove_segmentfield"></i>
                                        <div class="input_conditionfields_wrap${c}${parseInt(cc)+2}">`;
                                        let condion = 0;
                                        let is_first_cond = true;
                    for (let ccc in treeData[index]['formdt']['branches'][c][1][0][cc]) {
                            if(!is_first_cond){
                                conditonaldata +=`<p class="mb-0 mt-3 ms-3 segmentconditon">${treeData[index]['formdt']['branches'][c][1][0][cc][0][3]}</p>`;
                            }
                            is_first_cond = false;
                            conditonaldata += `<div class="row myremove" style="border-bottom: 1px solid #cbcdd1;">
                                    <div class="col-4">
                                        <label for="selectcondition" class="font-medium text-gray-700"></label>
                                        <select class="form-control" name="branches[${c}][${parseInt(cc)+2}][${ccc}][0]" onchange="checkselectedAction(this)">
                                            <option value="">-- select --</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Address'?'selected':''}>Address</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='City'?'selected':''}>City</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='State'?'selected':''}>State</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Company Name'?'selected':''}>Company Name</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Country'?'selected':''}>Country</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Date  of Birth'?'selected':''}>Date of Birth</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Email'?'selected':''}>Email</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='First Name'?'selected':''}>First Name</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Last Name'?'selected':''}>Last Name</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Full Name'?'selected':''}>Full Name</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Phone'?'selected':''}>Phone</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Postal Code'?'selected':''}>Postal Code</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][0]=='Website'?'selected':''}>Website</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="selectoperator" class="text-gray-700"></label>
                                        <select class="form-control" name="branches[${c}][${parseInt(cc)+2}][${ccc}][1]">
                                            <option value="">--- select Operator ---</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Is'?'selected':''}>Is</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Is not'?'selected':''}>Is not</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Contains'?'selected':''}>Contains</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Does not contain'?'selected':''}>Does not contain</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Is not empty'?'selected':''}>Is not empty</option>
                                            <option ${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][1]=='Is empty'?'selected':''}>Is empty</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="text-gray-700"></label>
                                        <input type="text" name="branches[${c}][${parseInt(cc)+2}][${ccc}][2]" value="${treeData[index]['formdt']['branches'][c][1][0][cc][ccc][2]}" class="form-control">
                                    </div>
                                    <div class="col-1">
                                        <i class="fa fa-trash remove_conditionfield pointer" style="margin-top: 33px;"></i>
                                    </div>
                                </div>`;
                                condion++;
                    }
                                conditonaldata += `</div>
                                                    <div class="text-end">
                                                        <button type="button" class="btn add_condition" data-branchid="${c}" data-segments="${parseInt(cc)+2}" data-conditions="${condion}">+ Add Condition</button>
                                                    </div>
                                                    <div class="segmentconditionselection">
                                                        <input type="hidden" value="${treeData[index]['formdt']['branches'][c][1][0][cc][0][3]}" name="branches[${c}][${parseInt(cc)+2}][0][3]" />
                                                        <button type="button" class="btn btn-outline-info" onclick="segmentCondition(this,'AND')">And</button>
                                                        <button type="button" class="btn btn-outline-gray-400" onclick="segmentCondition(this,'OR')">Or</button>
                                                    </div>
                                                </div>`;
                                                segment++;
                }
                    conditonaldata += `</div>
                                        <div class="text-end">
                                            <button type="button" class="btn add_segment" data-branchid="${c}" data-segments="${segment}">+ Add Segment</button>
                                        </div>
                                        <div class="branchconditionselection">
                                            <input type="hidden" value="${treeData[index]['formdt']['branches'][c][0][1]}" name="branches[${c}][1]" />
                                            <button type="button" class="btn btn-success" onclick="branchCondition(this,'AND')">And</button>
                                            <button type="button" class="btn btn-outline-info" onclick="branchCondition(this,'OR')">Or</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
            }
                $("#input_branchefields_wrap").append(conditonaldata);
        }break;
    }
}function saveemailactionModalForm(){
    let fromname = $('input[name=email_fromname]').val();
    let fromemail = $('input[name=email_fromemail]').val();
    let email_subject = $('input[name=email_subject]').val();
    let email_msg = $('textarea[name=email_msg]').val();
    return {
        fromname:fromname,
        fromemail:fromemail,
        email_subject:email_subject,
        email_msg:email_msg
    };
}function savesmsactionModalForm(){
    let sms_msg = $('textarea[name=sms_msg]').val();
    return {
        sms_msg:sms_msg
    };
}function savecallactionModalForm(){
    let call_msg = $('textarea[name=call_msg]').val();
    return {
        call_msg:call_msg
    };
}function savegreetingactionModalForm(){
    let greeting_sms_msg = $('textarea[name=greeting_sms_msg]').val();
    return {
        greeting_sms_msg:greeting_sms_msg
    };
}function saveconditionsactionModalForm(){
    // let branches = getvalues('branches[]');
    var branches = $('input[name="branches[]"]').map(function () {
        return {
            id: action_position++,
            parent_id: parent_action_position,
            branch_id: -1,
            title: this.value,
            value: this.value,
            modal_id: "conditionsactionModal",
            formdt:{}
        }; // $(this).val()
    }).get();
    return branches;
}function savecallforwardactionModalForm(){
    let callforward_msg = $('input[name=callforward_msg]').val();
    callforward_msg = callforward_msg.replace('"','')
    return {
        callforward_msg:callforward_msg
    };
}function deleteAction(e){
    let index = $(e).attr('data-id');
    delete actionss[index];
    renderActions();
}function getvalues(inputfield){
    // let inps = document.getElementsByName(inputfield);
    // let inps = $('#conditionsactionModal').find('input');

    let form = document.getElementById('conditionsactionModalforme');
    let inputs = form.elements;

    let myArray = [];
    let inputvalues = [];

    for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i];

        if (input.name.startsWith('branches')) {
            let formvaluess = input.name.match(/\d+/g);
            // console.log(formvaluess);
            if(formvaluess.length == 2){
                const [index1, index2] = formvaluess;
                myArray[index1] = myArray[index1] || [];
                myArray[index1][index2] = input.value;
            }else if(formvaluess.length == 3){
                const [index1, index2, index3] = input.name.match(/\d+/g);
                myArray[index1] = myArray[index1] || [];
                myArray[index1][index2] = myArray[index1][index2] || [];
                myArray[index1][index2][index3] = input.value;
            }else if(formvaluess.length == 4){
                const [index1, index2, index3, index4] = input.name.match(/\d+/g);
                myArray[index1] = myArray[index1] || [];
                myArray[index1][index2] = myArray[index1][index2] || [];
                myArray[index1][index2][index3] = myArray[index1][index2][index3] || [];
                myArray[index1][index2][index3][index4] = input.value;
            }
        }
    }
    for(let b in myArray){
        let newarray = [];
        newarray.push(myArray[b].slice(0,2));
        newarray.push([myArray[b].slice(2)]);
        inputvalues.push(newarray);
    }
    return inputvalues;
}function branchCondition(e,condition){
    $(e).parent().parent().find('.branchconditon').html(condition);
    $(e).parent().find('input[type=hidden]').val(condition);
    $(e).attr('class','btn btn-success');
    if(condition == 'AND'){
        $(e).next().attr('class','btn btn-outline-info');
    }else
        $(e).prev().attr('class','btn btn-outline-info');
}function segmentCondition(e,condition){
    $(e).parent().parent().find('.segmentconditon').html(condition);
    $(e).parent().find('input[type=hidden]').val(condition);
    $(e).attr('class','btn btn-outline-info');
    if(condition == 'AND'){
        $(e).next().attr('class','btn btn-outline-gray-400');
    }else
        $(e).prev().attr('class','btn btn-outline-gray-400');
}function checkselectedAction(e){
    let default_value = `
    <option value="">--- select ---</option>
    <option>Contact Details</option>
    `;
    let contact_details = `
    <option value="">-- select --</option>
    <option value="bacttoaction">Back To Action</option>
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
    `;
    if($(e).val() == "Contact Details"){
        $(e).html(contact_details);
    }else if($(e).val() == "bacttoaction"){
        $(e).html(default_value);
    }
}function buildTree(nodes, parentId = -1) {
    const tree = [];
    for (const node of nodes) {
        // console.log('node');
        // console.log(node);
        if (node.parent_id === parentId) {
            const childNode = { ...node };
            const children = buildTree(nodes, node.id);
        if (children.length > 0) {
            childNode.children = children;
        }
            tree.push(childNode);
        }
    }

    return tree;
}function buildTreeDiagram(node,element=document.createElement('ul')) {
    document.getElementById("append_action").innerHTML = '';
    element.className = 'action-paire list-unstyled';

    for (let [i, x] of node.entries()) {
      let listItem = document.createElement('li');
      listItem.innerHTML = `<div class="mx-2"><button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x.id},'${x.modal_id}')"><i class="fa fa-envelopes"></i> ${x.title}</button>
      <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(${actionss.length+1},${x.id})" data-id="1" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
      </div>`;
  
      if (x.children) {
        let childList = document.createElement('ul');
        buildTreeDiagram(x.children, childList);
        listItem.appendChild(childList);
      }
  
      element.appendChild(listItem);
    }
    document.getElementById("append_action").appendChild(element);
}