<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ExamDoo - Question Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script> -->
    <link rel="stylesheet" href="{{ asset('assets/css/latex.css')}}" type="text/css" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">

    <!-- KaTeX core -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>

    <!-- KaTeX auto-render -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"
        onload="initRender();"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<style>
    .progress-circle {
        stroke-dasharray: 565;
        stroke-dashoffset: 565;
    }
    .option-input {
        width: 18px;
        height: 18px;
    }

</style>
<body class="bg-gray-50 font-sans overflow-x-hidden w-full p-0 m-0">
    <div class="flex h-screen w-full">
        <!-- Left Sidebar -->
    @include('teacher.sidebar.content')

        <!-- Main Content -->
        <div class="flex-1 min-w-0 p-6 overflow-x-auto relative">
            <div class="flex items-center justify-between text-gray-600 font-medium">
                <!-- Left Side -->
                <span class="cursor-pointer"><i class="fa-solid fa-angle-left"></i> Back</span>

                <!-- Right Side Hamburger Button (hidden on mobile) -->
                <button id="rightOpenBtn" class="text-xl hidden md:block">
                    <i class="fas fa-bars"></i>
                </button>
            </div>


            <div class="flex items-center mb-6 mt-4">
                <button id="hamburgerBtn" class="text-xl md:hidden focus:outline-none mr-4">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-xl font-semibold">Test Name</h1>

            </div>

            <!-- Question Number Buttons -->
            <div class="overflow-x-auto whitespace-nowrap mt-4 px-2">
                <div class="inline-flex space-x-3 min-w-full">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        1</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        2</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        3</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        4</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        5</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        6</div>
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white">7</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        8</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        9</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        10</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        11</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        12</div>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 cursor-pointer hover:bg-gray-100">
                        +</div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row mt-6 gap-6">
                <!-- Question Section -->
                <div class="w-full lg:w-1/2 bg-white p-4 rounded-lg shadow">
                    <!-- Top section: Question title and dropdown aligned horizontally -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-lg font-medium">Question 7</div>
                        <select id="formatSelect" class="border border-gray-300 rounded p-1 text-sm">
                            <option value="Text">Text</option>
                            <option value="MathLyx">MathLyx</option>
                            <option value="latex">Latex</option>
                        </select>
                    </div>
                    @include('teacher.toolbar.content')
                    <textarea class="w-full h-32 border border-gray-300 rounded p-2" placeholder=""></textarea>


                    <div class="my-2 ">
                        <div id="gallery" class="flex flex-wrap gap-4 justify-start w-full"></div>
                    </div>



                    <div class="my-2 ">
                        <!-- Hidden file input -->
                        <input type="file" id="imageInput" accept="image/*" multiple class="hidden" />

                        <!-- Styled label acting as the button -->
                        <label for="imageInput"
                            class="border border-dotted border-gray-300 rounded px-4 py-2 flex items-center gap-2 w-full justify-center cursor-pointer hover:bg-gray-100 transition">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                            Upload File
                        </label>
                    </div>

                    <div class="mb-2 font-medium">Options</div>

                    <div class="flex items-center justify-between mb-2">
                        <div class="text-lg font-medium"></div>
                        <select class="border border-gray-300 rounded p-1 text-sm">
                            <option>Text</option>
                            <option value="MathLyx">MathLyx</option>
                            <option value="latex">Latex</option>
                        </select>
                    </div>
                    <div class="flex gap-2 mb-4 justify-center">
                        <p class="">A.</p>
                        <input type="text" placeholder="A" class="w-full mb-2 border border-gray-300 rounded p-2" />
                        <button class=" rounded w-10 "><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-lg font-medium"></div>
                        <select id="formatSelect" class="border border-gray-300 rounded p-1 text-sm">
                            <option value="Text">Text</option>
                            <option value="MathLyx">MathLyx</option>
                            <option value="latex">Latex</option>
                        </select>
                    </div>
                    @include('teacher.toolbar.content')
                    <div class="flex gap-2 mb-4 justify-center">
                        <p class="">B.</p>
                        <input type="text" placeholder="B" class="w-full mb-2 border border-gray-300 rounded p-2" />
                        <button class=" rounded w-10 "><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <button class="px-3 py-2 border border-gray-300 rounded w-full">-</button>
                        <button class="px-3 py-2 border border-gray-300 rounded w-full">+</button>
                    </div>

                    <div class="mb-2 font-medium">Answer</div>
                    <div class="flex gap-4 mb-4">
                        <label><input type="radio" name="answer" /> 1</label>
                        <label><input type="radio" name="answer" /> 2</label>
                        <label><input type="radio" name="answer" /> 3</label>
                        <label><input type="radio" name="answer" /> 4</label>
                    </div>

                </div>

                <!-- Preview Section -->
                <div class="w-full lg:w-1/2 bg-white p-4 rounded-lg shadow">
                    <div class="font-medium mb-2 text-center w-full text-2xl">
                        Preview
                    </div>

                    <div class="border border-gray-200 p-4 rounded">
                        <div class="font-medium mb-2">Question 7</div>
                        <p class="mb-4">If the blood groups of mother and father are AB and O, respectively, what are
                            the blood groups possible for their child?</p>
                        <div class="mb-2">Options</div>
                        <ul class="list-none ml-4 space-y-1">
                            <li>A &nbsp;&nbsp;&nbsp; AB</li>
                            <li>B &nbsp;&nbsp;&nbsp; O</li>
                            <li>C &nbsp;&nbsp;&nbsp; AB, A, B, or O</li>
                        </ul>
                    </div>
                    <div class="flex mt-4 gap-2 flex-row lg:flex-row justify-between items-center">
                        <button class="px-4 py-2 border border-gray-300 rounded">Previous</button>
                        <button class="px-4 py-2 border border-gray-300 rounded">Clear</button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Save/Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Only visible on desktop) -->
        <div id="rightSidebar"
            class="hidden w-48 bg-white border-l fixed top-0 right-0 bottom-0 z-40 ml-4 rounded-lg shadow-lg">
            <div class="flex justify-between items-center p-4 font-semibold text-lg">
                Question Panel
                <!-- ✅ Close Button -->
                <button id="rightCloseBtn" class="text-lg focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-3 gap-2 p-4">
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    1</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    2</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    3</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    4</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    5</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    6</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    7</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    8</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    9</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    10</div>
                <div
                    class="flex justify-center items-center w-8 h-8 border border-gray-300 hover:bg-gray-200 text-lg rounded-full cursor-pointer">
                    +</div>
            </div>
        </div>




    </div>

    <!-- Hamburger Menu for Mobile -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const hamburgerBtn = document.getElementById("hamburgerBtn");
        const closeBtn = document.getElementById("closeBtn");

        hamburgerBtn?.addEventListener("click", () => {
            sidebar.classList.remove("hidden");
        });

        closeBtn?.addEventListener("click", () => {
            sidebar.classList.add("hidden");
        });

        // ✅ Right Sidebar Toggle
        const rightSidebar = document.getElementById("rightSidebar");
        const rightCloseBtn = document.getElementById("rightCloseBtn");
        const rightOpenBtn = document.getElementById("rightOpenBtn");

        rightCloseBtn?.addEventListener("click", () => {
            rightSidebar.classList.add("hidden");
            rightOpenBtn.classList.remove("hidden");
        });

        rightOpenBtn?.addEventListener("click", () => {
            rightSidebar.classList.remove("hidden");
            rightOpenBtn.classList.add("hidden");
        });


        const select = document.getElementById('formatSelect');
        const toolbar = document.getElementById('toolbar');

        select.addEventListener('change', function () {
            if (this.value === 'MathLyx') {
                toolbar.style.display = 'block'; // Show the toolbar
            } else {
                toolbar.style.display = 'none'; // Hide the toolbar
            }
        });


    </script>

<script src="{{ asset('assets/js/qpage.js') }}"></script>
</body>

</html>