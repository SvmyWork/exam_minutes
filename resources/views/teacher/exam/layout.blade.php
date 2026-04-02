<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'exam')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>
    	<link type="text/css" rel="stylesheet" href="{{ asset('assets/js/exam/scientific_calculator/calclayout.css')}}">
	<script type="text/javascript" src="{{ asset('assets/js/exam/scientific_calculator/jquery-1.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/exam/scientific_calculator/oscZenoedited.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/exam/scientific_calculator/jquery-ui.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Configure MathJax for LaTeX rendering -->
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" async></script>
	<script type="text/javascript">
		$(function () {
			$("#keyPad").draggable({
				start: function () {

					$(this).css({ height: "auto", width: "463px" });
				},
				stop: function () {

					$(this).css({ height: "auto", width: "463px" });
				}
			});

			$(".calc_min").live('click', function () {
				$('#mainContentArea').toggle();
				$('#keyPad_Help').hide();
				$('#keyPad_Helpback').hide();
				$(".help_back").hide();
				$('#keyPad').addClass("reduceWidth");
				$('#helptopDiv span').addClass("reduceHeader");
				//		$('#calc_min').toggleClass("reduceHeader");
				$(this).removeClass("calc_min").addClass('calc_max');
			});
			$(".calc_max").live('click', function () {
				$(this).removeClass("calc_max").addClass('calc_min');
				$('#mainContentArea').toggle();
				if ($("#helpContent").css('display') == 'none') {
					$('#keyPad_Help').show();
				}
				else {
					$('#keyPad_Helpback').show();
				}
				$('#keyPad_Help').show();
				$('#keyPad').removeClass("reduceWidth");
				$('#helptopDiv span').removeClass("reduceHeader");
			});
		});
		$('#closeButton').click(function () {
			$('#loadCalc').hide();
		});
		/** new help changes **/
		$('#keyPad_Help').live('click', function () {
			$(this).hide();
			$('#keyPad_Helpback').show();
			$('.text_container').hide();
			$('.left_sec').hide();
			$('#keyPad_UserInput1').hide();
			$('#helpContent').show();

		});

		$('#keyPad_Helpback').live('click', function () {
			$(this).hide();
			$('#keyPad_Help').show();
			$('.text_container').show();
			$('.left_sec').show();
			$('#keyPad_UserInput1').show();
			$('#helpContent').hide();

		});

		/** new help changes **/
	</script>
	<style>
        #showCalcBtn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            z-index: 10000;
        }

        /* Optional: semi-transparent background behind calculator */
        #calcOverlay {
            display: none;
            position: fixed;
            top: 20%;
            left: 100%;
            transform: translateX(-50%);
            width: 100%;
            height: 100%;
            z-index: 9998;
        }

        /* Mobile view adjustments */
        @media (max-width: 768px) {
            #calcOverlay {
            
            top: 20%;
            left: 50%;
            
        }
            
        }
        

    </style>
</head>

