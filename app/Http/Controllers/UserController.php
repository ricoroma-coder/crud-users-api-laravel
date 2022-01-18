<?php

namespace App\Http\Controllers;

use App\Models\ApiMessage;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $body = $request->all();

        if (isset($request->id))
        {
            if ($request->id <= 0)
            {
                $response = new ApiMessage(400, 'Parameter id must be valid');
                return $response->throwMessage();
            }

            $obj = User::query()->find($request->id);
        }
        else
            $obj = new User();

        $obj->setValues($body);

        if ($obj->getError() !== null)
            return $obj->getError()->throwMessage();

        $response = new ApiMessage(201, 'User registered.');

        try
        {
            $obj->saveOrFail();
        }
        catch(Exception $e)
        {
            $response->success = false;
            $response->status = $e->getCode();
            $response->message = $e->getMessage();
        }

        return $response->throwMessage();
    }

    public function find(Request $request, $id)
    {
        if (!isset($id) || $id <= 0)
        {
            $response = new ApiMessage(400, 'Parameter id must be valid');
            return $response->throwMessage();
        }

        $obj = User::query()->find($id);

        return response()->json([
            'id' => $obj->getAttribute('id'),
            'name' => $obj->getAttribute('name'),
            'address' => $obj->getAttribute('address'),
            'city' => $obj->getAttribute('city'),
            'state' => $obj->getAttribute('state'),
        ]);
    }
}
