<?php

namespace App\Http\Controllers;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Hospital;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingsController extends ResponseController
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
            $bookings = Bookings::where('userId', $user->id)->orderBy('created_at', 'DESC')->get();
            foreach ($bookings as $booking) {
                $booking['hospital'] = Hospital::where('id', $booking['hospitalId'])->first()->name;
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
        $hospital = Hospital::where('id', $request->hospitalId)->get()->first();
        if ($hospital) {
            $bookingType = Str::lower($request->bookingType);
            $bookingJSON = json_decode($hospital->$bookingType);
            if ($bookingJSON -> available >= $request -> quantity) {
                $booking = Bookings::create([
                    'userId' => $request->userId,
                    'hospitalId' => $request->hospitalId,
                    'bookingType' => $request->bookingType,
                    'quantity' => $request->quantity,
                    'bookingDate' => $request->bookingDate
                ]);
                if ($booking) {
                    $bookingJSON-> available = $bookingJSON -> available - $request -> quantity;

                    $hospital-> $bookingType = json_encode($bookingJSON);
                    $hospital -> save();

                    $success['data'] =  $booking;
                    $success['message'] = "Booking Created successfully..";
                    return $this->sendResponse($success);
                } else {
                    $error = "Sorry! Booking Creation is not successful.";
                    return $this->sendError($error, 401);
                }
            } else {
                $error = 'Insufficient Quantity Requested, Please try with lesser quantity!!';
                return $this->sendError($error, 500);
            }
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
        $hospital = Hospital::where('id', $request->hospitalId)->get()->first();
        if ($hospital) {
            $bookingType = Str::lower($request->bookingType);
            $bookingJSON = json_decode($hospital->$bookingType);
            if ($bookingJSON -> available >= $request -> quantity) {
                $booking = Bookings::where('id', $id)->get()->first();
                if ($booking) {
                    $bookingJSON-> available = ($bookingJSON -> available + $booking -> quantity) - $request -> quantity;

                    $hospital-> $bookingType = json_encode($bookingJSON);
                    $hospital -> save();

                    $booking->quantity = $request->quantity;
                    $booking->bookingDate = $request->bookingDate;
                    $booking->save();

                    $success['data'] =  $booking;
                    $success['message'] = "Booking Updated successfully..";
                    return $this->sendResponse($success);
                } else {
                    $error = "Sorry! Booking Creation is not successful.";
                    return $this->sendError($error, 401);
                }
            } else {
                $error = 'Insufficient Quantity Requested, Please try with lesser quantity!!';
                return $this->sendError($error, 500);
            }
        } else {
            $error = "Sorry! Booking Creation is not successful.";
            return $this->sendError($error, 401);
        }
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
        $booking = Bookings::where('id', $id)->get()->first();
        if ($booking) {
            $hospital = Hospital::where('id', $booking->hospitalId)->get()->first();
            if ($hospital) {
                $bookingType = Str::lower($booking->bookingType);
                $bookingJSON = json_decode($hospital->$bookingType);

                $bookingJSON-> available = $bookingJSON -> available + $booking -> quantity;
                $hospital-> $bookingType = json_encode($bookingJSON);
                $hospital->save();

                $booking->delete();

                $success['data'] = $booking;
                $success['message'] = "Booking Deleted successfully..";
                return $this->sendResponse($success);
            }
            return $this->sendError(`Unable to find Hospital with ID ` + $booking->hospitalId, 404);
        }
        return $this->sendError(`Unable to Deleted selected Booking Details`, 404);
    }
}
