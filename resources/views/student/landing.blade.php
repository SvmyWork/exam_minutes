<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Series</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10">
    <div class="w-full max-w-5xl px-4">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Available Test Series</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer"
                onclick="window.location.href='../student/exam/start?question_id=qstn-Uabc123'">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Unlock</h2>
                <p class="text-gray-600 text-lg">
                    This series includes full-length mock tests designed to help you prepare thoroughly for your exams.
                    Sharpen your skills with realistic practice.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer"
                onclick="window.location.href='../student/exam/start?question_id=qstn-Ldef456'">
                <h2 class="text-2xl font-semibold text-red-600 mb-3">Lock</h2>
                <p class="text-gray-600 text-lg">
                    Advanced level practice tests focusing on high-weightage topics, exam patterns, and time management
                    strategies for top-tier preparation.
                </p>
            </div>
        </div>
    </div>
</body>

</html>

</html>