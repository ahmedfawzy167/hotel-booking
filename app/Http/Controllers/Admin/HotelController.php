<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\HotelRepositoryInterface;
use App\Models\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HotelController extends Controller
{
    protected $hotelRepository;

    public function __construct(HotelRepositoryInterface $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function index()
    {
        $hotels = $this->hotelRepository->index();
        return view('hotels.index', compact('hotels'));
    }

    public function create()
    {
        return $this->hotelRepository->create();
    }

    public function store(Request $request)
    {
        return $this->hotelRepository->store($request);
    }

    public function show($id)
    {
        $hotel = $this->hotelRepository->show($id);
        return view('hotels.show', compact('hotel'));
    }

    public function edit($id)
    {
        $hotel = $this->hotelRepository->edit($id);
        return view('hotels.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
        return $this->hotelRepository->update($request, $id);
    }
    public function destroy($id)
    {
        return $this->hotelRepository->destroy($id);
    }

    public function trashed()
    {
        $trashedHotels = Hotel::with('images')->onlyTrashed()->get();
        return view('hotels.trash', compact('trashedHotels'));
    }

    public function restore($id)
    {
        $hotel = Hotel::withTrashed()->findOrFail($id);
        $hotel->restore();

        Session::flash('message', 'Hotel is Restored Successfully');
        return redirect(route('hotels.index'))->withInput();
    }

    public function delete($id)
    {
        $hotel = Hotel::withTrashed()->findOrFail($id);
        $hotel->forceDelete();

        Session::flash('message', 'Hotel is Permanently Deleted Successfully');
        return redirect(route('hotels.index'))->withInput();
    }
}
