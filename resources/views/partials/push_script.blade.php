<script src="{{asset('assets/global/js/firebase/firebase-8.3.2.js')}}"></script>

<script nonce="{{ csp_nonce() }}">
    "use strict";   
    
    var permission = null;
    var authenticated = '{{ auth()->user() ? true : false }}';
    var pushNotify = @json($general->push_notify);
    var firebaseConfig = @json($general->firebase_config);

    function pushNotifyAction(){ 
        permission = Notification.permission;

        if(!('Notification' in window)){
            notify('info', 'Push notifications not available in your browser. Try Chromium.')
        }
        else if(permission === 'denied' || permission == 'default'){ //Notice for users dashboard
            $('.notice').append(`
                <div class="col-lg-12">
                    <div class="custom--card mb-4">
                        <div class="card-header justify-content-between d-flex flex-wrap notice_notify">
                            <h5 class="alert-heading">@lang('Please Allow / Reset Browser Notification') <i class='las la-bell text--danger'></i></h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 small">@lang('If you want to get push notification then you have to allow notification from your browser')</p>
                        </div>
                    </div> 
                </div>
            `);
        }
    }

    //If enable push notification from admin panel
    if(pushNotify == 1){
        pushNotifyAction();
    }

    //When users allow browser notification
    if(permission != 'denied' && firebaseConfig){ 
   
        //Firebase 
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        navigator.serviceWorker.register("{{ asset('assets/global/js/firebase/firebase-messaging-sw.js') }}")
        
        .then((registration) => {
            messaging.useServiceWorker(registration);
            
            function initFirebaseMessagingRegistration() {
                messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function (token){   
                    $.ajax({ 
                        url: '{{ route("add.device.token") }}',
                        type: 'POST',
                        data: {
                            token: token,
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(response){
                            // console.log(response);
                        },
                        error: function (err) {
                            // console.log('User Chat Token Error'+ err);
                        },
                    });
                }).catch(function (error){
                    // console.log(error);
                });
            }

            messaging.onMessage(function (payload){ 
                const title = payload.notification.title;
                const options = {
                    body: payload.notification.body,
                    icon: payload.notification.icon, 
                    click_action:payload.notification.click_action,
                    vibrate: [200, 100, 200]
                };
                new Notification(title, options);
            });

            //For authenticated users
            if(authenticated){
                initFirebaseMessagingRegistration();
            }

        });

    }
</script>