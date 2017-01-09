function fancyTable(){
    $('.graph-table-section').removeClass('hidden');
    theadHeight = $(".chart-table-header").parent().outerHeight(true);
    $("table.fixed-header").css("margin-top", "-" + (theadHeight+5) + "px");

    $(".chart-table-header").each(function(){
        var theadWidth = $(this).width();
        var h = $(this).attr('h');
        $('.chart-table-div[h="' + h + '"]').css('width', theadWidth  + 'px');
    });
}

function getDateRange(months, years){
    if(years.length > 1){
        var range = months[0] + ' — ' + months[months.length - 1];
    }else{
        year = years[0];
        var range = months[0] + ' ' + years + ' — ' + months[months.length - 1] + ' ' + years;
    }

    return range;
}

function hiddenParams(svg, sti, stiData, range, count){
    if($('.newHiddenParam').length == 0){
        var newHiddenSVGParam = $("<input>").attr("type", "hidden").attr("name", "svg").addClass('newHiddenParam').val(svg);
        var newHiddenSTIParam = $("<input>").attr("type", "hidden").attr("name", "sti").addClass('newHiddenParam').val(sti);
        var newHiddenRangeParam = $("<input>").attr("type", "hidden").attr("name", "range").addClass('newHiddenParam').val(range);
        var newHiddenDataParam = $("<input>").attr("type", "hidden").attr("name", "data").addClass('newHiddenParam').val(JSON.stringify(stiData));
        var newHiddenCountParam = $("<input>").attr("type", "hidden").attr("name", "count").addClass('newHiddenParam').val(JSON.stringify(count));
        
        $('.hiddenParams').append(newHiddenSVGParam).append(newHiddenSTIParam).append(newHiddenDataParam)
            .append(newHiddenRangeParam).append(newHiddenCountParam);
    }else{
        $('.newHiddenParam[name="svg"]').val(svg);
        $('.newHiddenParam[name="data"]').val(JSON.stringify(stiData));
        $('.newHiddenParam[name="sti"]').val(sti);
        $('.newHiddenParam[name="range"]').val(range);
        $('.newHiddenParam[name="count"]').val(count);
    }
}

function drawTable(sti, data, months, years){
    headers = [
        { label: "Month", colspan: 1},
        { label: "Group", colspan: 2},
        { label: "Positive Cases", colspan: 1},
        { label: "Tests Done", colspan: 1},
        { label: "Positivity Rate", colspan: 1}
    ];

    table = "<table class='ui celled table selectable fixed-header'><thead><tr>";

    //Header
    $.each(headers, function(d, t){
        table += "<th class='chart-table-header' h='" + d + "' colspan='" + t.colspan + "'>"+ t.label + "<div class='chart-table-div' h='" + d + "'> " + t.label +" </div>" + "</th>";
    });

    table += "</tr></thead><tbody>";

    //Body
    types = {
        "Males" : {
            "data" : [
                {
                    "label": "Males",
                    "suffix": "_male",
                    "parent_sfx" : "_male"
                }
            ],
                
            "suffix" : "male"
        },

        "Females" : {
            "data" : [
                {
                    "label": "Pregnant Female",
                    "suffix": "_pregnant",
                    "parent_sfx" : "_female"
                },
                {
                    "label": "Non-pregnant Female",
                    "suffix": "_not_pregnant",
                    "parent_sfx" : "_female"
                }
            ],
            
            "suffix" : "_female"
        }
        
    };

    count = 0;
    $.each(types, function(){
        count += this.data.length;
    });

    if(sti == "bacvag_res"){
        count--;
    }

    $.each(months, function(i, x){
        table += "<tr><td class='centered month chart-data no-left-border' rowspan='" + (count+1) + "' month='" + i + "'>" + x + "</td></tr>";

        p = 0;
        s = 0;
        $.each(types, function(l, c){
            entries = this.data.length;
            if(entries > 1){
                colspan = 1;
            }else{
                colspan = 2;
            }

            if(l != "Males" || sti != "bacvag_res"){
                table += "<tr><td class='chart-data' rowspan='" + this.data.length + "' colspan='" + colspan + "' month='" + i + "' grp='" + p + "'>" + l + "</td>"
                grp = this.data;

                $.each(grp, function(o, u){
                    if(colspan == 1){
                        table += "<td class='chart-data' grp='" + p + "' subgrp='"+ s +"' month='" + i + "'>" + u.label + "</td>";
                    }

                    table += "<td class='centered chart-data' grp='" + p + "' subgrp='"+ s +"' month='" + i + "'>" + data[sti]["raw" + u.suffix][i]+"</td>";

                    if(o == 0){
                        if(entries > 1){
                            w = "";
                        }else{
                            w = "subgrp='"+ s +"'";
                        }

                        table += "<td class='centered chart-data' grp='" + p + "' "+ w +" ' month='" + i + "' rowspan='" + entries + "'>" + data[sti]["tested" + u.parent_sfx][i] + "</td>";
                    }

                    table += "<td class='centered chart-data' grp='" + p + "' subgrp='"+ s +"' month='" + i + "'>" + data[sti]["rate" + u.suffix][i]+"%</td>";

                    table += "</tr>"
                    s++;
                });
            }
            p++; 
        });
    });

    table += "</tbody></table>";

    $("#graph-data-table").html(table);
}

