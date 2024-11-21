


$('.modalBtn').click(function(){

	// change the unread text to read using jquery traversing
	// $('.view').text('read');
	$(this).parent().prev().text('read');

	// get the row id from the custom attribute data-id
	var id = $(this).attr('data-id');
	// var id = $(this).data('id');

	// use ajax to update the message view from 0 to 1 
	$.post('functions/messages/add.php' , { id : id } , function(res){

		console.log(res);

	})


})