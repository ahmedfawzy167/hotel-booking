<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->user()->id)->paginate(3);
        if ($bookings) {
            if (request('page') > $bookings->lastPage()) {
                return response()->json([
                    'message' => 'Booking Not Found'
                ], 404);
            }
        }
        return BookingResource::collection($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date_format:Y-m-d H:i:s',
            'check_out_date' => 'required|date_format:Y-m-d H:i:s',
            'hotel_id' => 'required|exists:hotels,id',
        ]);

        $booking = new Booking();
        $booking->check_in_date = $request->check_in_date;
        $booking->check_out_date = $request->check_out_date;
        $booking->user_id = auth()->user()->id;
        $booking->hotel_id = $request->hotel_id;
        $booking->save();

        return response()->json([
            "status" => 'Success',
            "message" => "Booking Created Successfully!",
            "booking" => $booking
        ], 201);
    }


    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $booking = Booking::findOrFail($id);

        // Check if the booking belongs to the authenticated user
        if ($booking->user_id === $user->id) {
            $booking->delete();
            return response()->json([
                "status" => 'Success',
                "message" => "Booking is Deleted"
            ], 204);
        } else {
            return response()->json([
                "message" => "You are not Authorized to Delete This Booking"
            ], 403);
        }
    }
}
