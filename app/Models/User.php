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

    public static function findUser($route, $id = null)
    {
        $specialFindRoutes = ['findUserState', 'findUserCity', 'findUserAddress'];
        $response = [];

        if (in_array($route, $specialFindRoutes))
            $response = self::findAll($route);
        else
            $response = self::findById($route, $id);

        return $response;
    }

    public static function findAll($route)
    {
        $users = self::all();
        $response = [];
        $searchField = '';

        switch ($route)
        {
            case 'findUserState':
                $searchField = 'state';
            break;
            case 'findUserCity':
                $searchField = 'city';
            break;
            case 'findUserAddress':
                $searchField = 'address';
            break;
        }

        foreach ($users as $user)
        {
            $response[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                $searchField => $user[$searchField]
            ];
        }

        return $response;
    }

    public static function findById($route, $id)
    {
        $response = [];

        if (!isset($id) || $id <= 0)
        {
            $response = new ApiMessage(400, 'Parameter id must be valid');
            return [
                'success' => $response->success,
                'message' => $response->message
            ];
        }

        $obj = self::query()->find($id);

        if ($obj === null || !$obj->exists())
        {
            $response = new ApiMessage(404, 'User not found');
            return [
                'success' => $response->success,
                'message' => $response->message
            ];
        }

        if ($route == 'findUser')
            $response = [
                'id' => $obj->getAttribute('id'),
                'name' => $obj->getAttribute('name'),
                'address' => $obj->getAttribute('address'),
                'city' => $obj->getAttribute('city'),
                'state' => $obj->getAttribute('state'),
            ];
        else
        {
            $searchField = '';
            switch ($route)
            {
                case 'findUserStateById':
                    $searchField = 'state';
                break;
            }

            $response = [
                'id' => $obj->getAttribute('id'),
                'name' => $obj->getAttribute('name'),
                $searchField => $obj->getAttribute($searchField)
            ];
        }

        return $response;
    }
}
