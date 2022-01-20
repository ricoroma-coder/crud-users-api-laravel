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
            $obj->save();
        }
        catch(Exception $e)
        {
            $response->success = false;
            $response->status = $e->getCode();
            $response->message = $e->getMessage();
        }

        return $response->throwMessage();
    }

    public function find(Request $request, $id = null)
    {
        return response()->json(User::findUser($request->route()->getName(), $id));
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!isset($id) || empty($id) || $id <= 0)
        {
            $response = new ApiMessage(400, 'Parameter id must be valid');
            return $response->throwMessage();
        }

        $obj = User::query()->find($id);

        if ($obj === null || !$obj->exists())
        {
            $response = new ApiMessage(404, 'User not found');
            return $response->throwMessage();
        }

        $response = new ApiMessage(200, 'User deleted');

        try
        {
            $obj->delete();
        }
        catch (Exception $e)
        {
            $response->success = false;
            $response->status = 500;
            $response->message = 'Something got wrong';
        }

        return $response->throwMessage();
    }

    public function calculateTotal(Request $request)
    {
        $route = $request->route()->getName();
        $field = '';
        $getData = false;

        switch ($route)
        {
            case 'calcTotalByState':
            case 'calcTotalByStateData':
                $field = 'state';
                if ($route == 'calcTotalByStateData')
                    $getData = true;
            break;
            case 'calcTotalByCity':
            case 'calcTotalByCityData':
                $field = 'city';
            if ($route == 'calcTotalByCityData')
                $getData = true;
            break;
        }

        $users = User::all()->where($field, $request->get($field));
        $response['total'] = count($users);

        if ($getData)
            $response['users'] = $users;

        return response()->json($response);
    }
}
