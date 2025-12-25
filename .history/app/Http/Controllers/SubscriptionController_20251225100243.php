<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('pages.subscription');
    }

    public function pay(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $planId = $request->plan_id;

        // 1. Төлбөрийн системтэй холбогдох (QPay, SocialPay, Stripe)
        // Энд API дуудаж төлбөр амжилттай эсэхийг шалгана.
        // Жишээ нь: if (!PaymentGateway::check($request->transaction_id)) { return back()->with('error', 'Payment failed'); }

        // 2. Төлбөр амжилттай болсны дараа subscription үүсгэх
        
        // Хугацааг тооцоолох (Жишээ нь: Plan 3 бол 1 жил, бусад нь 1 сар)
        $durationInDays = ($planId == 3) ? 365 : 30; 

        UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($durationInDays),
            'status' => 'active'
        ]);

        return redirect()->route('subscription')->with('success', 'Амжилттай! Таны эрх идэвхжлээ.');
    }
}
