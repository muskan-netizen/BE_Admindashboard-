$(function(){

    $(document).on('change','.checkbox_change',function(){
        var This = this;
        checkobox_action(This,$(This).data('classname'));
    })

});
/**
 * checkbox attritbute change value
 * @param {*} val 
 * @param {*} id 
 */
function checkobox_action(This,id=""){
console.log();
    var action_val = 0; 
     if($(This).is(":checked")){
        action_val = 1;
    } else {
        action_val = 0;
    }
    $(`#${id}`).val(action_val);
}