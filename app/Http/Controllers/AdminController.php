<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Admin;
use App\Models\HotelProfile;
use App\Models\HotelType;
use App\Models\Tourist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Profiler\Profile;

class AdminController extends Controller
{
    function register()
    {
        // var_dump(request()->all());
        $attributes = request()->validate([
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:5|max:20',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $attributes['password'] = bcrypt($attributes['password']);

        $user = Admin::create($attributes);

        auth('admin')->login($user);

        session()->flash('success', 'Account has been created !');

        return redirect("site-admin/");
    }

    function logout()
    {

        auth('admin')->logout();

        return redirect("site-admin/login")->with('success', 'Logged out !');
    }

    function login()
    {
        $attributes = request()->validate([
            'email' => 'required|exists:admins,email',
            'password' => 'required'
        ]);

        $attributes["status"]=true;

        if (auth('admin')->attempt($attributes)) {
            return redirect('site-admin/')->with('success', 'Welcome again');
        }

        throw ValidationException::withMessages([
            'password' => 'Provided credentials not found !'
        ]);
    }

    public function pendingHotels()
    {
        $hotels = HotelProfile::where('approved_by', NULL)->get();

        return view('admin.admin.index', [
            "hotels" => $hotels

        ]);
    }

    function rejectHotel(){
        // $hotel= HotelProfile::where('hotel_type_id',request()->get('id'))->where('hotel_type_id',Auth::user('hotel'))->first();

        // if($hotel){
        //     $hotel->reject();
        //     return"success";
        // }else{
        //     return "error";
        // }
    }

    function approveHotel(){
        $attributes = request()->validate([
            'id' => 'required|numeric'
        ]);

        $hotel = HotelProfile::where('id',$attributes["id"])->first();

        if($hotel){
            $hotel->approved_by=Auth::user('admin')->id;
            $hotel->save();
            return "success";
        }else{
            return "error";
        }
    }
    function view(){
        $attributes = request()->validate([
            'id' => 'required|numeric'
        ]);

        $hotel = HotelProfile::where('id',$attributes["id"])->first();

        if($hotel){
            $hotel->approved_by=Auth::user('admin')->id;
            $hotel->save();
            return "success";
        }else{
            return "error";
        }
    }

    public function show()
    {
        $attributes = request()->validate([
            'id' => 'required|numeric'
        ]);

        $hotel = HotelProfile::where('id',$attributes["id"])->first();

        return view("admin.admin.models.profile",[
            "hotel"=>$hotel,
        ]);
        // return "error";
    }

    function updateName(){
        $user = Auth::user('admin');

        $attributes = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user->update($attributes);

        return redirect("site-admin/account")->with('success','Name updated');
    }

    function updatePassword(){

        $attributes = request()->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user('admin');

        if(Hash::check( $attributes['old_password'],$user->password )){
            $attributes['password'] = bcrypt($attributes['password']);
            $user->password= $attributes['password'];
            $user->update();
            return redirect("site-admin/account")->with('success','Password updated');
        }else{
            return redirect("site-admin/account")->with('error','Old password error');
        }

    }

    public function adminlist()
    {
        $adminlists = Admin::all();
        return view('admin.admin.adminList',[
            "adminlists" => $adminlists,

        ]);


    }

    function deleteAdmin(){
        $admin = Admin::where('id',request()->get('id'))->first();
        if($admin){
            $admin->status=false;
            $admin->save();
            return "success";
        }else{
            return "error";
        }
    }
    function addAdmin(){
        $admin = Admin::where('id',request()->get('id'))->first();
        if($admin){
            $admin->status=true;
            $admin->save();
            return "success";
        }else{
            return "error";
        }
    }

    public function createadmin(){
        $attributes = request()->validate([
            'email' => 'required|email|unique:admins,email',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $attributes['password'] = bcrypt(env('DEFAULT_ADMIN_PASSWORD'));

        $user = Admin::create($attributes);

        session()->flash('success', 'Account has been created !');

        return redirect("site-admin/create-admin");
    }


    public function recoverLink(Request $request){

        $request->validate([
            'email' => 'required|email|exists:admins',
        ]);

        $token = uniqid();

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('emails.pswd-reset', ['token' => 'site-admin/reset/'.$token, 'email'=> $request->email], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification');
            $message->replyTo('contact@manakal.com', 'Manakal contact');
        });

        return redirect('/site-admin/recover')->with('success','We have e-mailed your password reset link!');
    }

    public function updatePasswordRecover(Request $request){

        $request->validate([
            'password' => 'required|string|min:5|max:20|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where(['token' => $request->token])
                            ->first();

        if(!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');

        $user = Admin::where('email', $updatePassword->email)
                    ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')->where(['email'=> $updatePassword->email])->delete();

        return redirect('/site-admin/login')->with('success', 'Your password has been changed!');

    }

    public function touristList(){
        $tourists = Tourist::all();
        return view('admin.admin.touristList',[
            "tourists" => $tourists,
        ]);
    }

    public function hotelList(){
        $hotels = HotelProfile::all();
        return view('admin.admin.hotelList',[
            "hotels" => $hotels,
        ]);
    }

    public function addHotelType(){
        $attributes = request()->validate([
            'name' => 'required',
        ]);

        $hotel = HotelType::create($attributes);

        session()->flash('success', 'Hotel Type was added successfully');

        return redirect("site-admin/add-hotel-type");
    }

    public function touristBookings(){
        $bookings = Booking::all();
        return view('admin.admin.touristBookings',[
            "bookings" => $bookings,
        ]);
    }

    public function touristBookingsChart(){

        $users = DB::table('bookings')
            ->select(
                DB::raw('count(*) as count'),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            )
            ->where('status',1)
            ->groupBy('date')
            ->get();

        $months = DB::table('bookings')
            ->select(
                DB::raw('count(*) as count'),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date")
            )
            ->where('status',1)
            ->groupBy('date')
            ->get();

        $datas = array(0,0,0,0,0,0,0,0,0,0,0,0);



        foreach ($months as $key => $value) {
            $datas[(int)substr($value->date,5,2)-1] = $value->count;
        }

        return view('admin.admin.touristBookingsChart',compact('datas'));

    }

    public function hotelBookings(){
        $bookings = Booking::all();
        return view('admin.admin.hotelBookings',[
            "bookings" => $bookings,
        ]);
    }

    function dashboard(){

        $attributes = request()->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        

            $bookings = Booking::where('start_date','>=',$attributes["start_date"])->where('start_date','<=',$attributes["end_date"])->where('status',1)
                        ->groupBy('start_date')
                        ->selectRaw('sum(bookings.total) AS sale, start_date AS date')
                        ->get()
                        ->toArray();

            $packages = Booking::join('packages','packages.id','=','bookings.package_id')
                        ->join('hotel_profiles','packages.profile_id','=','hotel_profiles.id')
                        ->where('bookings.start_date','>=',$attributes["start_date"])
                        ->where('bookings.start_date','<=',$attributes["end_date"])
                        ->where('bookings.status',1)
                        ->groupBy('packages.profile_id')
                        ->selectRaw('count(bookings.id) AS pkg_count, hotel_profiles.name AS pkg_name')
                        ->get()
                        ->toArray();

            $totalSale = Booking::where('start_date','>=',$attributes["start_date"])->where('start_date','<=',$attributes["end_date"])->where('status',1)
                        ->selectRaw('count(id) AS orders, SUM(total) AS sale')
                        ->get()
                        ->toArray();

            $totalCustomers = Booking::where('start_date','>=',$attributes["start_date"])->where('start_date','<=',$attributes["end_date"])->where('status',1)
                        ->groupBy('bookings.tourist_id')
                        ->selectRaw('count(id) AS orders')
                        ->get()
                        ->toArray();

            return response()->json([
                "avg_sales_dates"=> json_encode(array_column($bookings, 'date')), 
                "avg_sales_datas" => json_encode(array_column($bookings, 'sale')),
                "avg_pkg_counts" => json_encode(array_column($packages, 'pkg_count')),
                "avg_pkg_names" => json_encode(array_column($packages, 'pkg_name')),
                "total_sales" => $totalSale[0]["sale"],
                "total_orders" => $totalSale[0]["orders"],
                "total_customers" => count($totalCustomers)
            ],200);

      
        
    }

}

