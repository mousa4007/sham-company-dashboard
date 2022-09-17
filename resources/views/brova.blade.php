<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body>

    <input type="text" name="daterange" value="01/01/2022 - 15/01/2022" />

    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                "locale": {
                    "format": "DD-MM-YYYY",
                    "separator": " - ",
                    "applyLabel": "تطبيق",
                    "cancelLabel": "إلغاء",
                    "fromLabel": "De",
                    "toLabel": "Até",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "الأحد",
                        "الاثنين",
                        "الثلاثاء",
                        "الأربعاء",
                        "الخميس",
                        "الجمعة",
                        "السبت"
                    ],
                    "monthNames": [
                        "كانون الثاني",
                        "شباط",
                        "آذار",
                        "نيسان",
                        "أيار",
                        "حَزِيران",
                        "تموز",
                        "آب",
                        "أيلول",
                        "تشرين الأول",
                        "تشرين الثاني",
                        "كانون الأول",
                    ],
                    "firstDay": 0
                }
            });
        });
    </script>
</body>

</html>
