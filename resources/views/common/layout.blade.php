<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{$title}}</title>
    <!-- Include Flowbite CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="{{ asset('css/student_login.css') }}" type="text/css"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/student_login.css')}}"> -->
  
</head>


<body class="bg-[#f5f7fe] bg-overlay flex flex-col items-center justify-center font-sans px-4 mt-10">
  <div class="flex items-center space-x-2 mb-8">
    <!-- Logo Image -->
    <img src="{{ asset('assets/images/XcceedAI_logo.png') }}" alt="X Logo" class="w-10 h-10 object-contain">

    <!-- Text Block -->
    <div class="leading-tight">
      <h1 class="text-xl font-bold">XcceedAI</h1>
      <p class="text-xs text-[#40404092]">Today With AI Innovation</p>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow-sm w-full max-w-md p-2 mx-4 mb-1 border-2">
    <h1 class="text-lg font-bold text-center text-blue-700">{{$title}}</h1>
  </div>

  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8 mx-4 border-2">

    <h2 class="text-2xl font-bold mb-6">@yield('title-form', '')</h2>
    
    @yield('content-form')
  </div>
  <div class="p-4 mb-4 text-sm text-red-800 rounded-lg" role="alert">
  <span class="font-medium">@isset($message)
    {{ $message }}
@endisset
</span> 
</div>
  <script src="{{ asset('assets/js/register.js') }}"></script>

</body>
</html>