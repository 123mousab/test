<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>الباقة</th>
        <th>البروتين</th>
        <th>الكارب</th>
        <th>وجبة بروتين</th>
        <th>عدد</th>
        <th>وجبة كارب</th>
        <th>عدد</th>
        <th>وجبة بروتين</th>
        <th>عدد</th>
        <th>وجبة كارب</th>
        <th>عدد</th>
        <th>وجبة بروتين</th>
        <th>عدد</th>
        <th>وجبة كارب</th>
        <th>عددد</th>
        <th>الملاحظات</th>
        <th>المكونات المستثناة</th>
        <th>(CheckSum)</th>
        <th>اعداد المميزات</th>
        <th>شوربة</th>
        <th>سلطة</th>
        <th>سناك</th>
        <th>فطور</th>
        <th>(CheckSum)</th>
        <th>عدد الوجبات المتبقية</th>
        <th>المدينة</th>
        <th>الحي</th>
        <th>المندوب</th>
        <th>الفترة</th>
        <th>ملاحظات توصيل</th>
        <th>الشركة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{$item['customer']['name']}}</td>
            <td>{{$item['package']['name']}}</td>
            <td>{{$item['protein']}}</td>
            <td>{{$item['carb']}}</td>

            @if(isset($data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[0]]))

                <td> {{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[0]]['recipie_protein_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[0]]['protein_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif
            @if(isset($data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[0]]))

                <td> {{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[0]]['recipie_carb_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[0]]['carb_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif

            @if(isset($data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[1]]))

                <td> {{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[1]]['recipie_protein_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[1]]['protein_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif
            @if(isset($data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[1]]))

                <td> {{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[1]]['recipie_carb_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[1]]['carb_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif

            @if(isset($data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[2]]))

                <td> {{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[2]]['recipie_protein_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['protein_item'][$proteinIdsWithMenuDetails[2]]['protein_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif
            @if(isset($data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[2]]))

                <td> {{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[2]]['recipie_carb_name'] }}</td>
                <td>{{ $data[$key]['menu_selected']['carb_item'][$carbIdsWithMenuDetails[2]]['carb_count'] }}</td>
            @else
                <td></td>
                <td></td>
            @endif
                <td>{{ $item['personal_desires']['notes'] }}</td>
            <td>
                @foreach($item['exclude_main_ingredients'] as $excludeMainIngredients)
                    {{$excludeMainIngredients['name']}},
                @endforeach
                @foreach($item['personal_desires']['exclude_not_main_ingredients'] as $excludeNotMainIngredients)
                    {{$excludeNotMainIngredients}},
                @endforeach
            </td>
            @if(collect($data[$key]['menu_selected']['item'])->first()['ingredient_id'] == 0)
                <td>0</td>
            @else
                <td>1</td>
            @endif
{{--             كميات الوجبات بعد الفلترة الصحيحة     --}}
            <td>
                @foreach($groups as $group)
                    {{$group->name}} : {{ @collect($data[$key]['group_subscription'])->pluck('group_id')->contains($group->id) ?
                    @collect($data[$key]['group_subscription'])->groupBy('group_id')[$group->id][0]['quantity'] : 0 }} <br>
                @endforeach
            </td>
            @foreach($groups as $group)
                <td>{{ $data[$key]['group_menu'][$group->id]['recipie_name'] ?? '-----' }}</td>
            @endforeach
            <td>
                @if(is_null(collect($data[$key]['group_subscription'])->pluck('group_id')->diff(collect($data[$key]['group_menu'])->pluck('group_id'))->first()))
                    1
                @else
                    0
                @endif
            </td>
            <td>{{ $item['subscribe']['remind_days'] }}</td>
            <td>{{$item['deliveries']['city']}}</td>
            <td>{{$item['deliveries']['branch']}}</td>
            <td>{{$item['deliveries']['delegate_name']}}</td>
            <td>{{$item['deliveries']['period']}}</td>
            <td>{{$item['deliveries']['notes']}}</td>
            <td>{{$item['deliveries']['company']}}</td>
        </tr>
    @endforeach
</tbody>
</table>
