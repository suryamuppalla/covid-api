<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Http\Request;
use App\Booking;
use App\Hospital;
use Validator;
use Illuminate\Support\Facades\Auth;

class BookingController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            // $bookings = Booking::orderBy('created_at', 'desc')->get();
            $bookings = Booking::where('userId', $user->id)->get();
            foreach ($bookings as $booking) {
                $booking['hospital'] = Hospital::where('id', $booking['hospitalId'])->first();
            }
            $success['data'] = $bookings;
            $success['message'] = 'Bookings List';
            return $this->sendResponse($success);
        } else {
            $error = "Sorry! Current User Not Found!!";
            return $this->sendError($error, 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = Booking::create([
            'userId' => $request->userId,
            'hospitalId' => $request->hospitalId,
            'bookingType' => $request->bookingType,
            'quantity' => $request->quantity,
            'bookingDate' => $request->bookingDate
        ]);
        if ($booking) {
            $success['data'] =  $booking;
            $success['message'] = "Booking Created successfully..";
            return $this->sendResponse($success);
        } else {
            $error = "Sorry! Booking Creation is not successful.";
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
