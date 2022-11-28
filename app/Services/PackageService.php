<?php


namespace App\Services;


use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\PackageDay;
use App\Models\PackageDetail;

class PackageService extends BaseService
{
    static protected $model = Package::class;
    static protected $resource = PackageResource::class;

    public function create($data)
    {
        $stringDays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $pakcage = Package::create([
            'name' => ['en' => @request()->name['en'], 'ar' => @request()->name['ar'],],
            'price' => request()->price,
            'cost' => request()->cost,
            'number_of_days' => request()->number_of_days,
            'number_of_meals' => request()->number_of_meals,
        ]);
//        foreach (request('day') as $one) {
//            PackageDay::create([
//                'occasion' => $days[$one],
//                'day' => $one,
//                'package_id' => $pakcage->id,
//            ]);
//        }

        collect(request('days'))->map(function ($day) use ($pakcage, $stringDays) {
            return PackageDay::query()->create([
                'occasion' => $stringDays[$day],
                'day' => $day,
                'package_id' => $pakcage->id,
            ]);
        });

        foreach (request('details') as $one) {
            PackageDetail::create([
                'group_id' => $one['id'],
                'quantity' => $one['quantity'],
                'package_id' => $pakcage->id,
            ]);
        }
        return;
    }

    public function update($id, $data)
    {
        Package::query()->where('id', $id)->update([
            'name' => ['en' => @request()->name['en'], 'ar' => @request()->name['ar'],],
            'price' => @request()->price,
            'cost' => @request()->cost,
            'number_of_days' => @request()->number_of_days,
            'number_of_meals' => @request()->number_of_meals,
        ]);

        $stringDays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];


//        foreach (request('day') as $one) {
//            PackageDay::create([
//                'occasion' => $days[$one],
//                'day' => $one,
//                'package_id' => $pakcage->id,
//            ]);
//        }

        $packageDay = PackageDay::query()->where('package_id', $id)->exists();

        if ($packageDay) {
            PackageDay::query()->where('package_id', $id)->delete();
        }

        collect(request('days'))->map(function ($day) use ($id, $stringDays) {
            return PackageDay::query()->create([
                'occasion' => $stringDays[$day],
                'day' => $day,
                'package_id' => $id,
            ]);
        });

         PackageDetail::query()->where('package_id', $id)->delete();
         foreach (request('details') as $one) {
             PackageDetail::create([
                 'group_id' => $one['id'],
                 'quantity' => $one['quantity'],
                 'package_id' => $id,
             ]);
         }
        return;
    }

    public function delete($id)
    {
        Package::where('id', $id)->delete();
        PackageDetail::query()->where('package_id', $id)->delete();
        PackageDay::query()->where('package_id', $id)->delete();
    }


}
