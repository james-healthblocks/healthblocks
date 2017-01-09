@section('script')
            <script type="text/javascript">

                $(document).ready(function() {

                    $("#message-block").fadeOut();

                    $('#import-button').addClass('disabled');

                    $("#exportselect").dropdown('set selected', "csv");

                    $('#message-icon').on('click', function() {
                        $("#message-block").fadeOut();

                        $('#importbar').progress({
                            percent: 0
                        });
                    });

                    $('.import-toggle').click(function() {
                        console.log($('.import-toggle').data("mode"));
                        $('#importmodal')
                            .modal('setting', 'closable', false)
                            .modal('show');
                    });

                    $('#cancel-button').click(function(){
                        $('#importmodal').modal('hide');

                        $('#importbar').progress({
                            percent: 0
                        });
                    });

                    $( "#importform" ).submit(function( event ) {
                        $('#importbar').progress({
                            percent: Math.floor((Math.random() * 40) + 50)
                        });
                        $('#import-button, #cancel-button').addClass('disabled');

                        var mydata = $('#importform').serialize();
                        // Create a formdata object and add the files
                        var data = new FormData(this);
                        $.each(files, function(key, value)
                        {
                            data.append(key, value);
                        });

                        var ext = $('input[name=uploadfile]').val().split('.').pop().toLowerCase();

                        $.ajax({
                            type    : "POST",
                            url     : "import/" + ext,
                            data    : data,
                            processData: false, // Don't process the files
                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                            success : function(response) {

                                $('#import-button, #cancel-button').removeClass('disabled');

                                $('#importbar').progress({
                                    percent: 100
                                });
                                
                                console.log(response);
                                var type = response["type"];
                                var msgtype = (type == 1) ? "ui message positive" : "ui message negative";

                                (type == 1) ? $('#importbar').progress('set success') : $('#importbar').progress('set error');
                                
                                $("#message-block").fadeIn();

                                $("#message-block").removeClass();
                                $("#message-block").addClass(msgtype);

                                // $("#message-block").replaceWith('<div class="ui ' + msgtype + ' message" id="message-block">');
                                $("#message-p").replaceWith('<p id="message-p">' + response["message"] + '</p>');

                            },
                        });

            /*
                        $.post('/import/', {
                            _token: $('meta[name=csrf-token]').attr('content')
                        })
                       .done(function(data, status) {
                           alert("Data: " + data + "\nStatus: " + status);
                           // return data;
                       })
                       .fail(function(error) {
                           alert( "error" );
                           console.log('error', error );
                       });*/

                        event.preventDefault();
                    });

                    // Variable to store your files
                    var files;

                    // Add events
                    $('input[name=uploadfile]').on('change', prepareUpload);

                    // Grab the files and set them to our variable
                    function prepareUpload(event)
                    {
                        var origin = $('input[name=_origin]').val();
                        var ext = $('input[name=uploadfile]').val().split('.').pop().toLowerCase();

                        if (origin == "reached" && ext == "csv") {
                            $('#import-button').removeClass('disabled');
                        }
                        else if (origin !== "reached" && (ext == "csv" || ext == "bin")) {
                            $('#import-button').removeClass('disabled');
                        }
                        else {
                            $('#import-button').addClass('disabled');
                        }

                        files = event.target.files;
                        console.log(files);
                    }
                });



            </script>
@stop