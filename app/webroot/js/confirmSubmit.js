function confirmSubmit(msg)
{
    var agree=confirm(msg);
    if (agree)
	return true ;
    else
	return false ;
}

function checkAllImmediate(){
	var length = $(".checkbox").length;
	for(i=0;i<length;i++){
		$(".checkbox")[i].children[0].checked=true;
		//$("#AlertMenuRecipient"+i+"")[0].checked=true;	
	}
}

function uncheckAllImmediate(){
	var length = $(".checkbox").length;
	for(i=0;i<length;i++){
		$(".checkbox")[i].children[0].checked=false;
		//$("#AlertMenuRecipient"+i+"")[0].checked=false;	
	}
}

function checkAllVoicePoll(){
	var length = $(".checkbox").length;
	for(i=0;i<length;i++){
		$(".checkbox")[i].children[0].checked=true;		
		//$("#VoicePollMenuRecipient"+i+"")[0].checked=true;	
	}
}

function uncheckAllVoicePoll(){
	var length = $(".checkbox").length;
	for(i=0;i<length;i++){
		$(".checkbox")[i].children[0].checked=false;
		//$("#VoicePollMenuRecipient"+i+"")[0].checked=false;	
	}
}