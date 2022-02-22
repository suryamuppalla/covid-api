<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HospitalController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pinCode = (int) $request->pinCode;
        $hospitalName = Str::lower($request->hospital);
        $address = Str::lower($request->address);

        $hospitals = Hospital::query();

        if ($pinCode) {
            $hospitals = $hospitals->where('pinCode', 'LIKE', '%' . $pinCode . '%');
        }
        if ($hospitalName) {
            $hospitals = $hospitals->where(DB::raw('lower(name)'), 'LIKE', '%' . $hospitalName . '%');
        }

        if ($address) {
            $hospitals = $hospitals->where(DB::raw('lower(address)'), 'LIKE', '%' . $address . '%');
        }

        $hospitals = $hospitals->get();
        // $hospitals = Hospital::where('name', 'LIKE', "%{$hospitalName}%")
        //     ->orWhere('pinCode', 'LIKE', "%{$pinCode}%")
        //     ->get();
        foreach ($hospitals as $hospital) {
            $hospital['bed'] = json_decode($hospital['bed']);
            $hospital['ventilator'] = json_decode($hospital['ventilator']);
            $hospital['oxygen'] = json_decode($hospital['oxygen']);
            $hospital['isolation'] = json_decode($hospital['isolation']);
        }
        $success['data'] = $hospitals;
        $success['message'] = 'Hospitals List';
        return $this->sendResponse($success);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|',
            'address' => 'required|string',
            'pinCode' => 'required|integer',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string',
            'description' => 'required|string',
            'bed' => 'required',
            'ventilator' => 'required',
            'oxygen' => 'required',
            'isolation' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $hospital = Hospital::create([
            'name' => $request->name,
            'address' => $request->address,
            'pinCode' => $request->pinCode,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'bed' => json_encode($request->bed),
            'ventilator' => json_encode($request->ventilator),
            'oxygen' => json_encode($request->oxygen),
            'isolation' => json_encode($request->isolation)
        ]);
        if ($hospital) {
            $hospital['bed'] = json_decode($hospital['bed']);
            $hospital['ventilator'] = json_decode($hospital['ventilator']);
            $hospital['oxygen'] = json_decode($hospital['oxygen']);
            $hospital['isolation'] = json_decode($hospital['isolation']);
            $success['data'] =  $hospital;
            $success['message'] = "Hospital Created successfully..";
            return $this->sendResponse($success);
        } else {
            $error = "Sorry! Hospital Creation is not successful.";
            return $this->sendError($error, 401);
        }

        // return response(['employee' => new HospitalResource($employee), 'message' => 'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $employee)
    {
        // return response(['employee' => new
        //     HospitalResource($employee), 'message' => 'Success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hospital $employee)
    {

        $employee->update($request->all());

        // return response(['employee' => new
        //     HospitalResource($employee), 'message' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Employee $employee
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Hospital $employee)
    {
        $employee->delete();

        return response(['message' => 'Employee deleted']);
    }
}
