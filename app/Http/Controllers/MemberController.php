<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\TrainingLocation;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $achievements = \App\Models\Achievement::orderBy('year', 'desc')->get();
        $myAchievements = \App\Models\Achievement::where('winner_name', 'like', '%' . Auth::user()->name . '%')
                                                 ->orderBy('year', 'desc')
                                                 ->get();
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('dashboard.member', compact('attendances', 'achievements', 'myAchievements', 'leaveRequests'));
    }

    public function absenPage()
    {
        $location = TrainingLocation::first();
        if (!$location) {
            $location = TrainingLocation::create([
                'name' => 'Kedai Ibu Dina',
                'latitude' => -6.3491520, 
                'longitude' => 106.7651687, 
                'radius_meter' => 1000
            ]);
        }
        return view('dashboard.absen', compact('location'));
    }

    public function storeAbsen(Request $request)
    {
        $request->validate([
            'photo' => 'required|string', // base64 encoded
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_id' => 'required|exists:training_locations,id'
        ]);

        $image_parts = explode(";base64,", $request->photo);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'attendance/' . uniqid() . '.png';

        Storage::disk('public')->put($fileName, $image_base64);

        Attendance::create([
            'user_id' => Auth::id(),
            'training_location_id' => $request->location_id,
            'photo_path' => $fileName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'present',
        ]);

        return redirect()->route('dashboard')->with('success_absen', 'Absensi berhasil disimpan!');
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sakit,izin',
            'reason' => 'required|string|max:1000',
            'date' => 'required|date',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('leave_proofs', 'public');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'date' => $request->date,
            'proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success_izin', 'Pengajuan izin berhasil dikirim! Menunggu persetujuan pelatih.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|string', // base64 encoded image from cropper
        ]);

        $user = Auth::user();

        $image_parts = explode(";base64,", $request->avatar);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'avatars/' . uniqid() . '.png';

        if ($user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        Storage::disk('public')->put($fileName, $image_base64);

        $user->avatar_url = $fileName;
        $user->save();

        return response()->json(['success' => true, 'url' => asset('storage/' . $fileName)]);
    }
}
