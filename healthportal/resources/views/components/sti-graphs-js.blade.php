@section('sti-graphs-js')
            {{ Html::script('/js/sti-graphs.js') }}

			<script type="text/javascript">
                sti_list = {!! json_encode($sti_list) !!};
                sti_count = {{ count($sti_list) }};
                columns = {!! json_encode($months) !!};
                years = {!! json_encode($years) !!};

                data = JSON.parse($(".hiddenPageParams > input[name='data']").val());
                rawData = JSON.parse($(".hiddenPageParams > input[name='rawData']").val());

                $('.graph-button-selected').removeClass('graph-button-selected');
                $('.reports-download-button').addClass('hidden');
                $('.graph-table-section').addClass('hidden');
                $('.hide-first').addClass('hidden');

                if(sti_count < 2){
                    sti = $('.ui.button.graph-button').attr('value');
                    $('.ui.button.graph-button').addClass('graph-button-selected');
                    generateGraph(sti, sti_list[sti], columns, data, years, rawData[sti]);
                    drawTable(sti, rawData, columns, years);
                    fancyTable();
                }

                $(document).on('click', '.ui.button.graph-button', function(){
                    sti = $(this).attr('value');
                    $('.graph-button-selected').removeClass('graph-button-selected');
                    $(this).addClass('graph-button-selected');
                    generateGraph(sti, sti_list[sti], columns, data, years, rawData[sti]);
                    drawTable(sti, rawData, columns, years);
                    fancyTable();
                    $('.graph-note').addClass('hidden');
                });

                $(document).on('click', '#png-download', function(){
                    downloadImg(sti_list[sti], columns, years);
                });

            </script>

@stop