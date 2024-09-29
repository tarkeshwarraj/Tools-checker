
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Check</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome CDN for the copy icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="px-3 sm:px-[5vw] md:px-[7vw] lg:px-[9vw] max-h-full overflow-hidden nav">
        {{-- navbar section --}}
        <nav class="flex justify-between sm:py-5 border-b border-black sm:my-2">
            <div>
                <img src="" alt="logo_icon">
            </div>
            <div>
                <ul class="flex gap-4">
                    <li class="transition duration-300 hover:scale-105" ><a href="{{route('smtp-check')}}">SMTP Check</a></li>
                    <li class="transition duration-300 hover:scale-105"><a href="{{route('send-mail')}}">Send Email</a></li>
                    <li class="transition duration-300 hover:scale-105">Email Filter</li>
                    <li class="transition duration-300 hover:scale-105"><a href="{{route('comment')}}">Help & Comment</a></li>
                    <li class="transition duration-300 hover:scale-105">Donate</li>
                </ul>
            </div>
        </nav>