function clearInputs(){
    $(document).find('input:text').val('');
    $(document).find('input[type="number"]').val(0);
    $(document).find('.ui.checkbox').checkbox('uncheck');
    $(document).find('.ui.dropdown:not(.no-reset)').dropdown('clear');
    $(".message-container").fadeOut();
}

$.ajaxSetup( {
    headers: {
        'CSRFToken': $("meta[name='csrf-token']").attr('content')
    }
} );