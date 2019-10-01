$(document).ready(function()
{
	$('.buttonEditOption').on('click', function()
	{
		$('.gotImages').html('');
		id = $(this).attr('id').substr(16);
		
		$('#setEditUserId').val(id);
		$('#setEditOptionTitle').val($('.edit-title-' + id).val());
		$('#setEditOptionDescription').val($('.edit-description-' + id).val());
		$('.editImages').html($('.edit-image-' + id).val());
		$('.listImage').val($('.edit-name-image-' + id).val());
		value = $('.edit-category-' + id).val();
		$('#setEditOptionCategory').val(value).attr('selected', 'selected');
	});

	$('.buttonDeleteOption').on('click', function()
	{
		id = $(this).attr('id').substr(18);

		$('#titleDeleteOption').text($('.edit-title-' + id).val());
		$('#idDeleteOption').val(id);
	});

	$('.buttonRestoreOption').on('click', function()
	{
		id = $(this).attr('id').substr(19);

		$('#titleRestoreOption').text($('.edit-title-' + id).val());
		$('#idRestoreOption').val(id);
	});

	$('.clearFields').on('click', function()
	{
		$('.gotImages').html('');
		$('.listImage').html('');
	});
});