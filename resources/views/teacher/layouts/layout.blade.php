<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ExamDoo</title>
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Include Flowbite CDN -->
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('head')
    
</head>

<body class="bg-[#f5f7fe] text-[#1e1e1e] font-sans">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Hamburger Menu Button (visible on mobile only) -->



        <!-- Sidebar -->
        @include('teacher.sidebar.content')

        <!-- Main Content -->
        @yield('content')
    </div>
    <!-- Main app.js -->
    <script src="{{ asset('assets/js/teacher/app.js') }}"></script>

    <!-- Dropdown toggle script -->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('dropdownButton');
            const dropdownMenu = document.getElementById('dropdownMenu');

            if (dropdownButton && dropdownMenu) {
                dropdownButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script> -->


    
</body>

</html>