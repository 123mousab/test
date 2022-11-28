<?php


namespace App\Http\Controllers\Admin;


use App\Exports\KitchenTodayExport;
use App\Exports\NewDeliveriesExport;
use App\Exports\QuantitiesExport;
use App\Exports\SubscriptionDataExport;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\Response;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{
    public function exportDeliveriesExport()
    {
        return Excel::download(new NewDeliveriesExport(), 'delivers.xlsx');
    }

    public function exportKitchenTodayExport()
    {
        $menu = Menu::query()->whereDate('cooking_date', request('date', today()))->exists();

        if (!$menu){
            return Response::error(422)->withMessage('لا يوجد لهذا اليوم جدول طبخ')->send();
        }

        return Excel::download(new KitchenTodayExport(), 'kitchen_today.xlsx');
    }

    public function exportQuantitiesToday()
    {
        $menu = Menu::query()->whereDate('cooking_date', request('date', today()))->exists();

        if (!$menu){
            return Response::error(422)->withMessage('لا يوجد لهذا اليوم جدول طبخ')->send();
        }

        return Excel::download(new QuantitiesExport(), 'quantities_today.xlsx');
    }

    public function exportSubscriptionData()
    {
        return Excel::download(new SubscriptionDataExport(), 'subscription_data.xlsx');
    }
}
