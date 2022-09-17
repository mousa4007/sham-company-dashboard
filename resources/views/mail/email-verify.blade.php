{{-- @component('mail::message')
    # السلام عليكم

    الرجاء نسخ الكود وإرساله للمدير

    @component('mail::button', ['url' => ''])
        42009213
    @endcomponent

    شكرا لك,<br>
@endcomponent --}}

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <div style="max-width: 600px;background-color: #ededed;padding: 50px" dir="rtl">
        <h1 style="text-align: right">السلام عليكم ورحمة الله</h1>
        <br>

        <h3 style="text-align: right">
            الرجاء نسخ الكود وارساله للمدير
        </h3>
        <br>
        <h4 style="background-color: rgb(49, 49, 255);color: white;padding: 10px;display: inline-block;">
            {{ $rand }}</h4>
        <br>
        <h4>مع تحيات , شركة شام</h4>
    </div>
</body>

</html>
