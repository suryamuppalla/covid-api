<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Http\Request;
use App\Hospital;
use Validator;
use Illuminate\Support\Str;
use DB;
class HospitalController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return $this->sendResponse('Hospital Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // Validate
        $validator = Validator::make($request->all(), [
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

        // $input = $request->all();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
