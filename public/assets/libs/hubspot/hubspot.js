// Synchronizes data with hubspot
$(function(){

   

})

/** Hub Spot API */
function syncHubspotData(){
    spinnerJS.showSpinner();
    axios.post(`/client/hubspot/create-contact`)
    .then(async response => {
        console.log(response)
        if(response.data.status){
            spinnerJS.hideSpinner();
        } else {
            spinnerJS.hideSpinner();
        }
    })
    .catch(e => {
        spinnerJS.hideSpinner();
        sweetAlert.error('Oops...','Something went wrong, try again later!')
    })
}