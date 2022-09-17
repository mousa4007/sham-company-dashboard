<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>لوح التحكم</title>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Harmattan&family=Tajawal&display=swap" rel="stylesheet">


</head>

<body class="block">
    <div class="block__cell">
        <a href="/home" class="btn--activate" id="btnActivation">
            <span class="btn__icon"></span>
             <span class="btn__text" data-wait="جار التحميل" data-after="تم التحميل">تسجيل الدخول</span>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
