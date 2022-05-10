<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\CsvUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\ProgressEvent;
use DB;


class CsvProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $header;
    public $data;
    public $filename;
    public $status;
    public $count;

    public function __construct($data, $header,$filename,$count)
    {
        $this->data = $data;
        $this->header = $header;
        $this->filename = $filename;
        $this->count = $count;
    }

    public function handle(){  
     
            foreach ($this->data as $row) {
                $sellData = array_combine($this->header,$row);
                $record = Product::where('UNIQUE_KEY',$sellData['UNIQUE_KEY'])->first();

                if (is_null($record)){
                     $product = Product::create([
                            'UNIQUE_KEY' => $sellData['UNIQUE_KEY'],
                            'PRODUCT_TITLE' => $sellData['PRODUCT_TITLE'],
                            'PRODUCT_DESCRIPTION' => $sellData['PRODUCT_DESCRIPTION'],
                            'STYLE#' => $sellData['STYLE#'],
                            'SANMAR_MAINFRAME_COLOR' => $sellData['SANMAR_MAINFRAME_COLOR'],
                            'SIZE' => $sellData['SIZE'],
                            'COLOR_NAME' => $sellData['COLOR_NAME'],
                            'PIECE_PRICE' => $sellData['PIECE_PRICE'],
                            'FILE_NAME' => $this->filename
                        ]);
                 }
                 else {
                    $product = Product::where('UNIQUE_KEY',$sellData['UNIQUE_KEY'])->update([
                            'PRODUCT_TITLE' => $sellData['PRODUCT_TITLE'],
                            'PRODUCT_DESCRIPTION' => $sellData['PRODUCT_DESCRIPTION'],
                            'STYLE#' => $sellData['STYLE#'],
                            'SANMAR_MAINFRAME_COLOR' => $sellData['SANMAR_MAINFRAME_COLOR'],
                            'SIZE' => $sellData['SIZE'],
                            'COLOR_NAME' => $sellData['COLOR_NAME'],
                            'PIECE_PRICE' => $sellData['PIECE_PRICE'],
                            'FILE_NAME' => $this->filename
                    ]);
                 }
            }

        }


    public function failed(Exception $exception){
        CsvUpload::create(['file_name'=> $filename,'status'=> 'failed']);
    }
}
