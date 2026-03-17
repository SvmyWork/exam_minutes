<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exam Completed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-10 max-w-lg text-center fade-in">
        <div class="text-green-500 mb-4">
            <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Your Exam is Complete</h1>
        <p class="text-lg text-gray-600 mb-6">
            Thank you for attempting this exam. <br />
            We wish you the very best for your future!
        </p>

        <a onclick="closeWindow()"
            class="inline-block bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-full shadow-lg hover:scale-105 transform transition-all duration-300">
            Go to Home
        </a>
    </div>

    <script src="{{ asset('assets/js/exam/closeWindow.js')  }}"></script>
</body>

</html>