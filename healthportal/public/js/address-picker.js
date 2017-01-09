function setOptions(el, values){
    if (values){
        var select = $(el).children('select');
        $(select).html('');
        $.each(values, function(key, value){
            $(select).append($('<option>').val(key).html(value));
        });
    }
}

function setSelected(el, value){
    if (value){
        $(el).dropdown('set selected', value);
    } else {
        $(el).dropdown('clear');
    }
}

$(document).ready(function(){
    $('.address').change(function(){
        if ($(this).hasClass('notme'))
            return ;

        var value = $(this).dropdown('get value');
        var url = '/api/address/';
        var addr_type = '';

        if (value == null){
            url = url + 'all';
        } else {
            var addr_type = '';
            if ($(this).hasClass('address-city')){
                addr_type = 'city';
            } else if ($(this).hasClass('address-province')){
                addr_type = 'province';
            } else if ($(this).hasClass('address-region')){
                addr_type = 'region'
            }
            url = url + addr_type + '/' + value;

        }
        var parent = $(this).parent().parent();
        var addresses = $(parent).find('.address');

        console.log(this);
        console.log(addresses);
        $('.address').each(function(){
            $(this).addClass('notme');
        });

        $.ajax({
            url: url,
            method: 'get'
        }).done(function(r){
            var addresses = $(parent).find('.address');
            addresses.each(function(){
                if ($(this).hasClass('address-city')){
                    setOptions(this, r.choices.cities);
                    setSelected(this, r.match.city);
                } else if ($(this).hasClass('address-province')){
                    setOptions(this, r.choices.provinces);
                    setSelected(this, r.match.province);
                } else if ($(this).hasClass('address-region')){
                    setSelected(this, r.match.region);
                }
            });

        }).always(function(r){
            $('.address').each(function(){
                $(this).removeClass('notme');
            });
        });
    });
});
