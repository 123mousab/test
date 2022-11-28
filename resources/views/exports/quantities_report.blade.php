<table>
    <thead>
    <tr>
        <th># Subscribers for today
            المشتركين لهذا اليوم#
        </th>
        <th>#Active Subscribers
            المشتركين الفعالين #
        </th>
        <th>#Temp. Subscriber
            الأيقافات المؤقتة#
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{$item['day']}}</td>
            <td>{{$item['active']}}</td>
            <td>{{$item['stop']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>وجبة البروتين</th>
        <th>العدد</th>
        <th>مجموع البروتين</th>
        <th>مجموع الكارب</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_protein'] as $item_protein)
        <tr>
            <td>{{$item_protein['recipie_protein_name']}}</td>
            <td>{{$item_protein['count']}}</td>
            <td>{{$item_protein['sum_protein']}}</td>
            <td>{{$item_protein['sum_carb']}}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>وجبة الكارب</th>
        <th>العدد</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_carb'] as $item_carb)
            <tr>
                <td>{{$item_carb['recipie_carb_name']}}</td>
                <td>{{$item_carb['count']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>وجبة البروتين مع سبايسي</th>
        <th>العدد</th>
        <th>مجموع البروتين</th>
        <th>مجموع الكارب</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_protein_with_spicy'] as $item_p_s)
            <tr>
                <td>{{$item_p_s['recipie_protein_name']}}</td>
                <td>{{$item_p_s['count']}}</td>
                <td>{{$item_p_s['sum_protein']}}</td>
                <td>{{$item_p_s['sum_carb']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>وجبة الكارب مع سبايسي</th>
        <th>العدد</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_carb_with_spicy'] as $item_c_s)
            <tr>
                <td>{{$item_c_s['recipie_carb_name']}}</td>
                <td>{{$item_c_s['count']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>


<table>
    <thead>
    <tr>
        <th>وجبة البروتين بدون سبايسي</th>
        <th>العدد</th>
        <th>مجموع البروتين</th>
        <th>مجموع الكارب</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_protein_without_spicy'] as $item_p_s)
            <tr>
                <td>{{$item_p_s['recipie_protein_name']}}</td>
                <td>{{$item_p_s['count']}}</td>
                <td>{{$item_p_s['sum_protein']}}</td>
                <td>{{$item_p_s['sum_carb']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>وجبة الكارب بدون سبايسي</th>
        <th>العدد</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['meal_carb_without_spicy'] as $item_c_s)
            <tr>
                <td>{{$item_c_s['recipie_carb_name']}}</td>
                <td>{{$item_c_s['count']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>الباقة
        </th>
        <th>العدد
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['list_package'] as $package)
            <tr>
                <td>{{$package['name']}}</td>
                <td>{{$package['count']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>الميزة
        </th>
        <th>الوصفة
        </th>
        <th>العدد
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        @foreach($item['group'] as $group)
            <tr>
                <td>{{$group['group_name']}}</td>
                <td>{{$group['recipie_name']}}</td>
                <td>{{$group['sum_quantity']}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
