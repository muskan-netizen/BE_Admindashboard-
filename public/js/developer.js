function startLoader(element,loader_text) {
    // check if the element is not specified
    if(typeof element == 'undefined') {
        console.log(element);
        element = "body";
    }

    // set the wait me loader
    $(element).waitMe({ 
        effect : 'rotateplane',
        text : loader_text,
        bg : 'rgb(2, 2, 2, 0.7)',
        //color : 'rgb(66,35,53)',
        color : 'rgb(242, 242, 242) !important',// change color if want any color for loader
        sizeW : '150px',
        sizeH : '150px',
        source : ''
    });
}

/**
* Start the loader on the particular element
*/
function stopLoader(element) {
    // check if the element is not specified
    if(typeof element == 'undefined') {
        element = 'body';
    }

    // close the loader
    $(element).waitMe("hide");
}
