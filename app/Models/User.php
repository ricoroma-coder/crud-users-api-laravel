<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'address',
        'city',
        'state',
    ];

    public $error = null;

    public $timestamps = false;

    public function setValues($values)
    {
        foreach ($values as $key => $value)
        {
            if (!in_array($key, $this->getFillable()) && $this->error === null)
                $this->error = new ApiMessage(400, "Attribute {$key} is not expected.");

            $this->setAttribute($key, $value);
        }
    }

    public function getError()
    {
        return $this->error;
    }
}
