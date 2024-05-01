@extends('layouts.app')

@section('content')
    <div class="container">


        <div class="flex h-[85vh]">
            <!-- Sidebar -->
            <div class="bg-gray-800 text-white w-1/4">
                <div class="p-4">
                    <h1 class="text-xl font-bold">Contacts</h1>
                    <!-- People Search -->
                    <div class="mt-4">
                        <input type="text" id="search"
                            class="w-full bg-gray-700 text-white rounded-md px-3 py-2 focus:outline-none"
                            placeholder="Search contacts...">
                    </div>
                    <!-- User List -->
                    <ul class="mt-4" id="contacts">
                        @foreach ($users as $user)
                            <li class="py-2 cursor-pointer"
                                onclick="changeRecipient({{ $user->id }}, '{{ $user->name }}')">
                                {{ $user->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Content (Chat) -->
            <div class="flex flex-col flex-1">
                <!-- Chat Header -->
                <div class="bg-gray-200 px-4 py-2">
                    <!-- Display the current recipient -->
                    <h1 id="recipient" class="text-lg font-semibold text-gray-800">Chat with: User 1</h1>
                </div>

                <!-- Chat Messages -->
                <div class="flex-1 px-4 py-2 overflow-y-auto" id="ChatingDiv">
                    <!-- Display messages here -->
                    <div class="flex flex-col space-y-4" id="UserMessages">
                        <h1 class="text-lg font-semibold text-gray-800 text-center">Please select a contact</h1>

                        {{-- <!-- Sender Message -->
                        <div class="self-end bg-blue-500 text-white rounded-lg p-2 max-w-xs">
                            <p>Hello there!</p>
                        </div>
                        <!-- Receiver Message -->
                        <div class="self-start bg-gray-300 text-gray-800 rounded-lg p-2 max-w-xs">
                            <p>Hi! How can I help you?</p>
                        </div> --}}
                        <!-- Add more messages here -->
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="bg-gray-200 px-4 py-2">
                    <form action="" method="post" id="messageForm" class="flex">
                        @csrf
                        <input name="user_id" id="user_id" value="{{ Auth::user()->id }}" class="hidden">
                        <input name="name" id="name" value="{{ Auth::user()->name }}" class="hidden">
                        <input name="receiver_id" id="reciver_id" value="" class="hidden">
                        <div class="flex">
                            <input type="text" name="message" id="YourMessage"
                                class="flex-1 border-gray-300 rounded-full py-2 px-4 focus:outline-none"
                                placeholder="Type your message...">
                            <button
                                class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-full"
                                id="sendMessage">Send</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- JavaScript function to change the recipient -->
        <script>
            setTimeout(() => {
                window.Echo.private('chat.{{ Auth::id() }}')
                    .listen('PrivateMessage', (e) => {
                        var uId = $('#reciver_id').val();
                        if (uId == e.message.userId) {
                            $('#UserMessages').append(`<div class="self-start bg-blue-500 text-white rounded-lg p-2 max-w-xs">
                                              <p>${e.message.message}</p>
                                         </div>`);
                        $("#ChatingDiv").scrollTop($("#ChatingDiv")[0].scrollHeight);

                        }

                        
                    })
            }, 200);

            function changeRecipient(id, name) {
                // Fetch user messages

                $.ajax({
                    url: "{{ route('getUserMessages') }}",
                    type: 'GET',
                    data: {
                        'user_id': id
                    },
                    success: function(data) {
                        // Update the contact list  
                        $('#reciver_id').val(id);
                        $('#recipient').html('Chat with: ' + name);
                        if (data.length > 0) {
                            var htmls = "";
                            data.forEach(element => {

                                if (element.user_id == id) {
                                    htmls += `<div class="self-start bg-blue-500 text-white rounded-lg p-2 max-w-xs">
                                              <p>${element.message}</p>
                                         </div>`
                                } else {
                                    htmls += `<div class="self-end bg-gray-300 text-gray-800 rounded-lg p-2 max-w-xs">
                                              <p>${element.message}</p>
                                         </div>`;
                                }

                            });

                            $('#UserMessages').html(htmls);
                        } else {
                            $('#UserMessages').html(
                                '<h1 class="text-lg font-semibold text-gray-900">No message found</h1>');
                        }
                        $("#ChatingDiv").scrollTop($("#ChatingDiv")[0].scrollHeight);

                    }
                });

                // Update the recipient
            }
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();

                // Search contact from ajax request
                $.ajax({
                    url: "{{ route('search') }}",
                    type: 'GET',
                    data: {
                        'search': value
                    },
                    success: function(data) {
                        // Update the contact list  


                        if (data.length > 0) {
                            console.log(data);

                            // print all users
                            data.forEach(element => {
                                $('#contacts').append(
                                    `<li class="py-2 cursor-pointer" onclick="changeRecipient(${element.id})">${element.name}</li>`
                                );
                            });
                        } else {
                            $('#contacts').html(
                                '<li class="text-lg font-semibold text-gray-100">No user found</li>');
                        }
                    }
                });
            });

            // Send message
            $("#sendMessage").click(function(event) {
                event.preventDefault(); // Prevent the default form submission

                var formData = $("#messageForm").serialize()
                var _token = "{{ csrf_token() }}",

                    formData = formData + "&_token=" + _token;
                $.ajax({
                    url: "{{ route('sendMessage') }}", // Specify your URL here
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        // var message = `<h1 class="p-1 text-right"><span class="font-bold">${response.name} : </span>${response.message} </h1>`;
                        $('#UserMessages').append(` <div class="self-end bg-gray-300 text-gray-800 rounded-lg p-2 max-w-xs">
                                                 <p>${response.message}</p>
                                            </div>`);
                        $('#YourMessage').val('');
                        $("#ChatingDiv").scrollTop($("#ChatingDiv")[0].scrollHeight);



                        // You can perform any further actions here
                    },
                    error: function(xhr, status, error) {
                        console.error("Form submission failed with status: " + xhr.status);
                    }
                });
            });
        </script>

    </div>
@endsection
