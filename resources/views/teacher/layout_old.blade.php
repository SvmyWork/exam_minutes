<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ExamDoo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#f5f7fe] text-[#1e1e1e] font-sans">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Hamburger Menu Button (visible on mobile only) -->



        <!-- Sidebar -->
        @include('teacher.sidebar.content')

        <!-- Main Content -->
        @yield('content')
    </div>
    <script src="{{ asset('assests/js/app.js') }}"></script>



</body>

</html>