<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Send Password</title>
</head>
<body>
    <p>Thân gửi: <h6>{{ $data['receiver']}}</h6></p>
    <p>{{ $data['message']}}</p>
    <p>Người gửi: {{ $data['sender']}}</p>
</body>
</html>