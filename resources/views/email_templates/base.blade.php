<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wellcome to send mail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="text-center w-100" style="width:70vw; text-align:center;">
        <h4>Cộng hòa xã hội chủ nghĩa Việt Nam</h4>
        <p>Độc lập - Tự do - Hạnh phúc</p>
        <br/>
        <h2>Thông báo</h2>
        <br/>
        <div style="width: 80%; margin:0 auto; text-align:left;">
            <p>Thân gửi: <span>{{ $data['receiver']}}</span></p>
            <p>{!! $data['content'] !!}</p>
        </div>
        <div style="width:100%; text-align:right">
            <p>Hà Nội, 28 tháng 7 năm 2022</p>
            <h4 style="margin: 0 auto">Hoàng Mike</h4>
        </div>
    </div>
    
    <p>Người gửi: {{ $data['sender'] }}</p>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>