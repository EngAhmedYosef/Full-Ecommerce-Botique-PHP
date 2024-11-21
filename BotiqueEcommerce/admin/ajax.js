
$(".modalBtn").click(function(){

	// get the text of read or unread
	var td = $(this).parent().prev().text();

	// jquery traversing
	$(this).parent().prev().text('read');

	

	// get row id 
	var id = $(this).attr('data-id');
	// var id = $(this).data('id');

	// update database view = 1 ;
	$.post('functions/messages/add.php' , {id : id} , function(res){
		
		// update the span with unread message number

		

		if (td == 'unread') {
			var num = Number($('.unreadMess').text());
			$('.unreadMess').text(num - 1);
		}


	})


})