<body class="bg-white font-sans ">
    <!-- Header -->
    @include('teacher.exam.header')
    @include('teacher.exam.scientific_calculator')
    <!-- Top Bar -->


    <!-- Main Content -->
    <div class="flex h-[calc(100%-90px-10px)] w-full mb-4">
        <div class="flex-1 overflow-auto w-full max-w-full ">
            <div class="flex items-center bg-[#f3f3f3] px-6 py-1.5 text-xl border-b">
                <div class="bg-blue-600 text-white px-2 py-1 rounded">{{ $test_name }}</div>
                
                <div class="ml-auto cursor-pointer" id="calbtn" title="Calculator"><i class="fa-solid fa-calculator fa-xl"
                        style="color: #f07f5f;"></i>
                </div>
                        <div class="ml-auto text-xl font-semibold block md:hidden cursor-pointer" id="rightPanel-btn"><i
                        class="fa-solid fa-bars cursor-pointer"></i>
                </div>
                
            </div>
            <div class="flex items-center bg-white px-6 py-1.5 text-xl overflow-y-auto miniwidth">
                <div class="text-lg font-semibold overflow-y-auto">Section</div>
                <div class="ml-auto text-xl font-semibold overflow-y-auto flex flex-row">Time Left : <p class="ml-1" id="timer"></p></div>
            </div>
            <div class="flex items-center bg-[#f3f3f3] px-1 py-1.5 text-xl miniwidth overflow-y-auto">
            <div class="flex space-x-1 ml-4" id="section-buttons">

            </div>
            </div>
            @yield('content')
        </div>

        <!-- Right Panel -->
        @include('teacher.exam.sidebar')

        @include('teacher.exam.footer')
    </div>
    <!-- Bottom Bar -->



    </div>

    <script src="{{ asset('assets/js/exam/index.js') }}"></script>
    <script>
        $(function () {
            // Show calculator on button click
            $('#calbtn').on('click', function () {
                $('#keyPad').fadeIn();
                $('#calcOverlay').fadeIn();
            });

            // Hide calculator on close
            $('#closeButton').on('click', function () {
                $('#keyPad').fadeOut();
                $('#calcOverlay').fadeOut();
            });

            // Enable dragging
            $("#keyPad").draggable({
                containment: "window",
                handle: "#helptopDiv"
            });
        });
    </script>

    



<script>
    let interval;

    function startTimer(durationInSeconds) {
        const timerElement = document.getElementById("timer");
        let totalSeconds = durationInSeconds;

        // Clear previous interval if exists
        clearInterval(interval);

        interval = setInterval(() => {
            let minutes = Math.floor(totalSeconds / 60);
            let seconds = totalSeconds % 60;

            const formattedTime = 
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            timerElement.textContent = formattedTime;
            localStorage.setItem("examTimer", formattedTime);

            // Change color if under 1 minute
            if (totalSeconds < 60) {
                timerElement.style.color = "red";
            } else {
                timerElement.style.color = ""; // reset if restarting
            }

            totalSeconds--;

            if (totalSeconds < 0) {
                clearInterval(interval);
                timerElement.textContent = "00:00";
                localStorage.setItem("examTimer", "00:00");
                endExam();
            }
        }, 1000);
    }

    const savedTime = localStorage.getItem("examTimer");
    const [minStr, secStr] = savedTime.split(":");
    const seconds = parseInt(minStr) * 60 + parseInt(secStr);
    startTimer(seconds);




let countSeconds = 0; // 10 minutes in seconds
const countUpElement = document.getElementById("countup");

let countUpInterval = startCountUp(); // Start the timer initially

function startCountUp() {
    return setInterval(() => {
        let minutes = Math.floor(countSeconds / 60);
        let seconds = countSeconds % 60;

        countUpElement.textContent =
            `${String(minutes).padStart(2, '0')}:` +
            `${String(seconds).padStart(2, '0')}`;

        countSeconds++;
    }, 1000);
}

const submitButton = document.getElementById("submitAnswer");

function resetCountUpTimer(timeString) {
    clearInterval(countUpInterval);    // Stop the current timer
    const [minutes, seconds] = timeString.split(":").map(Number);
    const totalSecondsValue = (minutes * 60) + seconds;
    countSeconds = totalSecondsValue;                // Reset to given time
    countUpElement.textContent = timeString; // Reset display
    countUpInterval = startCountUp();  // Start a new timer
}

const studentExamEndRoute = "{{ route('exam.end') }}";
</script>

<script src="{{ asset('assets/js/exam/localstorage.js')  }}"></script>
<script src="{{ asset('assets/js/exam/onpageload.js')}}"></script>
<script src="{{ asset('assets/js/exam/pageProtection.js') }}"></script>
<script src="{{ asset('assets/js/exam/endExam.js') }}"></script>
    
</body>

</html>