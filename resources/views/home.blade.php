@extends('layouts.app')

@section('content')
<div class="container">

    @forelse($notifications as $notification)
    <div class="alert alert-success" role="alert">
        [{{ $notification->data['name'] }} ha beeen added on {{ $notification->created_at }}]  
        @if (isset($notification->data['email']))
        with "({{ $notification->data['email'] }})". 
        @endif
        
        <a href="{{$notification->id}}/delete" class="float-right mark-as-read" data-id="{{ $notification->id }}" onclick="myFunction()">
            Mark as read
        </a>
    </div>

    @if($loop->last)
        <a href="delete-all" id="mark-all">
            @can('delete_all')
            Mark all as read
            @endcan
        </a>
    @endif
@empty
    There are no new notifications
@endforelse


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Add new Product') }}</div>

                <form action="check">
                    @csrf
                    <div class="form-group">
                        <label>Product name</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Cost</label>
                        <input type="number" class="form-control" name="price">
                      </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>

            </div>
        </div>
    </div>
</div>
</div>







    <div class="row justify-content-center">
        <div class="col-md-8">
            <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center>
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('send.notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Body</label>
                            <textarea class="form-control" name="body"></textarea>
                          </div>
                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyCTm0sEnebB3rdsQ-ccfUXiCcuay527E5s",
        authDomain: "unicom-1430d.firebaseapp.com",
        projectId: "unicom-1430d",
        storageBucket: "unicom-1430d.appspot.com",
        messagingSenderId: "764014970103",
        appId: "1:764014970103:web:c6b0a8d32d324bc398bff2",
        measurementId: "${config.measurementId}"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });
            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
     }
    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });
</script>




@if(auth()->user())
admin user
    <script>
    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('markNotification') }}", {
            method: 'POST',
            data: {
                _token,
                id
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                $(this).parents('div.alert').remove();
            });
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.alert').remove();
            })
        });
    });
    </script>
@endif
@endsection