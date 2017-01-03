/*$(window).scroll(function() {
	if($(window).scrollTop() < $("#landing-button").offset().top+$("#landing-button").outerHeight()) {
		$(".nav").fadeOut();
	}else
	{
		$(".nav").fadeIn();
	}
});*/

function moveArrow() {
	$(".landing-arrow").animate({"margin-top":"-4rem"}).animate({"margin-top":"-6rem"});
	setTimeout(moveArrow, 5000);
}

setTimeout(function() {
	moveArrow();
}, 1000);

$("#invite-button").click(function() {
	var email = $("#invite-email").val();
	$.ajax({
		url: 'invite.php',
		type: 'POST',
		data: {'email':email},
		success: function(data) {
			$("#invite-button").attr("disabled",true);
			$("#invite-button").html(data);
			$("#invite-button").removeClass("error");
		},
		error: function(x,s,data) {
			$("#invite-button").html(x.responseText);
			$("#invite-button").addClass("error");
		}
	});
});

function loadOtherMessage(text) {
	var newMessage = $('<div class="message other">'+text.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</div>');
	newMessage.hide();
	$(".phone-messages").append(newMessage);
	newMessage.slideDown(100);
	scrollToBottom();
}

function loadSelfMessage(text) {
	var newMessage = $('<div class="message self">'+text.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</div>');
	newMessage.hide();
	$(".phone-messages").append(newMessage);
	newMessage.slideDown(100);
	scrollToBottom();
}

loadOtherMessage("Welcome to the live demo of JusText! To view all available commands, simply type \"?\" and press enter!");

$(".phone-textbox input").keydown(function(e) {
	if(e.keyCode == 13) {
		var input = $(this).val();
		loadSelfMessage(input);
		$.ajax({
			url:'http://api.justext.me/?Body='+input,
			success:function(data) {
				loadOtherMessage(data);
			}
		});
		$(this).val("");
	}
});

function scrollToBottom() {
	setTimeout(function() {
		$(".phone-messages").animate({
			scrollTop:$(".phone-messages").height()
		}, 100);
	}, 200);
}
