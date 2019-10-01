$(document).ready(function()
{
	$('.buttonEditUser').on('click', function()
	{
		id = $(this).attr('id').substr(14);
		
		$('.userId').val(id);

		$('.userName').val($('.editUserName-' + id).val());

		$('.email').val($('.editEmail-' + id).val());
		$('.noCheckEmail').val($('.editEmail-' + id).val());

		$('.phone').val($('.editPhone-' + id).val());
		$('.noCheckPhone').val($('.editPhone-' + id).val());

		$('.anotherPhone').val($('.editAnotherPhone-' + id).val());
		$('.noCheckAnotherPhone').val($('.editAnotherPhone-' + id).val());

		$('.listImage').val($('.editNameImage-' + id).val());
		$('.editImages').html($('.editImage-' + id).val());

		$('.userLevel').val($('.editLevel-' + id).val());
		$('.userLevelList').html($('.editLevelList-' + id).val());
	});

	$('.buttonRemoveUser').on('click', function()
	{
		id = $(this).attr('id').substr(16);

		$('#nameRemoveUser').text($('.editName-' + id).val());
		$('#idRemoveUser').val(id);
	});

	$('.buttonRestoreUser').on('click', function()
	{
		id = $(this).attr('id').substr(17);

		$('#nameRestoreUser').text($('.editName-' + id).val());
		$('#idRestoreUser').val(id);
	});

	$(document).on('click', '.setLevel', function()
	{
		$('.userLevel').val($(this).attr('level'));
	});
});