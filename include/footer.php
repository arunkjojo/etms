        </div><!-- end of div.container-->

    </body>

    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password input').attr("type") == "text"){
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass( "glyphicon-eye-close" );
                    $('#show_hide_password i').removeClass( "glyphicon-eye-open" );
                }else if($('#show_hide_password input').attr("type") == "password"){
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass( "glyphicon-eye-close" );
                    $('#show_hide_password i').addClass( "glyphicon-eye-open" );
                }
            });
            $("#show_hide_re_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_re_password input').attr("type") == "text"){
                    $('#show_hide_re_password input').attr('type', 'password');
                    $('#show_hide_re_password i').addClass( "glyphicon-eye-close" );
                    $('#show_hide_re_password i').removeClass( "glyphicon-eye-open" );
                }else if($('#show_hide_re_password input').attr("type") == "password"){
                    $('#show_hide_re_password input').attr('type', 'text');
                    $('#show_hide_re_password i').removeClass( "glyphicon-eye-close" );
                    $('#show_hide_re_password i').addClass( "glyphicon-eye-open" );
                }
            });
        });
    </script>

</html>