<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Imports\SubscribeImport;
use App\Imports\TestImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelController extends Controller
{
    public function importCustomers()
    {
        Excel::import(new TestImport(), 'customers.xlsx');

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function importSubscribes()
    {
        Excel::import(new SubscribeImport(), request()->file('file'));

        return response()->json([
            'message' => 'success'
        ]);
    }
}
