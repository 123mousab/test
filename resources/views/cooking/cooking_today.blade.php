<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>جدول الطبخ اليومي</title>
</head>

<body>
    
        <div>
          <div class="container-fluid">
            <table class="mt-3">
              <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>بروتين</th>
                  <th>كارب</th>
                  <th>وجبة 1</th>
                  <th>عدد وجبة 1</th>
                  <th>كارب 1</th>
                  <th>وجبة 2</th>
                  <th>عدد وجبة 2</th>
                  <th>كارب 2</th>
                  <th>فطور</th>
                  <th>شوربة</th>
                  <th>سلطة</th>
                  <th>ملاحظات</th>
                  <th>الباقة</th>
                  <th>المندوب</th>
                  <th>فترة التوصيل</th>
                  <th>الجهة</th>
                  <th>المنطقة</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($data as $key => $row)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['protein'] }}</td>
                    <td>{{ $row['carbohydrates'] }}</td>
                    <td>{{ $row['details_recipies'][0]['recipe_name'] }}</td>
                    <td>{{ $row['details_recipies'][0]['quantity'] }}</td>
                    <td>{{ $row['carbohydrates'] }}</td>
                    <td>{{ $row['details_recipies'][1]['recipe_name'] }}</td>
                    <td>{{ $row['details_recipies'][1]['quantity'] }}</td>
                    <td>{{ $row['carbohydrates'] }}</td>
                    <td>{{ $row['details_recipies'][2]['recipe_name'] }}</td>
                    <td>{{ $row['details_recipies'][3]['recipe_name'] }}</td>
                    <td>{{ $row['details_recipies'][4]['recipe_name'] }}</td>
                    <td style="color: red">
                      (بدون:
                      @foreach ($row['note_recipe'] as $key2 => $item)
                      <span>
                        {{ $item }}
                        @if ($key2 !== count($row['note_recipe']) - 1)
                        <span 
                        >،</span>
                        @endif
                      </span>
                      @endforeach
                      ) 
                      (
                    @foreach ( $row['note_ingredients'] as $key3 => $item2 )
                    @if ($key3 !== count($row['note_ingredients']) - 1)  
                    <span
                        >
                        {{ $item2 }}
                        <span 
                        >،</span>
                        @endif
                      </span>
                      @endforeach  
                      )                        
                    </td>
                    <td>{{ $row['package_name'] }}</td>
                    <td>{{ $row['delivery'] }}</td>
                    <td>{{ $row['period'] }}</td>
                    <td>{{ $row['side'] }}</td>
                    <td>{{ $row['area'] }}</td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
</body>
</html>

<style>

  
      
@font-face {
        font-family: 'janna';
        src: url('/fonts/Janna.ttf');
      }
  
    body {
        direction: rtl;
    }
      table {
        border-collapse: collapse;
        width: 100%;
      }
    
      td,
      th {
        border: 1px solid #dddddd;
        text-align: right;
        padding: 8px;
        font-weight: bold;
      }
      th {
        padding-top: 4px;
        padding-bottom: 6px;
        color: #fff;
      }
      h2 {
        text-align: center;
        color: #000;
        margin-top: 15px;
      }
    
      th:nth-child(1),
      th:nth-child(2),
      th:nth-child(3),
      th:nth-child(4),
      th:nth-child(5) {
        background-color: #808080;
      }
      th:nth-child(6),
      th:nth-child(7),
      th:nth-child(8),
      th:nth-child(9),
      th:nth-child(10),
      th:nth-child(11),
      th:nth-child(12),
      th:nth-child(13),
      th:nth-child(14),
      th:nth-child(15),
      th:nth-child(16),
      th:nth-child(17) {
        background-color: #bf8f00;
      }
    
      th:nth-child(18),
      th:nth-child(19),
      th:nth-child(20) {
        background-color: #2f75b5;
      }
    
      th:nth-child(21),
      th:nth-child(22),
      th:nth-child(23),
      th:nth-child(24),
      th:nth-child(25),
      th:nth-child(26) {
        background-color: #808080;
      }

      th:nth-child(14) {
        width: 500px !important;
      }

    </style>