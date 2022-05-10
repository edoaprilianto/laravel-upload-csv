<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\CsvUpload;



class ProgressEvent implements ShouldBroadcast{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message, $filename)
    {
        $this->message = $message;
        $this->filename = $filename;
        echo "running event";
    }


    public function broadcastOn(){
        return new Channel('UpTable');
    }

    
}
