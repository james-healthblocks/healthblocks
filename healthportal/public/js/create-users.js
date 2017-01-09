$('.ui.dropdown').dropdown();

function hideShowRegions(){
    role = $('#role').dropdown('get value');
    console.log(role);
    if (parseInt(role[0]) > 3){
        $('#addr-fields').css('display', 'flex');
    } else {
        $('#addr-fields').css('display', 'none');
    }
}

$(document).ready(function(){
    hideShowRegions();
    $("li[menu='account-users']").addClass('active');
});

$('#role').change(function(){
    hideShowRegions();
});