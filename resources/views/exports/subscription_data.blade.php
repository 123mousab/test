<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>رقم الجوال</th>
        <th>الباقة</th>
        <th>تاريخ الاشتراك</th>
        <th>تاريخ نهاية الاشتراك</th>
        <th>عدد الايام المتبقية بالاشتراك</th>
        <th>حالة الاشتراك</th>
        <th>تاريخ الايقاف</th>
        <th>تاريخ الاستئناف</th>
        <th>كمية البروتين</th>
        <th>كمية الكارب</th>
        <th>ملاتحظات الاشتراك</th>
        <th>المكونات الاساسية المستثناة</th>
        <th>المكونات غير الاساسية المستثناة</th>
        <th>المدينة</th>
        <th>الحي</th>
        <th>المندوب</th>
        <th>فترة التوصيل</th>
        <th>سبت</th>
        <th>احد</th>
        <th>اثنين</th>
        <th>ثلاثاء</th>
        <th>اربعاء</th>
        <th>خميس</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{$item['customer']['name']}}</td>
            <td>{{$item['customer']['mobile']}}</td>
            <td>{{$item['package']['name']}}</td>
            <td>{{$item['subscribe']['start_date']}}</td>
            <td>{{$item['subscribe']['end_date']}}</td>
            <td>{{$item['subscribe']['remind_days']}}</td>
            <td>{{$item['subscribe']['subscribe_is_ended']}}</td>
            <td>{{$item['subscribe']['stop_subscription']['start_date']}}</td>
            <td>{{$item['subscribe']['stop_subscription']['end_date']}}</td>
            <td>-</td>
            <td>{{$item['protein']}}</td>
            <td>{{$item['carb']}}</td>
            <td>{{$item['personal_desires']['notes']}}</td>
            <td>
                @foreach($item['exclude_main_ingredients'] as $excludeMainIngredients)
                    {{$excludeMainIngredients['name']}},
                @endforeach
            </td>
            <td>
                @foreach($item['personal_desires']['exclude_not_main_ingredients'] as $excludeNotMainIngredients)
                    {{$excludeNotMainIngredients}},
                @endforeach
            </td>
            <td>{{$item['deliveries']['city']}}</td>
            <td>{{$item['deliveries']['branch']}}</td>
            <td>{{$item['deliveries']['delegate_name']}}</td>
            <td>{{$item['deliveries']['period']}}</td>
            <td>{{$item['subscribe']['days']['st']}}</td>
            <td>{{$item['subscribe']['days']['su']}}</td>
            <td>{{$item['subscribe']['days']['mo']}}</td>
            <td>{{$item['subscribe']['days']['tu']}}</td>
            <td>{{$item['subscribe']['days']['we']}}</td>
            <td>{{$item['subscribe']['days']['th']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
