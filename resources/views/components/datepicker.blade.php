<div class='d-inline-flex' style="max-height: 38px">
    <div class="date-range-input">
        <div class="input-group date mx-3" data-provide="datepicker" style="width: 230px">
            <input type="text" name="daterange" class="form-control date-range-input">
            <span class="input-group-append">
                <span class="input-group-text bg-white" style=" padding: 10px;">
                    <i class="bi bi-calendar"></i>
                </span>
            </span>
        </div>
    </div>

    @if ($paginateNumber == '')
        <input wire:model.lazy='paginateNumber' type="number" class="form-control" placeholder="0">
    @else
        <fieldset class="form-group paginate-select" style="width: 60px">
            <select wire:model='paginateNumber' class="form-select" id="basicSelect">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="">مخصصة</option>
            </select>
        </fieldset>
    @endif




</div>

<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            startDate: moment().startOf('day'),
            endDate: moment().startOf('day'),
            "locale": {
                "format": "YYYY-MM-DD",
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
                "firstDay": 1
            },
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' +
                end.format('YYYY-MM-DD'));

            @this.from = start.format('YYYY-MM-DD');
            @this.to = end.format('YYYY-MM-DD');
        });
    });
</script>
