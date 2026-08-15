<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Achievement;
use App\Models\Gallery;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelatihController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Pending leave requests for approval
        $pendingLeaves = LeaveRequest::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Processed leave requests (approved/rejected) this month
        $processedLeaves = LeaveRequest::whereIn('status', ['approved', 'rejected'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Build attendance recap per member for current month
        $members = User::where('role', '!=', 'pelatih')->get();

        // Count training days (Mon-Sat) in current month up to today
        $today = Carbon::now()->endOfDay();
        $endDate = $endOfMonth->lt($today) ? $endOfMonth : $today;
        $trainingDays = 0;
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endDate)) {
            if ($currentDay->dayOfWeekIso <= 6) {
                $trainingDays++;
            }
            $currentDay->addDay();
        }

        $attendanceRecap = [];
        foreach ($members as $member) {
            $hadir = Attendance::where('user_id', $member->id)
                ->whereBetween('attendance_date', [$startOfMonth, $endDate])
                ->count();

            $izin = LeaveRequest::where('user_id', $member->id)
                ->where('status', 'approved')
                ->whereBetween('date', [$startOfMonth, $endDate])
                ->count();

            $alfa = max(0, $trainingDays - $hadir - $izin);

            $attendanceRecap[] = (object)[
                'user' => $member,
                'hadir' => $hadir,
                'izin' => $izin,
                'alfa' => $alfa,
                'total_days' => $trainingDays,
            ];
        }

        return view('dashboard.pelatih', compact('pendingLeaves', 'processedLeaves', 'attendanceRecap', 'trainingDays'));
    }

    public function approveLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return back()->with('success_leave', 'Izin dari ' . $leave->user->name . ' telah disetujui.');
    }

    public function rejectLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return back()->with('success_leave', 'Izin dari ' . $leave->user->name . ' telah ditolak.');
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255'
        ]);

        $uploadedCount = 0;
        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('galleries', 'public');

            Gallery::create([
                'image_path' => $imagePath,
                'caption' => $request->caption
            ]);
            $uploadedCount++;
        }

        return back()->with('success_gallery', "$uploadedCount foto berhasil ditambahkan ke galeri!");
    }

    public function storeNews(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'icon' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePaths = null;
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('news', 'public');
            }
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'date' => $request->date,
            'icon' => $request->icon ?? '📰',
            'image_path' => $imagePaths,
        ]);

        return back()->with('success_news', 'Berita berhasil ditambahkan');
    }

    public function storeAchievement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'winner_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'year' => 'required|string|max:4',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePaths = null;
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('achievements', 'public');
            }
        }

        Achievement::create([
            'title' => $request->title,
            'winner_name' => $request->winner_name,
            'category' => $request->category,
            'location' => $request->location,
            'year' => $request->year,
            'description' => $request->description,
            'image_path' => $imagePaths,
        ]);

        return back()->with('success_achievement', 'Prestasi berhasil ditambahkan');
    }
}
