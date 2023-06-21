function renderActions() {
    // console.log('actionss');
    // console.log(actionss.length);
    // console.log(JSON.stringify(actionss));
    // Build the tree
    treeData = buildTree(actionss);
    // console.log(treeData);
    count_actions = 0;
    // console.log(count_actions);

    let resss = buildTreeDiagram(treeData);
    if (resss >= actionss.length) {
        // console.log(resss);
        document.getElementById('drawlinesvg').innerHTML = '';
        drawLineTreeDiagram(treeData);
    }
    // console.log(resss);
    return true;
}

function acction_post(index, parent_index = -1, branchid = -1) {
    action_position = index;
    parent_action_position = parent_index;
    branch_id = branchid;
    editaction_position = -1;
}

function acction_edit(index, parent_index = 0, branchid = -1) {
    // console.log(index);
    editaction_position = index;
    action_position = actionss[index]['id'];
    parent_action_position = actionss[index]['parent_id'];
    $('#' + actionss[index]['modal_id']).modal('show');
    $('.action_name').val(actionss[index]['title']);
    $('#' + actionss[index]['modal_id']).find('.deleteaction').removeClass('d-none');
    $('#' + actionss[index]['modal_id']).find('.deleteaction').attr('data-id', index);
    switch (actionss[index]['modal_id']) {
        case 'smsactionModal': {
            $('textarea[name=sms_msg]').val(actionss[index]['formdt']['sms_msg']);
        }
        break;
    case 'greetingactionModal': {
        $('input[name=greeting_media]').val(actionss[index]['formdt']['greeting_media']);
        // $('#ddddr').attr('src',actionss[index]['formdt']['greeting_media']);
        var sound      = document.createElement('audio');
            sound.id       = 'audio-player';
            sound.controls = 'controls';
            sound.src      = actionss[index]['formdt']['greeting_media'];
            sound.type     = 'audio/mpeg';
            document.getElementById('greenting_song').innerHTML = '';
            document.getElementById('greenting_song').appendChild(sound);
    }
    break;
    case 'callforwardactionModal': {
        $('input[name=callforward_msg]').val(actionss[index]['formdt']['callforward_msg'])
    }
    break;
    case 'editconditionsactionModal': {
        $('.action_name').val(actionss[index]['value']);
    }
    break;
    }
}

function saveemailactionModalForm() {
    let fromname = $('input[name=email_fromname]').val();
    let fromemail = $('input[name=email_fromemail]').val();
    let email_subject = $('input[name=email_subject]').val();
    let email_msg = $('textarea[name=email_msg]').val();
    return {
        fromname: fromname,
        fromemail: fromemail,
        email_subject: email_subject,
        email_msg: email_msg
    };
}

function savesmsactionModalForm() {
    let sms_msg = $('textarea[name=sms_msg]').val();
    return {
        sms_msg: sms_msg
    };
}

function savecallactionModalForm() {
    let call_msg = $('textarea[name=call_msg]').val();
    return {
        call_msg: call_msg
    };
}

function savegreetingactionModalForm() {
    let greeting_media = $('input[name=greeting_media]').val();
    return {
        greeting_media: greeting_media
    };
}

function savehangupactionModalForm() {
    return '';
}

function saveconditionsactionModalForm() {
    var branches = $('input[name="branches[]"]').map(function () {
        return {
            id: action_position++,
            parent_id: parent_action_position,
            branch_id: -1,
            title: "Press " + this.value,
            value: this.value,
            modal_id: "editconditionsactionModal",
            formdt: {}
        }; // $(this).val()
    }).get();
    return branches;
}

function saveeditconditionsactionModalForm() {
    return {};
}

function savecallforwardactionModalForm() {
    let callforward_msg = $('input[name=callforward_msg]').val();
    callforward_msg = callforward_msg.replace('"', '')
    return {
        callforward_msg: callforward_msg
    };
}

function deleteAction(e) {
    let index = $(e).attr('data-id');
    // delete actionss[index];
    actionss.splice(index, 1);
    renderActions();
}

function buildTree(nodes, parentId = -1) {
    const tree = [];
    for (const [i, node] of nodes.entries()) {
        // console.log('node');
        // console.log(node);
        if (node.parent_id === parentId) {
            const childNode = {
                ...node
            };
            const children = buildTree(nodes, node.id);
            if (children.length > 0) {
                childNode.children = children;
            }
            childNode['index'] = i;
            tree.push(childNode);
        }
    }

    return tree;
}

function buildTreeDiagram(node, element = document.createElement('ul')) {
    document.getElementById("append_action").innerHTML = '';
    element.className = 'action-paire list-unstyled';

    for (let [i, x] of node.entries()) {
        count_actions = count_actions + 1;
        let listItem = document.createElement('li');
        listItem.innerHTML = `<div class="mx-2" id="block_${x.id}"><button type="button" class="btn btn-light d-block mt-4 m-auto btn-acction add_action" onclick="acction_edit(${x.index},'${x.modal_id}')"><i class="fa fa-envelopes"></i> ${x.title}</button>
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
    // console.log(count_actions);
    // drawLineTreeDiagram(treeData);
    return count_actions;
}

function connectDiv(node11, node21) {
    // console.log(node11,node21);
    const div1 = document.getElementById(node11);
    const div2 = document.getElementById(node21);
    const drawlinesvg = document.getElementById('drawlinesvg');
    // console.log(div1,div2);

    const div1Rect = div1.getBoundingClientRect();
    const div2Rect = div2.getBoundingClientRect();
    const containerRect = drawlinesvg.getBoundingClientRect();

    const svgLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    svgLine.setAttribute('x1', div1Rect.left - containerRect.left + div1Rect.width / 2);
    svgLine.setAttribute('y1', (div1Rect.top - containerRect.top + div1Rect.height / 2) + 50);
    svgLine.setAttribute('x2', div2Rect.left - containerRect.left + div2Rect.width / 2);
    svgLine.setAttribute('y2', (div2Rect.top - containerRect.top + div2Rect.height / 2) - 50);
    svgLine.setAttribute('stroke', '#cdd5dd');
    // drawlinesvg.style.width = (div2Rect.left - containerRect.left + div2Rect.width / 2)+"px";
    // console.log(drawlinesvg.style.height.slice(0,-2));
    if(drawlinesvg.style.height.slice(0,-2) <= (div2Rect.top - containerRect.top + div2Rect.height / 2))
        drawlinesvg.style.height = (div2Rect.top - containerRect.top + div2Rect.height / 2) + "px";

    drawlinesvg.appendChild(svgLine);
}

function drawLineTreeDiagram(node, parent_node = '') {
    // console.log('draw',node,parent_node);
    for (let [i, x] of node.entries()) {
        if (parent_node != '' || parent_node == '0') {
            connectDiv('block_' + parent_node, 'block_' + (x.id).toString());
        }
        if (x.children) {
            drawLineTreeDiagram(x.children, (x.id).toString());
        }
    }
}