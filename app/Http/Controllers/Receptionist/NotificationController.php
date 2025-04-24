<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('receptionist.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        
        return view('receptionist.notifications.create', compact('customers'));
    }

    /**
     * Store a newly created notification in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:appointment,reminder,promotion,general',
        ]);
        
        $notification = new Notification();
        $notification->id = Str::uuid();
        $notification->customer_id = $request->customer_id;
        $notification->title = $request->title;
        $notification->message = $request->message;
        $notification->type = $request->type;
        $notification->is_read = false;
        $notification->created_by = Auth::id();
        $notification->save();
        
        return redirect()->route('receptionist.notifications.index')
            ->with('success', 'Thông báo đã được gửi thành công.');
    }

    /**
     * Display the specified notification.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::with('customer')->findOrFail($id);
        
        return view('receptionist.notifications.show', compact('notification'));
    }
}
