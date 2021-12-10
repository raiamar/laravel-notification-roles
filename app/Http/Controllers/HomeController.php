<?php

namespace App\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Events\ProductEvent;
// use App\Models\Notification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $notifications = auth()->user()->unreadNotifications;

        return view('home', compact('notifications'));
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }


    public function check(Request $request){
        $product = Product::create($request->all());

        // $admins = User::where('id', 1)->get();

        // Notification::send($admins, new ProductNotification($product));
        event(new ProductEvent($product));

        return redirect('/home');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
      

        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAAseLXRPc:APA91bE2UQvlNhFgsMfGg9o16oyXECQZNiLDzyi9ePSIC_cLWYvbA6V8RZE6uqrK0MCX4JK1uhyVuEU1JiKO7H1jSk5zlWR_sQy5mhSuwGLetLRygC1_f25vjGW3tTF2acEepcBGP_Dk';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
            "title" => $request->title,
            "body" => $request->body,
            "content_available" => true,
            "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }



    public function store(Request $request){
        $data = Products::create($request->all());
        return view('home');
    }


    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }

    public function delete(Request $request, $id){
        $data = \App\Models\Notification::find($id);
        $data->delete();
        return redirect('/home');
    }

    public function delete_all(){
        $this->authorize('delete_all');
        $data = \App\Models\Notification::orderBy('id');
        $data->delete();
        return redirect('/home');
    }


    public function show_product(){
        $product = Product::all();
        $user = Auth::user();

        // return $user;
        return view('products', compact('product', 'user'));
    }

    public function delete_product($id){
        // $this->authorize('delete', $product);
        $product = Product::find($id);
        $product->delete();
        return redirect('show-product');
    }
}