function generateGraph(sti, sti_name, columns, data, years, stiData){
    range = getDateRange(columns, years);

	$(function () {
        chart = Highcharts.chart('graph-container', {
            chart: {
                type: 'column',
                backgroundColor: '#faffea',
                plotBackgroundColor: '#f2f9db',
                style: {
                    fontFamily: 'inherit'
                }
            },

            title: {
                text: 'Positivity Rate for ' + sti_name 
            },

            subtitle: {
                text: range,
                style: {
                    "font-size" : "1.3em"
                }
            },

            xAxis: {
                categories: columns
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                max: 100,
                title: {
                    text: 'Positivity Rate: Positive Results/Tests Done (%)'
                }
            },

            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '%<br/>'
                }
            },

            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },

            exporting: {
                enabled: false
            },

            credits: {
                enabled: false
            },

            series: data[sti]
        });

        svg = chart.getSVG({
            chart: {
                backgroundColor: '#FFFFFF',
                spacingBottom: 30,
                spacingTop: 30,
                spacingLeft: 30,
                spacingRight: 30,
                style: {
                    fontFamily: 'sans-serif'
                },
                width: '1000'
            }
        });
        
        hiddenParams(svg, sti_name, stiData, range, count);
    });

    $('.reports-download-button').removeClass('hidden');
    $('.hide-first').removeClass('hidden');
};

function downloadImg(sti, months, years){
    range = getDateRange(months, years);
    var filename = sti + ' Positivity Rate for ' + range;

    chart.exportChartLocal({
            filename: filename
        }, {
            chart: {
                backgroundColor: '#FFFFFF',
                spacingBottom: 30,
                spacingTop: 30,
                spacingLeft: 30,
                spacingRight: 30,
                style: {
                    fontFamily: 'sans-serif'
                }
            }
        });
}

colors = ["#6B6F7A", "#47B89C", "#68c171"];

$(document).on('mouseover', '.chart-data', function(){
    cellMonth = $(this).attr('month');
    cellGrp = $(this).attr('grp');
    cellSubGrp = $(this).attr('subgrp');

    if(typeof cellMonth != "undefined"){
        if(typeof cellGrp === "undefined"){
            $("td[month='"+cellMonth+"']").addClass("table-hover");
            color = '#46B861';
        }else if(typeof cellSubGrp === "undefined"){
            $("td[month='"+cellMonth+"'][grp='"+cellGrp+"']").addClass("table-hover");
            $(".month[month='"+cellMonth+"']").addClass("table-hover");
            color = colors[cellGrp%3];
        }else{
            $("td[month='"+cellMonth+"'][grp='"+cellGrp+"']").addClass("table-hover");
            $(".month[month='"+cellMonth+"']").addClass("table-hover");
            
            color = colors[cellSubGrp%3];

            chart.series[cellGrp].data[cellMonth].setState('hover');
            chart.tooltip.refresh(chart.series[cellSubGrp].data[cellMonth]);
        }

        $(".table-hover").css('background-color', color);
    }
    

});


$(document).on('mouseout', '.chart-data', function(){
    if(typeof cellGrp != "undefined" && typeof cellMonth != "undefined"){
        chart.series[cellGrp].data[cellMonth];
        chart.tooltip.hide();
    }
    $(".table-hover").css('background-color', '');
    $(".table-hover").removeClass("table-hover");
});