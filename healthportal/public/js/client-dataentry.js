$(".ui.dropdown").dropdown();
$(".ui.search.selection.dropdown").dropdown({
    forceSelection: false
});   
$(".ui.checkbox").checkbox();
$('.datepicker').datepicker({
    autoclose: true,
});

var STI = { 'syp_scr_res' : 'Reactive for Syphilis',
            'syp_conf_res' : 'Positive for Syphilis',
            'gono_res' : 'Positive for Gonorrhea',
            'ngi_res' : 'Positive for NGI',
            'tri_res' : 'Positive for Trichomoniasis',
            'hepab_res' : 'Positive for Hepatitis B',
            'hepac_res' : 'Positive for Hepatitis C',

            'gen_warts_res' : 'With Genital Warts',
            'gen_anal_res' : 'With Genito-Anal Warts',
            'anal_warts_res' : 'With Anal Warts',
            'herpes_res' : 'With Herpes',
            'bacvag_res' : 'With Bacterial Vaginosis',
            'cand_res'  : 'With Candidiasis'
           };

var STIDiagnosis = [];

function deleteEntry(diagnosis){
    for (var i=STIDiagnosis.length-1; i>=0; i--) {
        if (STIDiagnosis[i] === diagnosis) {
            STIDiagnosis.splice(i, 1);
        }
    }
}

function UICParse(){
    function invalidUIC(){ 
        $("#uic").closest('div.field').addClass('error');
        $("#birthdate").closest('div.field').addClass('error');
        $("#age").closest('div.field').addClass('error');
    }

    function validUIC(){
        $("#uic").closest('div.field').removeClass('error');
        $("#birthdate").closest('div.field').removeClass('error');
        $("#age").closest('div.field').removeClass('error');

        $("#birthdate").val("Invalid UIC");
        $("#age").val("Invalid UIC");
    }

    var uic = $("#uic").val();
    if(uic.length == 14){
        regex = /[A-Za-z]{4}\d{10}/;

        if(regex.test(uic)){
            validUIC();
            month = uic.substring(6,8);
            day = uic.substring(8,10);
            year = uic.substring(10,14);

            var d = new Date();
            var yearnow = d.getFullYear();
            var monthnow = d.getMonth() + 1;
            var daynow = d.getDate();

            if((month > 0 && month < 13) && (day > 0 && day < 32) && (year <= yearnow)){
                $("#birthdate").val(month+'/'+day+'/'+year);

                var age = yearnow - year;

                if(monthnow < month){
                    age--;
                }else if(month == monthnow){
                    if(daynow < day){
                        age--;
                    }
                }

                $("#age").val(age);

            }else{
                invalidUIC();
            }
            
            
        }else{
            invalidUIC();
        }
    }
}

function getAddressDefaults(x){
    if ($(x).hasClass('permanent')){
        return {
            shc_munc: $('.munc_select.current').dropdown('get value'),
            shc_prov: $('.prov_select.current').dropdown('get value'),
            shc_reg: $('.region_select.current').dropdown('get value')
        }
    } else {
        return {
            shc_munc: $("#fixed-munc").data('value'),
            shc_prov: $("#fixed-prov").data('value'),
            shc_reg: $("#fixed-reg").data('value')
        }
    }
}

function isResident(x){
    if ($(x).parent().checkbox('is checked')){
        is_resident = $(x).val();
    }

    d = getAddressDefaults(x);
    shc_munc =d['shc_munc'];
    shc_prov = d['shc_prov'];
    shc_reg = d['shc_reg'];

    inline_fields = $(x).parent().parent().parent();
    munc = $(inline_fields).find('.munc_select');
    prov = $(inline_fields).find('.prov_select');
    reg = $(inline_fields).find('.region_select');

    if(is_resident == '1'){
        $(munc).dropdown('set selected', shc_munc).addClass('disabled');
        $(prov).dropdown('set selected', shc_prov).addClass('disabled');
        $(reg).dropdown('set selected', shc_reg).addClass('disabled');
    }else{
        $(munc).dropdown('clear').removeClass('disabled');
        $(prov).dropdown('clear').removeClass('disabled');
        $(reg).dropdown('clear').removeClass('disabled');
    }
}

// function updatePermanent(x){
//     $('#is_perm_resident').parent().checkbox('set unchecked');
    // if ($('#is_perm_resident').parent().checkbox('is checked')){
    //     if ($(x).hasClass('munc_select')){
    //         $('.munc_select.perm').dropdown('set selected', $(x).dropdown('get value'));
    //     } else if ($(x).hasClass('prov_select')){
    //         $('.prov_select.perm').dropdown('set selected', $(x).dropdown('get value'));
    //     } else if ($(x).hasClass('region_select')){
    //         $('.region_select.perm').dropdown('set selected', $(x).dropdown('get value'));
    //     }
    // }
