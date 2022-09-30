@extends('admin.hotel.includes.dash')
@section('title', 'Home')

@section('content')
    <!-- Content -->
    <div class="content">
        <div class="row no-gutters chat-block">
            <!-- Chat sidebar -->
            <div class="col-lg-4 chat-sidebar">
                <!-- Sidebar search -->
                <div class="chat-sidebar-header">
                    <div class="d-flex">
                        <div class="pr-3">
                            <div class="avatar">
                                <img src="/images/icon.png" class="rounded-circle"
                                    alt="image" />
                            </div>
                        </div>
                        <div>
                            <p class="mb-0">{{ Auth::user('hotel')->first_name }} {{ Auth::user('hotel')->last_name }}</p>
                            <p class="m-0 small text-muted">{{ Auth::user('hotel')->email }}</p>
                        </div>
                        <div class="ml-auto">
                            <div class="dropdown">
                                <a href="#" data-toggle="dropdown">
                                    <i class="width-18 height-18" data-toggle="tooltip" title="Settings"
                                        data-feather="settings"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="profile.html">Profile</a>
                                    <a class="dropdown-item" href="user-edit.html">Edit</a>
                                    <a class="dropdown-item" data-sidebar-target="#settings" href="#">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Chat search" />
                            <div class="input-group-append">
                                <button class="btn" type="button">
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ./ Sidebar search -->

                <!-- Chat list -->
                <div class="chat-sidebar-content">
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
                <!-- Chat empty block -->
                <div class="chat-empty">
                    <img src="/admin/assets/media/svg/empty_chat.svg" alt="..." />
                    <p class="lead text-muted mt-3">Choose a chat</p>

                </div>
                <!-- ./Chat empty block -->

                <!-- Chat footer -->
                <div class="chat-footer" style="display: block;">
                    <form class="d-flex">
                        <div class="flex-grow-1">
                            <input type="text" id="mymsg" class="form-control" placeholder="Write your message" />
                        </div>
                    </form>
                </div>
                <!-- ./ Chat footer -->

            </div>
            <!-- ./ Chat content -->
        </div>
    </div>
    <!-- ./ Content -->

@endsection

@section('scripts')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(document).ready(function() {
            loadChtList('', 0, 0);
        });

        var chtid = 0;

        function loadChtList(keyword, id, profile) {
            $.ajax({
                type: 'POST',
                url: '/hotel-portal/chat-list',
                data: {
                    'keyword': keyword,
                    'id': id,
                    'profile': profile,
                },
                success: function(data) {
                    // console.log(data);
                    $('.list-group-flush').html(data)
                }
            });
        }

        function loadCht(keyword, id, profile) {
            // read msg
            $.ajax({
                type: 'POST',
                url: '/hotel-portal/read-msg',
                data: {
                    'id': id,
                },
                success: function(data) {
                    //load list again
                    loadChtList(keyword, id, profile);
                }
            });

            $('#mymsg').val('');

            if (chtid == 0) {
                chtid = id;
                loadChtLoop();
            } else {
                chtid = id;
            }


        }

        function loadChtLoop() {
            //load chat with loop
            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: '/hotel-portal/load-chat',
                    data: {
                        'id': chtid,
                    },
                    success: function(data) {
                        if ($('#mymsg').val() == '') {
                            $('.chat-content').html(data);
                            $('.messages').scrollTop($('.messages')[0].scrollHeight);
                        }
                        loadChtLoop(chtid);
                    }
                });
            }, 3000);

        }

        function sendMsg(id) {
            $.ajax({
                type: 'POST',
                url: '/hotel-portal/send-msg',
                data: {
                    'id': id,
                    'msg': $('#mymsg').val()
                },
                success: function(data) {
                    $('#mymsg').val('');
                    loadChtLoop(id);
                }
            });
        }
    </script>
@endsection
