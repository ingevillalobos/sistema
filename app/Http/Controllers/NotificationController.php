<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function get(){
        //return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');
        foreach($unreadNotifications as $notifications){
            if($fechaActual != $notifications->created_at->toDateString()){
                $notifications->markAsRead();
            }
        }
        return Auth::user()->unreadNotifications;
    }
}
