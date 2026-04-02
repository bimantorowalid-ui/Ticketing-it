<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket berdasarkan Role.
     */
    public function index()
{
    // Mengambil data user yang sedang login
    $user = Auth::user();

    // LOGIKA PEMISAHAN ROLE
    if ($user->role == 'karyawan') {
        // KARYAWAN: Hanya melihat tiket buatannya sendiri
        $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        
        // Kirim ke view dashboard TANPA variabel $itStaffs
        return view('dashboard', compact('tickets'));
    } 

    // SPV & IT STAFF: Melihat SEMUA tiket yang masuk
    $tickets = Ticket::with(['user', 'assignedUser'])->latest()->get();
    
    // Ambil daftar user yang rolenya 'it_staff' untuk pilihan delegasi
    $itStaffs = User::where('role', 'it_staff')->get();

    // Kirim ke view dashboard DENGAN variabel $itStaffs
    return view('dashboard', compact('tickets', 'itStaffs'));
}
    public function create()
    {
        return view('tickets.create');
    }

    // Di dalam TicketController.php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'divisi' => 'required',
        'no_wa' => 'required|numeric', // validasi nomor agar hanya angka
        'priority' => 'required'
    ]);

    Ticket::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'divisi' => $request->divisi,
        'no_wa' => $request->no_wa,
        'priority' => $request->priority,
        'status' => 'open',
    ]);

    return redirect()->route('dashboard')->with('success', 'Tiket berhasil dikirim!');
}
    /**
     * Fitur SPV: Mendelegasikan tiket ke Staff IT
     */
    public function assign(Request $request, Ticket $ticket)
    {
        if (Auth::user()->role !== 'spv') {
            return back()->with('error', 'Hanya SPV yang dapat mendelegasikan tiket.');
        }

        $request->validate([
            'it_staff_id' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->it_staff_id,
            'status' => 'on-progress' // Otomatis berubah saat didelegasikan
        ]);

        return back()->with('success', 'Tiket berhasil didelegasikan ke Staff IT.');
    }

    /**
     * Fitur Staff IT: Mengupdate status pengerjaan
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:on-progress,pending,resolved',
        ]);

        // Proteksi: Hanya staff yang ditugaskan (atau SPV) yang bisa update
        if (Auth::user()->role == 'it_staff' && $ticket->assigned_to != Auth::id()) {
            return back()->with('error', 'Anda tidak ditugaskan untuk tiket ini.');
        }

        $ticket->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status tiket berhasil diperbarui.');
    }
}