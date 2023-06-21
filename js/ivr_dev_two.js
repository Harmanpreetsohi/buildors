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
        // console.log(formdt);
        for (const bacn of formdt) {
            actionss.push(bacn);
        }
    }else{
        // formdt['parent_id']= action_position;
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
    }
    // console.log(actionss);

    // re_order_acction();
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
                    <input type="text" value="Press ${c}" name="branches[]" class="form-control" />
                </div>
            </div>
            <div id="branch-collapse${c}" class="accordion-collapse collapse show" aria-labelledby="branch-heading${c}">
            </div>
        </div>
    `);
});
$("#input_branchefields_wrap").on("click", ".remove_field", function (e) {
    e.preventDefault();
    $(this).parents('.myremove').remove();
});