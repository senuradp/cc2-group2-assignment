<!-- Chat header -->
<div class="chat-header" style="display: block;">
    <div class="d-flex align-items-center">
        <div class="pr-3">
            <div class="avatar">
                <img src="{{$profile->image}}" class="rounded-circle" alt="image" />
            </div>
        </div> 
        <div>
            <p class="mb-0">{{$profile->first_name}} {{$profile->last_name}}</p>
            <div class="m-0 small text-success">{{$profile->email}}</div>
        </div>
        <div class="ml-auto">
            <ul class="nav align-items-center">
                <li class="mr-4 d-inline">
                    <a href="#" title="Start Video Call" data-toggle="tooltip">
                        <i data-feather="video" class="width-18 height-18"></i>
                    </a>
                </li>
                <li class="mr-4 d-inline">
                    <a href="#" title="Start Voice Call" data-toggle="tooltip">
                        <i data-feather="phone-call" class="width-18 height-18"></i>
                    </a>
                </li>
                <li class="d-inline">
                    <a href="#" title="Add to Contact" data-toggle="tooltip">
                        <i data-feather="user-plus" class="width-18 height-18"></i>
                    </a>
                </li>
                <li class="ml-4 mobile-chat-close-btn">
                    <a href="#" class="btn btn-sm btn-danger">
                        <i data-feather="x" class="width-18 height-18"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- ./ Chat header -->

<!-- Messages -->
<div class="messages" style="display: block;">
  @foreach ($chats as $chat)
  <div class="message-item {{ $chat->send_by_tourist ? '':'me' }}">
      <div class="message-item-content">{{  $chat->message }}</div>
      <span class="time small text-muted font-italic">{{ date("Y-m-d h:i A",strtotime($chat->created_at)) }} </span>
  </div>
  @endforeach
</div>
<!-- ./ Messages -->

 <!-- Chat footer -->
 <div class="chat-footer" style="display: block;">
  <form class="d-flex">
      <div class="flex-grow-1">
          <input type="text" id="mymsg" class="form-control" placeholder="Write your message" />
      </div>
      <div class="chat-footer-buttons d-flex">
          <button class="btn btn-primary" type="button" onclick="sendMsg({{$profile->id}})">
              <i data-feather="send" class="width-15 height-15"></i>
          </button>
      </div>
  </form>
</div>
<!-- ./ Chat footer -->
