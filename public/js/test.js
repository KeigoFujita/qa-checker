$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var company_id = button.data('id');
    var company_name = button.data('name');
    var modal = $(this);
    modal.find('input[name=name]').val(company_name);
    modal.find('input[name=company_id]').val(company_id);
});


$('#editCallModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var company_id = button.data('company-id');
    var rating = button.data('rating');
    var duration = button.data('duration');
    var amount_earned = button.data('amount-earned');

    var mins = Math.floor(duration / 60);
    var seconds = duration % 60;

    var modal = $(this);

    modal.find('input[name=id]').val(id);
    modal.find('select[name=company_id]').val(company_id);
    modal.find('input[name=rating]').val(rating);
    modal.find('#minutes').val(mins);
    modal.find('#seconds').val(seconds);
    modal.find('input[name=amount_earned]').val(amount_earned);
});

$('#deleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var company_id = button.data('id');
    var company_name = button.data('name');
    var modal = $(this);
    modal.find('#company_name').text(company_name);
    modal.find('input[name=company_id]').val(company_id);
});

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
        error:function(data){
            location.reload();
        }
    });
});
