function clearProgress(){
    $('.checkmark-space').text('');

    $('.progress').progress('reset');
}

function sync(hindi){
    var button = hindi;
    var progress = $('.progress');
    clearInterval(window.fakeProgress);
    clearProgress();
    selectors = ['cr', 'in', 'se'];

    $(button).parent().children('.button').addClass('disabled');
    window.fakeProgress = setInterval(function(){
        $(progress).progress('increment');

        newCheck = selectors.shift();
        $('.checkmark-space.'+newCheck).html($('<i >').addClass('checkmark icon'));
        if ($(progress).progress('is complete')){
            clearInterval(window.fakeProgress);
            $(button).parent().children('.button').removeClass('disabled');
        }
    }, 3000);
}

$(document).ready(function(){
    $('.ui.modal.sync').modal();

    $('#cancel-button').click(function(){
        $('.ui.modal.sync').modal('hide');
    });

    $('.sync-toggle').click(function(){
        $('.ui.modal.sync').modal('show');
    });

    $('.progress').progress({
        total: 3,
        text: {
            active: '{value}/{total} Synced',
            success: "Sync success!"
        }
    });

    $('#sync-button').click(function(){
        sync(this);
    });
});