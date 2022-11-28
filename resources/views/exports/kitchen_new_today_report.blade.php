<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>رقم الجوال</th>
        <th>الباقة</th>
        <th>عدد ايام الباقة</th>
        <th>عدد وجبات الباقة</th>
        <th>تاريخ بداية الاشتراك</th>
        <th>تاريخ نهاية الاشتراك</th>
        <th>المكونات الاساسية المستثناة</th>
        <th>المكونات الغير الاساسية المستثناة</th>
        <th>البروتين</th>
        <th>الكارب</th>
        <th>وجبة 1</th>
        <th>وجبة بروتين</th>
        <th>وجبة كارب</th>
        <th>وجبة 2</th>
        <th>وجبة بروتين</th>
        <th>وجبة كارب</th>
        <th>وجبة 3</th>
        <th>وجبة بروتين</th>
        <th>وجبة كارب</th>
        <th>المدينة</th>
        <th>الحي</th>
        <th>المندوب</th>
        <th>الفترة</th>
        <th>ملاحظات توصيل</th>
        <th>الميزة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{$item['customer']['name']}}</td>
            <td>{{$item['customer']['mobile']}}</td>
            <td>{{$item['package']['name']}}</td>
            <td>{{$item['package']['number_of_days']}}</td>
            <td>{{$item['package']['number_of_meals']}}</td>
            <td>{{$item['subscribe']['start_date']}}</td>
            <td>{{$item['subscribe']['end_date']}}</td>
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
            <td>{{$item['protein']}}</td>
            <td>{{$item['carb']}}</td>
            @if(collect($item['menu_selected']['item'])->count() == 1)
                @foreach($item['menu_selected']['item'] as $newItem)
                    <td>{{ $newItem['ingredient_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_protein_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_carb_name'] ?? '-' }}</td>
                @endforeach
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif
            @if(collect($item['menu_selected']['item'])->count() == 2)
                @foreach($item['menu_selected']['item'] as $newItem)
                    <td>{{ $newItem['ingredient_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_protein_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_carb_name'] ?? '-' }}</td>
                @endforeach
                <td></td>
                <td></td>
                <td></td>
            @endif
            @if(collect($item['menu_selected']['item'])->count() == 3)
                @foreach($item['menu_selected']['item'] as $newItem)
                    <td>{{ $newItem['ingredient_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_protein_name'] ?? '-' }}</td>
                    <td>{{ $newItem['recipie_carb_name'] ?? '-' }}</td>
                @endforeach
            @endif
            @if(collect($item['menu_selected']['item'])->count() == 0)
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif
            <td>{{$item['deliveries']['city']}}</td>
            <td>{{$item['deliveries']['branch']}}</td>
            <td>{{$item['deliveries']['delegate_name']}}</td>
            <td>{{$item['deliveries']['period']}}</td>
            <td>{{$item['deliveries']['notes']}}</td>
            @if(collect($item['group_menu'])->count() > 0)
                @foreach(collect($item['group_menu'])->all() as $newItem)
                    <td>
                        الميزة: {{ $newItem['group_name'] }}<br>
                        الوصفة: {{ $newItem['recipie_name'] }}<br>
                        العدد: {{ $newItem['quantity'] }}
                    </td>
                @endforeach
            @else
                <td>-</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
