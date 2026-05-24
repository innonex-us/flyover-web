<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelRoomController extends Controller
{
    public function index(Hotel $hotel)
    {
        $rooms = $hotel->rooms()->latest()->paginate(15);
        return view('admin.hotels.rooms.index', compact('hotel', 'rooms'));
    }

    public function create(Hotel $hotel)
    {
        return view('admin.hotels.rooms.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'room_type'      => 'required|string|max:100',
            'price_per_night'=> 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'is_active'      => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'room_type', 'price_per_night', 'capacity', 'description']);
        $data['hotel_id']  = $hotel->id;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels/rooms', 'public');
        }

        $hotel->rooms()->create($data);

        return redirect()->route('admin.hotels.rooms.index', $hotel)->with('success', 'Room created successfully.');
    }

    public function edit(Hotel $hotel, HotelRoom $room)
    {
        return view('admin.hotels.rooms.edit', compact('hotel', 'room'));
    }

    public function update(Request $request, Hotel $hotel, HotelRoom $room)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'room_type'      => 'required|string|max:100',
            'price_per_night'=> 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'is_active'      => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'room_type', 'price_per_night', 'capacity', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('hotels/rooms', 'public');
        }

        $room->update($data);

        return redirect()->route('admin.hotels.rooms.index', $hotel)->with('success', 'Room updated successfully.');
    }

    public function destroy(Hotel $hotel, HotelRoom $room)
    {
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }
        $room->delete();
        return redirect()->route('admin.hotels.rooms.index', $hotel)->with('success', 'Room deleted successfully.');
    }
}
