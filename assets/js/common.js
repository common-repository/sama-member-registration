

function memmberRegistration(frm) {
    //console.log(frm);
    var error = 0;
    var errorMessage = "";
    if (frm.first_name.value == "") {
        error++;
        //errorMessage += "Please add email address \n";
        jQuery("#firstName_lbl").show();
    }
    if (frm.last_name.value == "") {
        error++;
        //errorMessage += "Please add contact number \n";
        jQuery("#lastName_lbl").show();
    }
    if (frm.user_name.value == "") {
        error++;
        //errorMessage += "Please add contact number \n";
        jQuery("#username_lbl").show();
    }
    if (frm.email_address.value == "") {
        error++;
        //errorMessage += "Please add contact number \n";
        jQuery("#emailAddress_lbl").show();
    }
    if (frm.mobile_number.value == "") {
        error++;
        //errorMessage += "Please add contact number \n";
        jQuery("#mobileNumber_lbl").show();
    }
	
	    if (frm.subscription_type.value == 0) {
        error++;
        //errorMessage += "Please select your subscription plan \n";
        jQuery("#memSub_lbl").show();
    }
	
    if (error > 0) {
        //alert(errorMessage);
        return false;
    }
     
    jQuery(".loader").show();
    jQuery("#regBtn").hide();
    var myformData = new FormData(frm);
    myformData.append("nonceVal", sama_js_vars.nonceVal);
    //var form_data = jQuery(frm).serialize();
    /*jQuery.post(
        fsms_i18n.ajax_url, 
        form_data, 
        function(msg) {
         alert(msg);
    }); */

    jQuery.ajax({
        type: "POST",
        data: myformData,
        dataType: "json",
        url: sama_js_vars.ajaxurl,
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        success: function (data, textStatus, jqXHR) {
             
            jQuery('html, body').animate({
                scrollTop: jQuery("#sama-registration-div").offset().top
            }, 100);
            jQuery(".loader").hide();
            jQuery("#sama-registration-div").html(data.welcome_web_message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //if fails
            console.log(jqXHR);
        }
    });
}


function acceptOrReject(id,status,user_id){
    if(status == -2){
        return;
    }
    var alertTxt = "";
    if(status == 3){
        alertTxt = "Reject";
    }
    if(status == 1){
        alertTxt = "Accept";
    }
    if(status == 5){
        alertTxt = "Suspend";
    }
    if(status == 4){
        alertTxt = "expired";
    }
    if (confirm("Do you need to "+alertTxt) == true) {
         
    } else {
      return;
    }
    
    if(status == 3){
        jQuery("#reject_span_"+user_id).hide();
        alert("Rejected");
        jQuery("#accept_reject_span_"+user_id).html("Rejected");
    }
    if(status == 1){
        jQuery("#accept_span_"+user_id).hide();
        alert("Accepted");
        jQuery("#accept_reject_span_"+user_id).html("Accepted")
    }
    if(status == 5){
        jQuery("#accept_span_"+user_id).hide();
        alert("Suspended");
        jQuery("#accept_reject_span_"+user_id).html("Suspended")
    }
    if(status == 4){
        jQuery("#accept_span_"+user_id).hide();
        alert("Expired");
        jQuery("#accept_reject_span_"+user_id).html("Expired")
    }
    
    var myformData = new FormData();
    myformData.append("nonceVal", sama_js_vars.nonceVal);
    myformData.append("id", id);
    myformData.append("status", status);
    myformData.append("action", "member_accept_reject_user");
    myformData.append("ok", "1");
    myformData.append("user_id", user_id);
    jQuery.ajax({
        type: "POST",
        data: myformData,
        dataType: "json",
        url: sama_js_vars.ajaxurl,
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        success: function (data, textStatus, jqXHR) {
             
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //if fails
            console.log(jqXHR);
        }
    });

}

jQuery( function() {
    jQuery( "#birth_date" ).datepicker(
        { dateFormat: 'yy-mm-dd' }
        );
  } );

