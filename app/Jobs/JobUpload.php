<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use App\Events\ProgressEvent;
use App\Jobs\CsvProcess;
use App\Models\CsvUpload;
use App\Models\Product;



class JobUpload implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


   

    public $chunks;
    public $file_name;

    public function __construct($chunks,$file_name)
    {
        $this->chunks = $chunks;
        $this->file_name = $file_name;
        // CsvUpload::where('file_name',$this->file_name)->update(['status' => 'processing']);

    }


    protected function start() {

        CsvUpload::where('file_name',$this->file_name)->update(['status' => 'processing']);
        event(new ProgressEvent('processing',$this->file_name));
    }


     protected function stop() {

        CsvUpload::where('file_name',$this->file_name)->update(['status' => 'completed']);
        event(new ProgressEvent('completed',$this->file_name));
    }



    protected function process() {

        $header = [];
        CsvUpload::where('file_name',$this->file_name)->update(['status' => 'processing']);
        event(new ProgressEvent('processing',$this->file_name));
        $chunks = array_chunk($this->chunks,4000);
        foreach ($chunks as $key => $chunk) {
              $data = array_map('str_getcsv', $chunk );
              if($key == 0){
                    $header = self::remove_bs($data[0]);
                    unset($data[0]);
                }

              foreach ($data as $row) {
                    $sellData = array_combine($header,$row);
                    $record = Product::where('UNIQUE_KEY',$sellData['UNIQUE_KEY'])->first();
                    if (is_null($record)){
                           Product::insert([
                                  'UNIQUE_KEY' => $sellData['UNIQUE_KEY'],
                                  'PRODUCT_TITLE' => $sellData['PRODUCT_TITLE'],
                                  'PRODUCT_DESCRIPTION' => $sellData['PRODUCT_DESCRIPTION'],
                                  'STYLE#' => $sellData['STYLE#'],
                                  'SANMAR_MAINFRAME_COLOR' => $sellData['SANMAR_MAINFRAME_COLOR'],
                                  'SIZE' => $sellData['SIZE'],
                                  'COLOR_NAME' => $sellData['COLOR_NAME'],
                                  'PIECE_PRICE' => $sellData['PIECE_PRICE'],
                                  'FILE_NAME' => $this->file_name
                              ]);
                       }
                       else {
                          Product::where('UNIQUE_KEY',$sellData['UNIQUE_KEY'])->update([
                                  'PRODUCT_TITLE' => $sellData['PRODUCT_TITLE'],
                                  'PRODUCT_DESCRIPTION' => $sellData['PRODUCT_DESCRIPTION'],
                                  'STYLE#' => $sellData['STYLE#'],
                                  'SANMAR_MAINFRAME_COLOR' => $sellData['SANMAR_MAINFRAME_COLOR'],
                                  'SIZE' => $sellData['SIZE'],
                                  'COLOR_NAME' => $sellData['COLOR_NAME'],
                                  'PIECE_PRICE' => $sellData['PIECE_PRICE'],
                                  'FILE_NAME' => $this->file_name
                          ]);
                       }
                    }
              }
    
    }

  
    public function handle(){

        $this->start();
        $this->process();
        $this->stop();
    }

    function remove_bs($Str) {

    $ss = implode(",",$Str);  
    $StrArr = str_split($ss); $NewStr = '';
          foreach ($StrArr as $Char) {    
            $CharNo = ord($Char);
            if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
            if ($CharNo > 31 && $CharNo < 127) {
              $NewStr .= $Char;    
            }
          }  
          return explode(",", $NewStr);
    }
}
