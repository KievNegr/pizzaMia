$(document).ready(function()
{
	$('.optimize-category').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'category'},
			success: getCategory,
        	beforeSend: function(){$('.for-category').fadeIn();},
		})
	});

	function getCategory(data)
	{
		$('.for-category').fadeOut();
		$('.category-fl').html(data);
		$('.category-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}

	$('.optimize-page').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'page'},
			success: getPage,
        	beforeSend: function(){$('.for-page').fadeIn();},
		})
	});

	function getPage(data)
	{
		$('.for-page').fadeOut();
		$('.page-fl').html(data);
		$('.page-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}

	$('.optimize-option').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'option'},
			success: getOption,
        	beforeSend: function(){$('.for-option').fadeIn();},
		})
	});

	function getOption(data)
	{
		$('.for-option').fadeOut();
		$('.option-fl').html(data);
		$('.option-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}

	$('.optimize-ingredient').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'ingredient'},
			success: getIngredient,
        	beforeSend: function(){$('.for-ingredient').fadeIn();},
		})
	});

	function getIngredient(data)
	{
		$('.for-ingredient').fadeOut();
		$('.ingredient-fl').html(data);
		$('.ingredient-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}

	$('.optimize-avatar').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'avatar'},
			success: getAvatar,
        	beforeSend: function(){$('.for-avatar').fadeIn();},
		})
	});

	function getAvatar(data)
	{
		$('.for-avatar').fadeOut();
		$('.avatar-fl').html(data);
		$('.avatar-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}

	$('.optimize-goods').on('click', function()
	{
		$.ajax({
			type: "GET",
			url: "index.php?r=setting/clear",
			data: {object: 'goods'},
			success: getGoods,
        	beforeSend: function(){$('.for-goods').fadeIn();},
		})
	});

	function getGoods(data)
	{
		$('.for-goods').fadeOut();
		$('.goods-fl').html(data);
		$('.goods-good').html('<span class="btn btn-success btn-sm">Все в порядке</span>');
	}
});