// }

function isFemale(){
    sex = $(".sex_dropdown").dropdown('get value');

    if(sex == '2'){
        $('#is_pregnant_fields').css("display", "flex").hide().fadeIn();
        $('.female-only').each(function(){
            $(this).removeClass('disabled');
        });
        $('.male-only').each(function(){
            $(this).addClass('disabled');
            $(this).checkbox('uncheck');
        });

        $('.gi_dropdown .item').each(function(){
            if ($.inArray($(this).attr('data-value'), ['1', '3']) != -1){
                $(this).addClass('disabled');
            } else {
                $(this).removeClass('disabled');
            }
        });
    }else{
        $('#is_pregnant_fields').fadeOut();
        $('.male-only').each(function(){
            $(this).removeClass('disabled');
        });
        $('.female-only').each(function(){
            $(this).addClass('disabled');
            $(this).checkbox('uncheck');
        });
        $('div[toggle-for="wet_mount"] div.ui.checkbox').checkbox('uncheck');
        $("[test-entry='wet_mount']").fadeOut();

        $('.gi_dropdown .item').each(function(){
            if ($.inArray($(this).attr('data-value'), ['2', '4']) != -1){
                $(this).addClass('disabled');
            } else {
                $(this).removeClass('disabled');
            }
        });
    }
}

function setGI(){
    sex = $(".sex_dropdown").dropdown('get value');
    if (sex=='2'){
        $('.gi_dropdown').dropdown('set selected', '2');
    } else {
        $('.gi_dropdown').dropdown('set selected', '1');
    }
}


function clientType(){
    var client = 0;
    $(".client-type-option").each(function(){
        if($(this).parent().checkbox('is checked')){
            client = $(this).val();
        }
    });

    if(client == '1'){
        $('.subgroup.client-type').css("display", "flex").hide().fadeIn();
        $('.with-subgroup.client-type').attr('style', 'border: 0; margin-bottom: -.5em !important');
    }else{
        $('.with-subgroup.client-type').removeAttr('style');
        $('.subgroup.client-type').fadeOut();
    }
}

function syncInvalid(x){
    if ($(x).checkbox('is checked')){
        $('.invalid-checkbox').checkbox('set checked');
        $('input[name="invalid"]').val(1);
    } else {
        $('.invalid-checkbox').checkbox('set unchecked');
        $('input[name="invalid"]').val(0);
    }
}

$(".tests").on('click', function(event){
    var $checkbox = $(this).find('.ui.checkbox');
    var testEntry = $(this).attr('toggle-for');

    if(!$(event.target).hasClass('checkbox-label')){
        $checkbox.checkbox('toggle');
    }

    if($checkbox.checkbox('is checked')){
        $("div").find("[test-entry='" + testEntry + "']").css("display", "flex").hide().fadeIn();
        $(this).addClass('active');
    }else{
        $("div").find("[test-entry='" + testEntry + "']").fadeOut();
        $(this).removeClass('active');
    }

});

function getRiskGroup(){
    riskGroups = [];
    $('.riskgroupboxes input').each(function(){
        if($(this).parent().checkbox('is checked')){
            riskGroups.push($(this).attr('name'));
        }
    });

    if($.inArray('rg_rsw', riskGroups) > -1 || $.inArray('rg_nsw', riskGroups) > -1){
        $('#establishment-fields').css("display", "flex").fadeIn();
    }else{
        $('#establishment-fields').fadeOut();
    }
}

function setTransgender(x){
    var val = $(x).dropdown('get value');
    if (val > 2){
        $('input[name="rg_tg"]').parent().checkbox('set checked');
    } else {
        $('input[name="rg_tg"]').parent().checkbox('set unchecked');
    }
}

function prevTestedFollowUp(e){
    if($(e).checkbox('is checked')){
        value = $(e).children("input[type='radio']").val();

        $followUp = $(e).closest('.six.wide.column').next('.six.wide.column').find('.prev_tested_cont');

        if(value == '1'){
            $followUp.css("display", "flex").hide().fadeIn();
        }else{
            $followUp.fadeOut();
            $followUp.children('.field').children('.ui.radio.checkbox').checkbox('uncheck');
        }
    }
}

