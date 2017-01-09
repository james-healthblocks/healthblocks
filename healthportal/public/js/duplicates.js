function getChecked(hindi, duplicate){
    table = $(hindi).parent().parent().parent().parent();
    ids = [];
    $(table).find('tbody .checkbox.checked input').each(function(index){
        ids.push($(this).attr('id').split("_")[1]);
    });

    reason = $(table).find('th[colspan=6]').text();
    promise = sendChecked(ids, duplicate, reason);
    promise.done(function(r){
        loadList(content);
    }).fail(function(r){
        alert('Failed to Mark Duplicates');
        loadList(content);
    });
}

function checkAllOrNothing(hindi){
    table = $(hindi).parent().parent().parent().parent();
    if ($(hindi).checkbox('is checked')){
        $(table).find('tbody .checkbox').each(function(){
            $(this).checkbox('check');
        });
    } else {
        $(table).find('tbody .checkbox').each(function(){
            $(this).checkbox('uncheck');
        });
    }
}
function countChecks(hindi){
    table = $(hindi).parent().parent().parent().parent();
    checked = $(table).find('.checkbox.checked').length;
    if (checked == 1){
        $(table).find('.button').addClass('disabled');
        $(table).find('.different-button').removeClass('disabled');
    } else if (checked > 1){
        $(table).find('.button').removeClass('disabled');
    } else {
        $(table).find('.button').addClass('disabled');
    }
}

function sendChecked(checked, duplicate, reason){
    return $.ajax({
        method: 'post',
        url: '/client/duplicates/resolve',
        data: {
            checked: checked,
            duplicate: duplicate,
            reason: reason
        },
        dataType: 'json',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    });
}

function createTables(parent, r){
    $(parent).text('');
    r = r['duplicates'];
    columns = [
        'uic', 'firstname', 'middlename', 'lastname',
        'sex', 'municipality'
    ];
    for (uic in r){
        tbody = $('<tbody />');
        tfoot = $('<tfoot />');
        thead = $('<thead />');
        table = $('<table />').addClass('ui celled table');
        thead.append(
            $('<tr />').addClass('dark').append(
                $('<th />').addClass('collapsing').append(
                    $('<div />').addClass('ui fitted checkbox').append(
                        $('<input type="checkbox" />')
                    ).append(
                        $('<label />')
                    )
                )
            ).append(
                $('<th colspan="6" />').text(uic)
            )
        );
        head_row_2  = $('<tr />');
        head_row_2.append($('<th />').addClass('collapsing'));
        $.each(columns, function(index, value){
            head_row_2.append(
                $('<th />').text(
                    value
                )
            );
        });
        $(thead).append(head_row_2);
        for (line in r[uic]){
            row = $('<tr />').append(
                $('<td class="collapsing" />').append(
                    $('<div />').addClass('ui fitted checkbox').append(
                        $('<input type="checkbox" />').prop('id', 'cb_'+r[uic][line].client_id)
                    ).append(
                        $('<label />')
                    )
                )
            );
            $.each(columns, function(index, value){
                v = r[uic][line][value];
                if (value == 'sex'){
                    v = (v == '1') ? 'M' : 'F';
                } else if (value == 'municipality'){
                    v = cities[v];
                }
                row.append(
                    $('<td />').text(
                        v
                    )
                );
            });
            tbody.append(row);
        }
        tfoot.append(
            $('<tr />').append(
                $('<th colspan="7" />').append(
                    $('<div />').addClass('ui right floated small button disabled different-button').text('Mark as Different').click(function() {
                        getChecked(this, false);
                    })
                ).append(
                    $('<div />').addClass('ui small primary button disabled').text('Mark as Duplicate').click(function (){
                        getChecked(this, true);
                    })
                )
            )
        );
        table.append(thead);
        table.append(tbody);
        table.append(tfoot);
        $(parent).append(table);
    }
}

function loadList(content){
    return $.ajax({
        method: 'get',
        url: '/client/duplicates/list',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(r){   
            createTables(content, r);        
            $('.ui.checkbox').checkbox();
            $('.ui.checkbox').change(function(){
                countChecks(this);
            });
            $('thead .ui.checkbox').change(function(){
                checkAllOrNothing(this);
            });
        },
        failure: function(r){
            $(content).text("Failed to retrieve records.");
        },
    });
}

$(document).ready(function() {
    loadList(content);
});