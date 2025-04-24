<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ProfessionalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfessionalNoteController extends Controller
{
    /**
     * Display a listing of the professional notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = ProfessionalNote::with('customer')
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('technician.notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new professional note.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('full_name')->get();
        
        return view('technician.notes.create', compact('customers'));
    }

    /**
     * Store a newly created professional note in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'skin_condition' => 'required|string|max:50',
            'content' => 'required|string|max:1000',
            'recommendations' => 'nullable|string|max:500',
        ]);
        
        // Kiểm tra xem đã có ghi chú cho khách hàng này chưa
        $existingNote = ProfessionalNote::where('customer_id', $request->customer_id)
            ->where('created_by', Auth::id())
            ->first();
        
        if ($existingNote) {
            // Cập nhật ghi chú hiện có
            $existingNote->skin_condition = $request->skin_condition;
            $existingNote->content = $request->content;
            $existingNote->recommendations = $request->recommendations;
            $existingNote->appointment_id = $request->appointment_id;
            $existingNote->updated_by = Auth::id();
            $existingNote->save();
            
            $message = 'Ghi chú chuyên môn đã được cập nhật thành công.';
        } else {
            // Tạo ghi chú mới
            $note = new ProfessionalNote();
            $note->id = Str::uuid();
            $note->customer_id = $request->customer_id;
            $note->appointment_id = $request->appointment_id;
            $note->skin_condition = $request->skin_condition;
            $note->content = $request->content;
            $note->recommendations = $request->recommendations;
            $note->created_by = Auth::id();
            $note->save();
            
            $message = 'Ghi chú chuyên môn đã được tạo thành công.';
        }
        
        if ($request->has('appointment_id') && $request->appointment_id) {
            return redirect()->route('technician.sessions.show', $request->appointment_id)->with('success', $message);
        }
        
        return redirect()->route('technician.notes.index')->with('success', $message);
    }

    /**
     * Display the specified professional note.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = ProfessionalNote::with('customer')
            ->where('created_by', Auth::id())
            ->findOrFail($id);
        
        return view('technician.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified professional note.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);
        $customers = Customer::orderBy('full_name')->get();
        
        return view('technician.notes.edit', compact('note', 'customers'));
    }

    /**
     * Update the specified professional note in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'skin_condition' => 'required|string|max:50',
            'content' => 'required|string|max:1000',
            'recommendations' => 'nullable|string|max:500',
        ]);
        
        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);
        
        $note->customer_id = $request->customer_id;
        $note->skin_condition = $request->skin_condition;
        $note->content = $request->content;
        $note->recommendations = $request->recommendations;
        $note->updated_by = Auth::id();
        $note->save();
        
        return redirect()->route('technician.notes.index')->with('success', 'Ghi chú chuyên môn đã được cập nhật thành công.');
    }

    /**
     * Remove the specified professional note from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = ProfessionalNote::where('created_by', Auth::id())->findOrFail($id);
        $note->delete();
        
        return redirect()->route('technician.notes.index')->with('success', 'Ghi chú chuyên môn đã được xóa thành công.');
    }
}