function inspectedFollowUp(x){
    $followUp = $(x).closest('.six.wide.column').next('.six.wide.column').find('.insp_with');

    if($(x).checkbox('is checked')){
        value = $(x).children("input[type='radio']").val();
        if(value == '1'){
            $followUp.removeClass('disabled');
        }else{
            $followUp.addClass('disabled');
            $followUp.children('.field').children('.ui.radio.checkbox').checkbox('uncheck');
            name = $followUp.children('.field').children('.ui.radio.checkbox').children("input").attr('name');
            console.log(STI[name]);
            deleteEntry(STI[name]);
        }
    }
}

function generateSTIDiagnosis(x){
    if($(x).checkbox('is checked') || $(x).hasClass('gs_res')){
        value = $(x).hasClass('gs_res') ? $(x).checkbox('is checked') : parseInt($(x).children('input').val());
        name = $(x).children("input").attr('name');
        if(value){
            STIDiagnosis.push(STI[name]);
        } else {
            deleteEntry(STI[name]);
        }
    }

    $("#STIDiagnosis").val(STIDiagnosis.join(', '));
}

function disableSyphConfirm(x){
    syphConfirmToggle = $('div[toggle-for="syph_confirm"] div.ui.checkbox');
    if ($(x).checkbox('is checked')){
        if ($(x).children('input').attr('value') == '1'){  // positive
            $(syphConfirmToggle).removeClass('disabled');
        } else { // negative
            $(syphConfirmToggle).checkbox('uncheck');
            toggle_for = $(syphConfirmToggle).parent().parent().attr('toggle-for');
            $("[test-entry='" + toggle_for + "']").fadeOut();
            $(syphConfirmToggle).addClass('disabled');
        }
    }
};


function disableHepVaccine(x){
    var vaccinetoggle =$('.hep_vac .ui.checkbox');
    if ($(x).checkbox('is checked')){
        if ($(x).children('input').attr('value') == '1'){
            $(vaccinetoggle).addClass('disabled');
        } else {
            $(vaccinetoggle).removeClass('disabled');
        }
    }
}

function replaceImage(){
    $('#imagefield').empty();
    $('#imagefield').append(
        $('<div class="field" />').append(
            '<input id="usrimage" name="usrimage" type="file">'
        )
    );
}

$(document).ready(function(){
    $(".tests").each(function(){
        var $checkbox = $(this).find('.ui.checkbox');
        var testEntry = $(this).attr('toggle-for');

        if($checkbox.checkbox('is checked')){
            $("div").find("[test-entry='" + testEntry + "']").css("display", "flex").hide().fadeIn();
        }else{
            $("div").find("[test-entry='" + testEntry + "']").fadeOut();
        }
    });

    if ($('input[name="invalid"]').val() == '1'){
        $('.invalid-checkbox').checkbox('check');
    } else {
        $('input[name="invalid"]').val(0);
        $('.invalid-checkbox').checkbox('uncheck');
    }

    UICParse();
    // $('.is_resident').each(function(){
    //     isResident(this);
    // });
    isFemale();
    clientType();
    getRiskGroup();

    setTransgender($('.gi_dropdown'));

    $('.prev_tested').each(function(){
        prevTestedFollowUp(this);
    });

    $('.insp_with').each(function(){
        $(this).addClass('disabled');
    });

    $('.inspected').each(function(){
        inspectedFollowUp(this);
    });

    $('.test_result').each(function(){
        generateSTIDiagnosis(this);
    });

    $('.test_result.syph_screen').each(function(){
        disableSyphConfirm(this);
    });

    $('.invalid-checkbox').each(function(){

    });

    $('.test_result.hepab_res').each(function(){
        disableHepVaccine(this);
    });

});

$("#uic").keypress(function(){
    UICParse();
});

$(".is_resident").change(function(){
    console.log('change');
    isResident(this);
});

$(".sex_dropdown").change(function(){
    isFemale();
    setGI();
});

$('.gi_dropdown').change(function(){
    setTransgender(this);
});

$(".client-type-option").change(function(){
    clientType();
})

$('.riskgroupboxes').change(function(){
    getRiskGroup();
});

$('.prev_tested').change(function(){
    prevTestedFollowUp(this);
});

$('.inspected').change(function(){
    inspectedFollowUp(this);
});

$('.test_result').change(function(){
    generateSTIDiagnosis(this);
});

$('.test_result.syph_screen').change(function(){
    disableSyphConfirm(this);
});

$('.invalid-checkbox').change(function(){
    syncInvalid(this);
});

// $('.ui.dropdown.current').change(function(){
//     updatePermanent(this);
// });

$('#replace-image').click(function(){
    replaceImage();
});

$('.test_result.hepab_res').change(function(){
    disableHepVaccine(this);
});