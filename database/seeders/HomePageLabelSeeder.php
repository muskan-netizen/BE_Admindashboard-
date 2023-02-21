<body>
<div align="center">
    <form id="frmContact" action="" method="post"
        onSubmit="return validate();">

        <div class="field-row">
            <label>Card Number</label> <span id="card-number-info" class="info"></span>
            <br /> 
            <input type="text" id="card-number" class="input_box">
        </div>
        <div class="field-row">
            <div class="contact-row column-right">
                <label>Expiry Month / Year</label> 
                <span id="userEmail-info" class="info"></span>
                <br /> 
                <select name="expiryMonth" id="expiryMonth" class="select_box">
                <?php
                for ($i = date("m"); $i <= 12; $i ++) {
                    $monthValue = $i;
                    if (strlen($i) < 2) {
                        $monthValue = "0" . $monthValue;
                    }
                    ?>
                <option value="<?php echo $monthValue; ?>"><?php echo $i; ?></option>
                <?php
                }
                ?>
                </select> <select name="expiryMonth" id="expiryMonth"
                    class="select_box">
            <?php
            for ($i = date("Y"); $i <= 2030; $i ++) {
                $yearValue = substr($i, 2);
                ?>
            <option value="<?php echo $yearValue; ?>"><?php echo $i; ?></option>
            <?php
            }
            ?>
            </select>
            </div>
            <div class="contact-row cvv-box">
                <label>CVV</label> <span id="cvv-info" class="info"></span><br />
                <input type="text" name="cvv" id="cvv"
                    class="input_box cvv-input">
            </div>
            
        </div>
        <div>
	<div class="field-row">
            <label style="padding-top: 20px;">Card Holder Name</label> <span
                id="card-holder-name-info" class="info"></span><br /> <input
                type="text" id="card-holder-name" class="input_box" />
			</div>
        </div>
        <div>
            <input type="submit" value="Submit" class="btnAction" />
        </div>
        <div id="error-message"></div>

    </form>
</div>
</body>

<script>
function validate(){
	var valid = true;	 
    $(".demoInputBox").css('background-color','');
    var message = "";

    var cvvRegex = /^[0-9]{3,3}$/;
    
    var cardNumber = $("#azul-card-element").val();
    var cvv = $("#azul-cvv-element").val();

    if(cardNumber == "" || cvv == "") {
    	   message  += "<div>All Fields are Required.</div>";  
    	  
    	   if(cardNumber == "") {
    		   $("#azul-card-element").css('background-color','#FFFFDF');
    	   }
    	   if (cvv == "") {
    		   $("#azul-cvv-element").css('background-color','#FFFFDF');
    	   }
       valid = false;
    }
    
    if(cardNumber != "") {
        	$('#azul-card-element').validateCreditCard(function(result){
            if(!(result.valid)){
                	message  += "<div>Card Number is Invalid</div>";    
            		$("#card-number").css('background-color','#FFFFDF');
            		valid = false;
            }
        });
    }
    
    if (cvv != "" && !cvvRegex.test(cvv)) {
        message  += "<div>CVV is Invalid</div>";    
        $("#azul-cvv-element").css('background-color','#FFFFDF');
    		valid = false;
    }
    
    if(message != "") {
        $("#azul_card_error").show();
        $("#azul_card_error").html(message);
    }
    return valid;
}
</script>