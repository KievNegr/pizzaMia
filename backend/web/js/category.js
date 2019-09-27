$(document).ready(function()
{
	$('.buttonEditCategory').on('click', function()
	{
		$('.gotImages').html('');

		id = $(this).attr('id').substr(18);

		$('#edit-name').val($('.edit-title-' + id).val());
		$('#edit-sef').val($('.edit-sef-' + id).val());
		$('#setEditNoCheckName').val($('.edit-title-' + id).val());
		$('#setEditNoCheckSef').val($('.edit-sef-' + id).val());
		$('#setEditText').val($('.edit-text-' + id).val());
		$('.editImages').html($('.edit-image-' + id).val());
		$('.listImage').val($('.edit-name-image-' + id).val());
		
		$('#setEditId').val(id);
		if(	$('.edit-parent-' + id).val() != 0 )
		{
			value = $('.edit-parent-' + id).val();

			$('#setEditParent').val(value).attr('selected', 'selected');
		}
		else
		{
			$('#setEditParent').val(0).attr('selected', 'selected');
		}

		if($('.edit-main-' + id).val() == 1)
		{
			$('.categoryMain').attr('checked', 'checked');
		}
		else
		{
			$('.categoryMain').removeAttr('checked');
		}
		
	});

	$('.buttonDeleteCategory').on('click', function()
	{
		id = $(this).attr('id').substr(20);

		$('#titleDeleteCategory').text($('.edit-title-' + id).val());
		$('#idDeleteCategory').val(id);
	});

	$('.buttonRestoreCategory').on('click', function()
	{
		id = $(this).attr('id').substr(21);

		$('#titleRestoreCategory').text($('.edit-title-' + id).val());
		$('#idRestoreCategory').val(id);
	});

	$('.clearFields').on('click', function()
	{
		$('.gotImages').html('');
		$('.listImage').html('');
	});
});