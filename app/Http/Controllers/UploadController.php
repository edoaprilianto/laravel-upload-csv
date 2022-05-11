<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UploadCsv;
use App\Models\CsvUpload;
use App\Models\Product;
use Redirect;
use App\Jobs\testjob;
use Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use App\Jobs\CsvProcess;
use App\Jobs\JobUpload;
use App\Events\ProgressEvent;
use Carbon\Carbon;
use DB;
use DataTables;
use Throwable;





class UploadController extends Controller
{
    

	public function __construct(){
		ini_set('max_execution_time', 180);

    }

     public function index(){


     	  $data = [
     	  	'row' => CsvUpload::orderBy('created_at', 'DESC')->get()
     	  ];

          return view('index',$data);
     }

     
     public function upload_csv_records(Request $request){

     		$start = Carbon::now();
     		$file = $request->file('file');
	        $csv    = file($request->file);

     		$filename = time().'_'.$file->getClientOriginalName();
     		event(new ProgressEvent('pending',$filename));

     		CsvUpload::insert([
     			'file_name'=> $filename,
     			'status'=> 'pending',
     			'created_at' => now()->toDateTimeString(),
     			'updated_at' => now()->toDateTimeString(),
     		]);

     		 $job = new JobUpload($csv,$filename);
    		 $job->delay($start->addSeconds(10));
    		 dispatch($job);
        
     }
 
}
