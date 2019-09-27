$(".addNewImage").change(function()
{
    $(".sendNewImage").submit();
});

$(document).on("submit", ".sendNewImage", function(e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: 'index.php?r=ingredient/uploadimage',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: getNewImages,
        beforeSend: sendData,
        complete: completeSend
    });
});

function getNewImages(data)
{
    gotData = $.parseJSON(data);
    $(".newImages").html(gotData.text);
    $('.listImage').val(gotData.img);
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
        url: "index.php?r=ingredient/uploadimage",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: getEditImages,
        beforeSend: sendData,
        complete: completeSend
    });
});

function getEditImages(data)
{
    gotData = $.parseJSON(data);
    $(".editImages").html(gotData.text);
    $('.listImage').val(gotData.img);
}

function sendData()
{
    $('.spinner-border').fadeIn();
}

function completeSend()
{
    $('.spinner-border').fadeOut();
}