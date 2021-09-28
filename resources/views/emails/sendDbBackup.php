// mail.sendMail 
// here where display the masaage content 
<html>
    <body>
        <div>
                <h1>{{$massage}}</h1>
            @foreach ($database as $db_name)
        <h1>{{$db_name}}</h1>
    @endforeach

        </div>
    </body>
</html>