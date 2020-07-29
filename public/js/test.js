$('#hard-refresh').click(function (event) {
    $(this).hide();
    $('#loading').show();
    $.ajax({
        url: '/hard',
        success: function (data) {
            $('#loading').hide();
            $(this).show();
            location.reload();
        },
        error: function (data) {
            location.reload();
        }
    });
});
