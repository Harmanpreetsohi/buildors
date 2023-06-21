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
    $('#addtriggerModal').find('.deletetrigger').addClass('d-none');
});
$('#choose_trigger').on('change', function() {
    $('#selected_trigger_value').val(this.value);
});
$('#save_trigger').click(function(){
    let trigger_value = $('#selected_trigger_value').val();
    let choose_trigger = $('#choose_trigger').val();
    let trigger_index = $('#trigger_index').val();
    if(trigger_index == 'new'){
        if(checkisTrigger(trigger_index,choose_trigger)){
                alert('You already have a trigger with similar configuration "'+choose_trigger+'". Please update the trigger before saving')
        }else{
            triggers.push({
                id: 2,
                title: trigger_value,
                value: choose_trigger
            });
            renderTriggers();
            $('#addtriggerModal').modal('hide');
        }
    }else{
        if(typeof triggers[trigger_index] != undefined) {
            if(checkisTrigger(trigger_index,choose_trigger)){
                alert('You already have a trigger with similar configuration "'+choose_trigger+'". Please update the trigger before saving')
            }else{
                triggers[trigger_index]['title']=trigger_value;
                triggers[trigger_index]['value']=choose_trigger;
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
    console.log(formdt);
    console.log(editaction_position);
    if(editaction_position !== -1){
    console.log('editaction_position');
        actionss[editaction_position]={
            id: action_position,
            title: actionname,
            value: actionname,
            modal_id: targetmodal,
            formdt:formdt
        };
    }else{
    console.log('saveaction_position');

        actionss.splice(action_position,0,{
            id: action_position,
            title: actionname,
            value: actionname,
            modal_id: targetmodal,
            formdt:formdt
        });
    }
    renderActions();
});