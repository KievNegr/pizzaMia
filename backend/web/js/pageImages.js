$(".addNewImage").change(function()
{
    $(".sendNewImage").submit();
});

$(document).on("submit", ".sendNewImage", function(e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "index.php?r=page/uploadimage",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: getNewImages,
        beforeSend: sendImages,
        complete: completeSendImages
    });
});

function getNewImages(data)
{
    $(".newImages").append(data);
    addTofield();
}

$(".addEditImage").change(function()
{
    $(".sendEditImage").submit();
});

$(document).on("submit", ".sendEditImage", function(e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "index.php?r=page/uploadimage",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: getEditImages,
        beforeSend: sendImages,
        complete: completeSendImages
    });
});

function getEditImages(data)
{
    $(".editImages").append(data);
    addTofield();
}

function addTofield()
{
    $('.listImage').val('');
    $.each($('.gotImages .imageRun'), function () { 
        nameImage = $(this).attr('nameImage');
        $('.listImage').val($('.listImage').val() + nameImage + ',');
    });
}

function sendImages()
{
    $('.spinner-border').fadeIn();
}

function completeSendImages()
{
    $('.spinner-border').fadeOut();
}

$(document).on('click', '.imageInsert', function()
{
    image = '<img src="' + $(this).attr('address') + '" />';
    $('.pageText').val($('.pageText').val() + image);
});

$(document).on('click', '.deleteImage', function(e)
{
    nameDelete = $(this).attr("nameDelete");
    $('.' + nameDelete).remove();
    addTofield();
});