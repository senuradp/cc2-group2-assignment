@if($newChat)
<a onclick="loadCht('{{$keyword}}',{{$newChat->id}},{{$newChat->id}})" class="list-group-item d-flex align-items-center  {{$id==$newChat->id ? 'active' : ''}}">
    <div class="pr-3">
        <div class="avatar avatar-state-danger">
            <img src="/images/hotels/room8.jpg" class="rounded-circle" alt="image">
        </div>
    </div>
    <div>
        <h6 class="mb-1">{{$newChat->name}}</h6>
        <span class="text-muted">New Chat</span>
    </div>
    <div class="text-right ml-auto d-flex flex-column">
        <span class="small text-muted">{{ date("h:i A") }}</span>
    </div>
</a>
@endif
@foreach ($chats as $chat)
@if(!($newChat && $chat->hotel->id==$newChat->id))
<a onclick="loadCht('{{$keyword}}',{{$chat->hotel->id}},{{ $newChat!==null ? $newChat->id : '0'}})" class="list-group-item d-flex align-items-center {{$id==$chat->hotel->id ? 'active' : ''}}">
    <div class="pr-3">
        <div class="avatar avatar-state-danger">
            <img src="/images/hotels/room8.jpg" class="rounded-circle" alt="image">
        </div>
    </div>
    <div>
        <h6 class="mb-1">{{$chat->hotel->name}}</h6>
        <span class="text-muted">{{$chat->message}}</span>
    </div>
    <div class="text-right ml-auto d-flex flex-column">
        @if($chat->unread>0)
        <span class="badge badge-success badge-pill ml-auto mb-2">{{$chat->unread}}</span>
        @endif
        <span class="small text-muted">{{ date("h:i A",strtotime($chat->created_at)) }}</span>
    </div>
</a>
@endif
@endforeach