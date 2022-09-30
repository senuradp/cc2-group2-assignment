 <!-- Chat header -->
 <div class="chat-header" style="position: relative">
    <div class="d-flex align-items-center">
        <div class="pr-3">
            <div class="avatar avatar-state-warning">
                <img src="/images/hotels/room8.jpg" class="rounded-circle" alt="image">
            </div>
        </div>
        <div>
            <p class="mb-0">{{$profile->name}}</p>
            <div class="m-0 small text-success">{{$profile->address}}</div>
        </div>

        <div class="TravelGo-opt-list" style="right: 10px;">
            <a href="/hotel/{{$profile->id}}" class="single-map-item">
                <i class="fas fa-map-marker-alt"></i>
                <span class="TravelGo-opt-tooltip">On the map</span>
            </a>
            <a href="tel:{{$profile->contacts[0]}}" class="TravelGo-js-favorite">
                <i class="fas fa-phone"></i>
                <span class="TravelGo-opt-tooltip">Call</span>
            </a>
        </div>
    </div>
</div>
<!-- ./ Chat header -->

<!-- Messages -->
<div class="messages" style="max-height: 500px;">
    @foreach ($chats as $chat)
    <div class="message-item {{ $chat->send_by_tourist ? 'me':'' }}">
        <div class="message-item-content">{{  $chat->message }}</div>
        <span class="time small text-muted font-italic">{{ date("Y-m-d h:i A",strtotime($chat->created_at)) }} </span>
    </div>
    @endforeach
    
</div>
<!-- ./ Messages -->

<!-- Chat footer -->
<div class="chat-footer">
    <form class="d-flex">
        <div class="flex-grow-1">
            <input type="text" id="mymsg" class="form-control" placeholder="Write your message">
        </div>
        <div class="chat-footer-buttons d-flex">
            <div class="nav-item border-0 d-none d-lg-inline-block align-self-center"> <a onclick="sendMsg({{$profile->id}})" class=" btn btn-sm btn-success text-white mb-0">Send</a> </div>
        </div>
    </form>
</div>
<!-- ./ Chat footer -->