


$('.modalBtn').click(function(){

	

	// get the row id from the custom attribute data-id
	var id = $(this).attr('data-id');

	console.log(id)
	// var id = $(this).data('id');

	// use ajax to update the message view from 0 to 1 
	$.post('functions/messages/delete.php' , { id : id } , function(res){

		console.log(res);

	})


})