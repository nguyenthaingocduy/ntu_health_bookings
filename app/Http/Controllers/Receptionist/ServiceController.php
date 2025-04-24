<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::where('status', 'active')->orderBy('name')->paginate(10);
        
        return view('receptionist.services.index', compact('services'));
    }

    /**
     * Display the specified service.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        
        return view('receptionist.services.show', compact('service'));
    }
}
