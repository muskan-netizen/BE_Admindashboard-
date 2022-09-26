$(function(){

    $(document).on('change','.checkbox_change',function(){
        var This = this;
        checkobox_action(This,$(This).data('classname'));
    })

});
/**
 * checkbox attritbute change value
 * @param {*} val 
 * @param {*} className 
 */
function checkobox_action(This,className=""){

    var action_val = 0; 
    // if($(This).is(":checked")){
    if(){
        action_val = 1;
    } else {
        action_val = 0;
    }
    $(This).val(action_val);
}