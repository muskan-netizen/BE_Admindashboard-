// Synchronizes data with hubspot
$(function(){

   

})

/** Hub Spot API */
function syncHubspotData(){
    spinnerJS.showSpinner();
    axios.post(`/client/hubspot/create-contact`)
    .then(async response => {
        if(response.data.success){
            spinnerJS.hideSpinner();
        } 
    })
    .catch(e => {
        spinnerJS.hideSpinner();
        sweetAlert.error('Oops...','Something went wrong, try again later!')
    })
}