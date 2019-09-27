$(document).ready(function(){
	
	$('.collapse').collapse();

  $('.start').on('click', function()
	{
		$.each($('.image'), function()
		{
			img = $(this).val();

			$.ajax({
				type: "GET",
				url: "index.php?r=site/img",
				data: {file: img},
				success: add,
			});
		});
	});

	function add(data)
	{
		$('.img').append(data + ' is ready <br>');
		//alert(data);
	}

    // $( function() {
    //     $(".datepicker").datepicker();
    // } );

	$(function($) {
        $.mask.definitions['~']='[+-]';
        //$('#name_buy').mask("");
        $('.phone_mask').mask('+38(999)999-99-99');
        $('.time_mask').mask('99:99');
        //$("#tin").mask("99-9999999");
        //$("#ssn").mask("999-99-9999");
        //$("#product").mask("a*-999-a999");
        //$("#eyescript").mask("~9.99 ~9.99 999");
    });

	jQuery(function() {
      var picture = $('#sample_picture');

      // Make sure the image is completely loaded before calling the plugin
      picture.one('load', function(){
        // Initialize plugin (with custom event)
        picture.guillotine({eventOnChange: 'guillotinechange'});

        // Display inital data
        var data = picture.guillotine('getData');
        for(var key in data) { $('#'+key).html(data[key]); }

        // Bind button actions
        $('#rotate_left').click(function(){ picture.guillotine('rotateLeft'); });
        $('#rotate_right').click(function(){ picture.guillotine('rotateRight'); });
        $('#fit').click(function(){ picture.guillotine('fit'); });
        $('#zoom_in').click(function(){ picture.guillotine('zoomIn'); });
        $('#zoom_out').click(function(){ picture.guillotine('zoomOut'); });

        // Update data on change
        picture.on('guillotinechange', function(ev, data, action) {
          data.scale = parseFloat(data.scale.toFixed(4));
          for(var k in data) { $('#'+k).html(data[k]); }
        });
      });

      // Make sure the 'load' event is triggered at least once (for cached images)
      if (picture.prop('complete')) picture.trigger('load')
    });

  var disabledDays = [0, 6];

    var today = new Date();
    var week = new Date();
    week.setDate(today.getDate() + 7);

    $('.order-picker').datepicker({
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = disabledDays.indexOf(day) != -1;

                return {
                    disabled: isDisabled
                }
            }
        },
        minDate: today,
        maxDate: week,
        minHours: 9,
        maxHours: 18
    })

    $('.promo-picker').datepicker({
        minDate: today
    })
});