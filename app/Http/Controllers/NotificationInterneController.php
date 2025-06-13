<?php

namespace App\Http\Controllers;

use App\Models\NotificationInterne;
use Illuminate\Http\Request;

class NotificationInterneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('notifications.index', [
            'notifications' => NotificationInterne::with('utilisateur')->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NotificationInterne $notificationInterne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotificationInterne $notificationInterne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotificationInterne $notificationInterne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificationInterne $notificationInterne)
    {
        $notificationInterne->delete();
        return redirect()->route('notifications.index');
    }

    public function markAsRead(NotificationInterne $notificationInterne)
    {
        $notificationInterne->update(['est_lu' => true]);
        return redirect()->back();
    }
}
