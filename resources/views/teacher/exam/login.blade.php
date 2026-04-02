<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GATE exam 2 Test Series</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white font-sans">

    <!-- Header -->
    <div class="bg-gray-200 flex flex-col sm:flex-row justify-between items-center  py-4 sm:py-0 ">
        <!-- Title Info -->
        <div class="text-center sm:text-left md:ml-5">
            <h1 class="text-xl sm:text-2xl font-bold">{{ $data['PaperName'] }}</h1>
            <p class="text-base sm:text-lg mt-1">By {{ $data['TeacherName'] }}</p>
        </div>

        <!-- Profile Info -->
        <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto justify-between sm:justify-end mr-5 md:mr-0">
            <div class="text-center sm:text-left ml-5">
                <p class="text-lg sm:text-xl font-semibold">Shubhamoy Paul</p>
                <p class="text-sm sm:text-base">{{ $data['PaperId'] }}</p>
            </div>
            <img src="https://img.freepik.com/free-vector/smiling-young-man-illustration_1308-173524.jpg?ga=GA1.1.1157756022.1739728206&semt=ais_items_boosted&w=740"
                alt="Profile" class="w-20 h-20 sm:w-24 sm:h-24 object-cover border border-black" />
        </div>
    </div>

    <!-- Login Box -->
    <div class="flex justify-center px-4 mt-12">
        <div class="w-full max-w-md bg-white border border-gray-300 p-6 sm:p-8 shadow-md rounded-lg">
            <h2 class="text-lg sm:text-xl font-bold mb-6 text-center">Login (Demo)</h2>

            <label class="block text-sm font-medium mb-1">Username</label>
            <input type="text" value="223.181.183.245" readonly
                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-700 mb-4">

            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" value="**********" readonly
                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-700 mb-6">

<button
  class="w-full bg-blue-600 text-white py-2 font-bold rounded hover:bg-blue-700 transition"
  onclick="openFullscreenPage()"
>
  LOGIN
</button>

<script>
  $testId = "{{ $test_id }}";
  function openFullscreenPage() {
    const routeUrl = "{{ route('exam.instructions', ['test_id' => $test_id]) }}";

    const win = window.open('', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');

    if (!win) {
      alert('Popup blocked. Please allow popups for this site.');
      return;
    }

    win.document.write(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Launching Exam</title>
        <style>
          body {
            margin: 0;
            background-color: #000;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: sans-serif;
            font-size: 22px;
          }
        </style>
      </head>
      <body>
        <div>Preparing your exam environment...</div>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const requestFS = document.documentElement.requestFullscreen || 
                              document.documentElement.webkitRequestFullscreen || 
                              document.documentElement.mozRequestFullScreen || 
                              document.documentElement.msRequestFullscreen;

            if (requestFS) {
              requestFS.call(document.documentElement)
                .catch(() => {
                  console.warn('Fullscreen failed');
                });
            }

            setTimeout(() => {
              window.location.href = '${routeUrl}';
            }, 1000);
          });
        <\/script>
      </body>
      </html>
    `);

    win.document.close();
  }

  localStorage.setItem("examTimer", "{{ $Timeinit }}");
  const data = @json($data);
  localStorage.setItem('examMetaData', JSON.stringify(data));
  console.log(data);
  localStorage.setItem('testId', data['PaperId']);
  localStorage.setItem('teacherId', data['TeacherId']);
</script>



            <p class="text-center text-orange-500 mt-4 font-semibold">Click Login To proceed</p>
        </div>
    </div>

</body>

</html>