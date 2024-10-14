<?php

namespace App\Repositories;

use App\Models\Hotel;
use App\Models\Image;
use App\Repositories\HotelRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HotelRepository implements HotelRepositoryInterface
{
    public function index()
    {
        $hotels = Hotel::with('images')->get();
        return $hotels;
    }

    public function create()
    {
        return view('hotels.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'description' => 'required|string|max:800',
            'address' => 'required|string|max:200',
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:3000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hotel = new Hotel();
        $hotel->name = $request->name;
        $hotel->description = $request->description;
        $hotel->address = $request->address;
        $hotel->save();

        foreach ($request->file('images') as $imageFile) {
            $ext = $imageFile->getClientOriginalExtension();
            $fileName = Date("Y-m-d-h-i-s") . '.' . $ext;
            $location = "public/";
            $imageFile->storeAs($location, $fileName);

            $image = new Image();
            $image->path = $fileName;
            $image->imageable_id = $hotel->id;
            $image->imageable_type = 'App\Models\Hotel';
            $image->save();
        }

        Session::flash('message', 'Hotel is Created Successfully');
        return redirect(route('hotels.index'));
    }

    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return $hotel;
    }

    public function edit($id)
    {
        $hotel = Hotel::find($id);
        return $hotel;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,50',
            'description' => 'required|string|max:800',
            'address' => 'required|string|max:200',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:3000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hotel = Hotel::find($id);
        $hotel->name = $request->name;
        $hotel->description = $request->description;
        $hotel->address = $request->address;
        $hotel->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $ext = $imageFile->getClientOriginalExtension();
                $fileName = time() . '.' . $ext;
                $location = "public/";
                $imageFile->storeAs($location, $fileName);

                $image = new Image();
                $image->path = $fileName;
                $image->imageable_id = $hotel->id;
                $image->imageable_type = 'App\Models\Hotel';
                $image->save();
            }
        }

        Session::flash('message', 'Hotel is Updated Successfully');
        return redirect(route('hotels.index'));
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        Session::flash('message', 'Hotel is Trashed Successfully');
        return redirect(route('hotels.index'));
    }
}
