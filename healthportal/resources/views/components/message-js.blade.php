@section('message-js')
            <script type="text/javascript">
                function showMessage(type, header, message){
                    
                    $("#message-block").removeClass('error');
                    $("#message-block").removeClass('success');
                    $("#message-block").removeClass('warning');

                    $("#message-block").addClass(type);
                    $("#message-block > .header").text(header);

                    if($.isArray(message)){
                        body = "<ul>";
                        $.each(message, function(i,text){
                            body += "<li>" + text + "</li>";
                        });
                        body += "</ul>";
                    }else{
                        body = message;
                    }

                    $("#message-block > p ").html(body);
                    $(".message-container").css("display", "flex").hide().fadeIn();
                }

                function hideMessage(){
                    $(".message-container").fadeOut();
                }

                $(document).ready(function(){
                    $('.message .close').on('click', function() {
                        $(".message-container").fadeOut();
                    });
                });
            </script>
@stop