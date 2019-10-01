$(document).ready(function()
{
	$('.addImage').change(function()
	{
		
		$('.sendImage').submit();
	});

	$(document).on('submit', '.sendImage', function(e)
	{
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: 'customer/uploadimage.html',
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: getNewImage,
			beforeSend: sendData,
			complete: completeSend
		})
	});

	function getNewImage(data)
	{
		gotData = $.parseJSON(data);
    	$(".avatar-area").html(gotData.text);
		$('.logged').css('background-image', 'url(images/avatar/' + gotData.img + ')');
		$('.imageCrop').click();
	}

	function sendData()
	{
	    $('.spinner-border').fadeIn();
	}

	function completeSend()
	{
	    $('.spinner-border').fadeOut();
	}
});