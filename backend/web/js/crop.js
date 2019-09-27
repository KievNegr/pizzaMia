$(document).ready(function()
{
    var source;
    var particularClass;

    $(document).on('click', '.imageCrop', function()
    {
        source = $(this).attr('address');
        imageWidth = $(this).attr('imageWidth');
        imageHeight = $(this).attr('imageHeight');
        particularClass = $(this).attr('particularclass');
        crop(source, imageWidth, imageHeight);
    });

    function crop(source, imageWidth, imageHeight) {
        
        type = $('#typeOfImage');

        $('#imageContainer').css('background-image', 'url(' + source + '?' + Math.random() * 100 + ')');
        $("#selectedArea").css({'top': 0, 'left': 0});

        windowHeight = $(window).height() - 150;
        windowWidth = $(window).width() - 150;

        ratio = imageWidth / imageHeight;

        if( imageHeight >= windowHeight )
        {
            
            if( imageWidth < windowWidth )
            {
                containerWidth = windowHeight * ratio;
                containerHeight = windowHeight;
            }
            else
            {
                if( ratio >= 1)
                {
                    containerWidth = windowHeight * ratio;
                    containerHeight = windowHeight;
                }
                else
                {
                    containerHeight = windowHeight;
                    containerWidth = containerHeight * ratio;
                }
            }
        }
        else if( imageHeight < windowHeight)
        {
            if( imageWidth < windowWidth )
            {
                containerWidth = imageWidth;
                containerHeight = imageHeight;
            }
            else
            {
                containerWidth = windowWidth;
                containerHeight = windowWidth / ratio;
            }
        }

        if( type.val() == 'box' )
        {
            areaSize = Math.min(containerHeight, containerWidth);
            
            $("#selectedArea").css({'width': type.attr('minWidth'), 'height': type.attr('minHeight')});

            $("#selectedArea").resizable({
                aspectRatio: 1 / 1,
                maxHeight: areaSize,
                maxWidth: areaSize,
                minHeight: type.attr('minHeight'),
                minWidth: type.attr('minWidth')
            });
        }
        else if ( type.val() == 'any' )
        {
            $("#selectedArea").css({'width': 100, 'height': 100});

            $("#selectedArea").resizable({
                maxHeight: containerHeight,
                maxWidth: containerWidth,
                minHeight: 100,
                minWidth: 100
            });
        }
        else if ( type.val() == 'fixed')
        {
            fixedRatio = type.attr('minWidth')/type.attr('minHeight');
            $("#selectedArea").css({'width': containerWidth, 'height': containerWidth / fixedRatio});

            /*$("#selectedArea").resizable({
                aspectRatio: 1 / 1,
                maxHeight: type.attr('minHeight'),
                maxWidth: type.attr('minWidth'),
                minHeight: type.attr('minHeight'),
                minWidth: type.attr('minWidth')
            });*/
        }

        $('#imageContainer').css({
            'width': containerWidth,
            'height': containerHeight,
            'margin-left': -containerWidth / 2
        });

        rate = imageWidth / containerWidth;
        $('#rate').val(rate);

        $('#fadeContainer').fadeIn();
        $('#imageContainer').fadeIn();
    }

    $('.closeImageContainer').on('click', function()
    {
        $('#fadeContainer').fadeOut();
        $('#imageContainer').fadeOut();
    });

    $(".setImage").on("click", function(e)
    {
        rate = $("#rate").val();

        pointX = Math.round($("#selectedArea").position().left * rate);
        pointY = Math.round($("#selectedArea").position().top * rate);
        areaWidth = Math.round($("#selectedArea").width() * rate);
        areaHeight = Math.round($("#selectedArea").height() * rate);

        $(".newImagePointX").val(pointX);
        $(".newImagePointY").val(pointY);
        $(".newImageAreaWidth").val(areaWidth);
        $(".newImageAreaHeight").val(areaHeight);

        data = {'pointX': pointX, 'pointY': pointY, 'areaWidth': areaWidth, 'areaHeight': areaHeight, 'source': source};

        $.ajax({
            url: 'index.php?r=site/crop',
            type: 'POST',
            data: {data: data},
            success: getсroppedImages
        });

        e.preventDefault();

        $("#fadeContainer").fadeOut();
        $("#imageContainer").fadeOut();
    });

    function getсroppedImages(data)
    {
        $('.avatar .pageImage').css('background-image', 'url(' + data + '?' + Math.random() * 100 + ')');
        $('.' + particularClass).css('background-image', 'url(' + data + '?' + Math.random() * 100 + ')');
    }
});