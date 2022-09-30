@foreach ($chats as $chat)
<a onclick="loadCht('{{$keyword}}',{{$chat->tourist->id}},0)" class="list-group-item d-flex align-items-center {{$id==$chat->tourist->id ? 'active' : ''}}" style="cursor: pointer;">
    <div class="pr-3">
        <div class="avatar">
            <img src="{{$chat->tourist->image}}" class="rounded-circle" alt="image">
        </div>
    </div>
    <div>
        <h6 class="mb-1">{{$chat->tourist->first_name}} {{$chat->tourist->last_name}}</h6>
        <span class="text-muted">{{$chat->message}}</span>
    </div>
    <div class="text-right ml-auto d-flex flex-column">
        @if($chat->unread>0)
        <span class="badge badge-success badge-pill ml-auto mb-2">{{$chat->unread}}</span>
        @endif
        <span class="small text-muted">{{ date("h:i A",strtotime($chat->created_at)) }}</span>
    </div>
</a>
@endforeach