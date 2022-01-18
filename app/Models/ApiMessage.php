<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiMessage extends Model
{
    use HasFactory;

    public $success;
    public $message;
    public $status;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->success = true;
        if (!in_array($status, [200, 201]))
            $this->success = false;

        $this->message = $message;
    }

    public function throwMessage()
    {
        return response()->json([
            'success' => $this->success,
            'message' => $this->message
        ], $this->status);
    }
}
