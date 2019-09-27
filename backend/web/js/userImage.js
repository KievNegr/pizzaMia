$(".addNewImage").change(function()
{
    $(".sendNewImage").submit();
});

$(document).on("submit", ".sendNewImage", function(e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: 'index.php?r=user/uploadimage',
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
        url: "index.php?r=user/uploadimage",
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

$(document).on('click', '.deleteImage', function(e)
{
    $('.listImage').val('');
    $('.editImages').html('<div class="image default_avatar" style="background-image: url(../../frontend/web/images/avatar/default_avatar.png?' + Math.random() * 100 + ');"></div>');
});