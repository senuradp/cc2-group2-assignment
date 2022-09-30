<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\HotelProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function touristChatList(){

        $attributes = request()->validate([
            'keyword' => 'nullable',
            'id' => 'required',
            'profile' => 'required',
        ]);

        $user = Auth::user('web');

        $chats = Chat::whereHas('tourist',function (Builder $query) use($user){
            $query->where('id',  $user->id);
        });

        if($attributes["keyword"]!=""){
            $chats = $chats->whereHas('hotel',function (Builder $query) use($attributes){
                $query->where('name', 'like','%'.$attributes["keyword"].'%');
            });
        }

        $chats = $chats->groupBy('hotel_id')->get(array(DB::raw('SUM(IF(send_by_tourist=0,unread,NULL)) as unread'),'hotel_id','message','created_at'));

        $newChat = NULL;
        if($attributes["profile"]>0){
            // new chat
            $newChat = HotelProfile::where('id',$attributes["profile"])->first();
        }

        return view("web.chat.list",[
            "chats" => $chats,
            "newChat" => $newChat,
            "keyword" => $attributes["keyword"],
            "id" => $attributes["id"],
        ]);
    }

    public function touristRead(){

        $attributes = request()->validate([
            'id' => 'required',
        ]);

        $user = Auth::user('web');

        DB::table('chats')->where('send_by_tourist', false)->where('unread', true)->where('tourist_id', $user->id)->where('hotel_id', $attributes["id"])->update(array('unread' => false));  

        return "1";
    }

    public function touristChat(){

        $attributes = request()->validate([
            'id' => 'required',
        ]);

        $user = Auth::user('web');

        $chats = Chat::whereHas('tourist',function (Builder $query) use($user){
            $query->where('id', $user->id);
        })->whereHas('hotel',function (Builder $query) use($attributes){
            $query->where('id', $attributes["id"]);
        })->get();

        
        if($chats->first()){
            $profile = $chats->first()->hotel;
        }else{
            $profile = HotelProfile::where('id',$attributes["id"])->first();
        }

        return view("web.chat.chat",[
            "chats" => $chats,
            "profile" => $profile
        ]);

    }

    public function touristMsg(){

        $attributes = request()->validate([
            'id' => 'required',
            'msg' => 'required',
        ]);

        $user = Auth::user('web');

        $newChat = new Chat();
        $newChat->message = $attributes["msg"];
        $newChat->send_by_tourist = true;
        $newChat->tourist_id = $user->id;
        $newChat->hotel_id = $attributes["id"];

        return $newChat->save();

    }

    public function hotelChatList(){

        $attributes = request()->validate([
            'keyword' => 'nullable',
            'id' => 'required',
            'profile' => 'required',
        ]);

        $user = Auth::user('hotel');

        $chats = Chat::whereHas('hotel',function (Builder $query) use($user){
            $query->where('id',  $user->hotel->id);
        });

        if($attributes["keyword"]!=""){
            $chats = $chats->whereHas('tourists',function (Builder $query) use($attributes){
                $query->where('first_name', 'like','%'.$attributes["keyword"].'%')->orWhere('last_name', 'like','%'.$attributes["keyword"].'%');
            });
        }

        $chats = $chats->groupBy('tourist_id')->get(array(DB::raw('SUM(IF(send_by_tourist=1,unread,NULL)) as unread'),'tourist_id','message','created_at'));


        return view("admin.hotel.chat.list",[
            "chats" => $chats,
            "keyword" => $attributes["keyword"],
            "id" => $attributes["id"],
        ]);
    }

    public function hottelRead(){
        $attributes = request()->validate([
            'id' => 'required',
        ]);

        $user = Auth::user('hotel');

        DB::table('chats')->where('send_by_tourist', true)->where('unread', true)->where('hotel_id', $user->hotel->id)->where('tourist_id', $attributes["id"])->update(array('unread' => false));  

        return "1";
    }

    public function hotelChat(){

        $attributes = request()->validate([
            'id' => 'required',
        ]);

        $user = Auth::user('hotel');

        $chats = Chat::whereHas('tourist',function (Builder $query) use($attributes){
            $query->where('id', $attributes["id"]);
        })->whereHas('hotel',function (Builder $query) use($user){
            $query->where('id', $user->hotel->id);
        })->get();
        
        $profile = $chats->first()->tourist;

        return view("admin.hotel.chat.chat",[
            "chats" => $chats,
            "profile" => $profile
        ]);

    }

    public function hotelMsg(){

        $attributes = request()->validate([
            'id' => 'required',
            'msg' => 'required',
        ]);

        $user = Auth::user('hotel');

        $newChat = new Chat();
        $newChat->message = $attributes["msg"];
        $newChat->send_by_tourist = false;
        $newChat->tourist_id = $attributes["id"];
        $newChat->hotel_id = $user->hotel->id;

        return $newChat->save();
    }
}
