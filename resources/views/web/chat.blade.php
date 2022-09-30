@extends('web.includes.layout')

@section('title', 'HelaView - Chat')

@section('content')

    <section class="pt80 pb80 booking-section login-area">
        <div class="container">
            <div class="row">
                <!-- Chat sidebar -->
                <div class="col-lg-4 chat-sidebar">

                    <!-- Sidebar search -->
                    <div class="chat-sidebar-header">
                        <div class="d-flex">
                            <div class="pr-3">
                                <div class="avatar">
                                    <img src="{{ Auth::user('web')->image }}" class="rounded-circle" alt="image">
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">{{ Auth::user('web')->first_name . ' ' . Auth::user('web')->last_name }}</p>
                                <p class="m-0 small text-muted">{{ Auth::user('web')->email }}</p>
                            </div>

                        </div>
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control mb-2" placeholder="Chat search">

                            </div>
                        </form>
                    </div>
                    <!-- ./ Sidebar search -->

                    <!-- Chat list -->
                    <div class="chat-sidebar-content" style="max-height: 500px;">
                        <div class="chat-lists">
                            <div class="list-group list-group-flush">
                                
                            </div>
                        </div>
                    </div>
                    <!-- ./ Chat list -->

                </div>
                <!-- ./ Chat sidebar -->

                <!-- Chat content -->
                <div class="col-lg-8 chat-content no-chat-selected">

                    <!-- Chat header -->
                    <div class="chat-header" style="position: relative">
                        <div class="d-flex align-items-center">
                           
                            <div>
                                <p class="mb-0">Select Chat From Left site</p>
                            </div>
                        </div>
                    </div>
                    <!-- ./ Chat header -->

                    <!-- Messages -->
                    <div class="messages" style="max-height: 500px;">
                        <div class="message-item">
                            <div class="message-item-content">Select a chat from list or Search a hotel and click on <i>"chat with the hotel"</i> button inside a package</div>
                        </div>
                    </div>
                    <!-- ./ Messages -->

                    <!-- Chat footer -->
                    <div class="chat-footer">
                        <form class="d-flex">
                            <div class="flex-grow-1">
                                <input type="text" id="mymsg" class="form-control" placeholder="Write your message">
                            </div>
                        </form>
                    </div>
                    <!-- ./ Chat footer -->

                </div>
                <!-- ./ Chat content -->
            </div>
        </div>
    </section>

@endsection


@section('scripts')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $( document ).ready(function() {
            loadChtList('',0,{{ Request::segment(2)!==null ? Request::segment(2) : '0'}});
        });

        var chtid=0;
        function loadChtList(keyword,id,profile){
            $.ajax({
                type: 'POST',
                url: '/chat-list',
                data: {
                    'keyword' : keyword,
                    'id': id,
                    'profile' : profile,
                },
                success: function(data) {
                    // console.log(data);
                    $('.list-group-flush').html(data)
                }
            });
        }

        function loadCht(keyword,id,profile){
            // read msg
            $.ajax({
                type: 'POST',
                url: '/read-msg',
                data: {
                    'id': id,
                },
                success: function(data) {
                   //load list again
                    loadChtList(keyword,id,profile);
                }
            });

            $('#mymsg').val('');

            if(chtid==0){
                chtid=id;
                loadChtLoop();
            }else{
                chtid=id;
            }
           

        }

        function loadChtLoop(){
            //load chat with loop
            setTimeout(function() { 
                $.ajax({
                    type: 'POST',
                    url: '/load-chat',
                    data: {
                        'id': chtid,
                    },
                    success: function(data) {
                        if($('#mymsg').val()==''){
                            $('.chat-content').html(data);
                            $('.messages').scrollTop($('.messages')[0].scrollHeight);
                        }
                        loadChtLoop(chtid);
                    }
                });             
            }, 3000);
            
        }

        function sendMsg(id){
            $.ajax({
                type: 'POST',
                url: '/send-msg',
                data: {
                    'id': id,
                    'msg' : $('#mymsg').val()
                },
                success: function(data) {
                    $('#mymsg').val('');
                    loadChtLoop(id);
                }
            });
        }


    </script>

@endsection
