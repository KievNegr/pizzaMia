$(document).ready(function()
{
	$('.buttonEditPage').on('click', function()
	{
		$('.gotImages').html('');
		id = $(this).attr('id').substr(14);

		$('#editPageTitle').val($('.edit-title-' + id).val());
		$('#editPageSef').val($('.edit-sef-' + id).val());
		$('#editPageNoCheckSef').val($('.edit-sef-' + id).val());
		$('#editPageDescription').val($('.edit-description-' + id).val());
		$('.pageText').val($('.edit-text-' + id).val());
		$('.listImage').val($('.edit-inserted-images-' + id).val());
		$('.listImageNoCheck').val($('.edit-inserted-images-' + id).val());
		$('.editImages').html($('.edit-images-' + id).val());
		$('#editPageId').val(id);
		if($('.edit-showheader-' + id).val() == 1)
		{
			$('.checkShowheader').attr('checked', 'checked');
		}
		else
		{
			$('.checkShowheader').removeAttr('checked');
		}

		if($('.edit-showfooter-' + id).val() == 1)
		{
			$('.checkShowfooter').attr('checked', 'checked');
		}
		else
		{
			$('.checkShowfooter').removeAttr('checked');
		}
		
	});

	$('.buttonDeletePage').on('click', function()
	{
		id = $(this).attr('id').substr(16);

		$('#titleDeletePage').text($('.edit-title-' + id).val());
		$('#idDeletePage').val(id);
	});

	$('.buttonRestorePage').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('#titleRestorePage').text($('.edit-title-' + id).val());
		$('#idRestorePage').val(id);
	});

	$('.clearFields').on('click', function()
	{
		$('.gotImages').html('');
		$('.itemPrices').html('');
		$('.newPageModalXl input').val('');
		$('.newPageModalXl textarea').val('');
	});

	$(document).on('change', '.pageSort', function()
	{
		id = $(this).attr('idPage');
		val = $(this).val();

		$.ajax({
			type: 'POST',
			url: 'index.php?r=page/sortpage',
			data: {id: id, val: val},
			success: pageSorted,
		});
	});

	function pageSorted(data)
	{
		$('input[idPage=' + data + ']').addClass('sorted');
	}
});