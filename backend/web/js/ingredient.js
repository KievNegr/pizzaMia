$(document).ready(function()
{
	$('.buttonEditIngredient').on('click', function()
	{
		$('.gotImages').html('');
		
		id = $(this).attr('id').substr(20);

		$('#editIngredientName').val($('.edit-name-' + id).val());
		$('#nameNoCheckEditIngredient').val($('.edit-name-' + id).val());
		$('.editImages').html($('.edit-image-' + id).val());
		$('.listImage').val($('.edit-name-image-' + id).val());
		$('#editIngredientId').val(id);
	});

	$('.buttonDeleteIngredient').on('click', function()
	{
		id = $(this).attr('id').substr(22);

		$('#titleDeleteIngredient').text($('.edit-name-' + id).val());
		$('#idDeleteIngredient').val(id);
	});

	$('.buttonRestoreIngredient').on('click', function()
	{
		id = $(this).attr('id').substr(23);

		$('#titleRestoreIngredient').text($('.edit-name-' + id).val());
		$('#idRestoreIngredient').val(id);
	});

	$('.clearFields').on('click', function()
	{
		$('.gotImages').html('');
		$('.listImage').html('');
	});
});