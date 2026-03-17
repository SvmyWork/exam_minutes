<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamLy - Student Dashboard</title>
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/student/dashboard.css') }}" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/student_login.css')}}"> -->
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-header">
            <div class="profile-section">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                    <h3>Ankan Chakraborty</h3>
                    <p>Student ID: STU001</p>
                    <span class="profile-badge">Premium</span>
                </div>
            </div>
            <button class="close-sidebar" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Exams</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-trophy"></i>
                <span>Achievements</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>

    <!-- Desktop Header -->
    <header class="desktop-header">
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>ExamLy</span>
            </div>
            <nav class="desktop-nav">
                <a href="#" class="nav-link active">Dashboard</a>
                <a href="#" class="nav-link">Exams</a>
                <a href="#" class="nav-link">Courses</a>
                <a href="#" class="nav-link">Analytics</a>
            </nav>
            <div class="header-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="user-profile">
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="username">{{ ucwords($name) }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="hamburger" id="hamburgerBtn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>ExamLy</span>
        </div>
        <button class="notification-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section - Full Width -->
        <section class="welcome-section full-width">
            <div class="welcome-content">
                <h1>Hi, Welcome {{ ucwords($name) }}! <span class="wave">👋</span></h1>
                <p>Ready to ace your exams? Let's check your progress.</p>
                <div class="streak-info">
                    <div class="streak-item">
                        <span class="label">Current Streak</span>
                        <span class="value">7 days</span>
                    </div>
                    <div class="streak-item">
                        <span class="label">Total Score</span>
                        <span class="value">2,847</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="dashboard-container">
            <!-- Left Column -->
            <div class="left-column">

                <!-- Exam Overview -->
                <section class="exam-overview">
                    <h2>Exam Overview</h2>
                    <div class="overview-grid">
                        <div class="overview-card completed">
                            <div class="card-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-content">
                                <div class="card-number" id="completedCount">23</div>
                                <div class="card-label">Completed</div>
                                <div class="card-subtitle">Exams finished successfully</div>
                                <div class="card-stat">
                                    <span class="stat-label">Average Score</span>
                                    <span class="stat-value">87%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overview-card upcoming">
                            <div class="card-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="card-content">
                                <div class="card-number" id="upcomingCount">8</div>
                                <div class="card-label">Upcoming</div>
                                <div class="card-subtitle">Scheduled for this month</div>
                                <div class="card-stat">
                                    <span class="stat-label">Preparation Progress</span>
                                    <span class="stat-value">68%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overview-card due-soon">
                            <div class="card-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="card-content">
                                <div class="card-number" id="dueSoonCount">5</div>
                                <div class="card-label">Due Soon</div>
                                <div class="card-subtitle">Requires immediate attention</div>
                                <div class="card-stat">
                                    <span class="stat-label">Priority Level</span>
                                    <span class="stat-value high">High</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overview-card expired">
                            <div class="card-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="card-content">
                                <div class="card-number" id="expiredCount">2</div>
                                <div class="card-label">Expired</div>
                                <div class="card-subtitle">Missed deadlines</div>
                                <div class="card-stat">
                                    <span class="stat-label">Recovery Options</span>
                                    <span class="stat-value">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Calendar Section -->
                <section class="calendar-section">
                    <div class="section-header">
                        <h2>Your Calendar</h2>
                        <div class="calendar-controls">
                            <button class="calendar-nav" id="prevMonth">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="current-month" id="currentMonth">December 2024</span>
                            <button class="calendar-nav" id="nextMonth">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="calendar-container">
                        <div class="calendar" id="calendar">
                            <!-- Calendar will be generated by JavaScript -->
                        </div>
                        <div class="calendar-legend">
                            <div class="legend-item">
                                <span class="legend-dot upcoming"></span>
                                <span>Upcoming Exam</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot completed"></span>
                                <span>Completed</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot due-soon"></span>
                                <span>Due Soon</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot overdue"></span>
                                <span>Overdue</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Progress Overview -->
                <section class="progress-overview">
                    <h2>Progress Overview</h2>
                    <div class="progress-circle">
                        <div class="circle-progress">
                            <svg id="CircleSvgPath" viewBox="0 0 140 140">
                                <circle class="progress-bg" cx="70" cy="70" r="60"></circle>
                                <circle class="progress-fill" cx="70" cy="70" r="60"></circle>
                            </svg>
                            <div class="progress-text">
                                <span class="progress-percentage"><!-- 70%--></span> 
                                <span class="progress-label">Overall</span>
                            </div>
                        </div>
                    </div>
                    <div class="subject-progress">
                        <div class="progress-item">
                            <span class="subject-name">Mathematics</span>
                            <div class="progress-bar">
                                <div class="progress-fill mathematics" style="width: 85%"></div>
                            </div>
                            <span class="progress-value">85%</span>
                        </div>
                        <div class="progress-item">
                            <span class="subject-name">Physics</span>
                            <div class="progress-bar">
                                <div class="progress-fill physics" style="width: 72%"></div>
                            </div>
                            <span class="progress-value">72%</span>
                        </div>
                        <div class="progress-item">
                            <span class="subject-name">Chemistry</span>
                            <div class="progress-bar">
                                <div class="progress-fill chemistry" style="width: 68%"></div>
                            </div>
                            <span class="progress-value">68%</span>
                        </div>
                    </div>
                </section>

                <!-- Recent Activity -->
                <section class="recent-activity">
                    <div class="section-header">
                        <h2>Recent Activity</h2>
                        <a href="#" class="view-all">View all activities</a>
                    </div>
                    <div class="activity-list">
                        <!-- Populate by Javascript -->
                        <!-- <div class="activity-item">
                            <div class="activity-icon completed">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Completed Mathematics Mock Test #5</h4>
                                <p>Score: 87% • 2 hours ago</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon upcoming">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Physics Quiz Reminder</h4>
                                <p>Due tomorrow at 3:00 PM</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon achievement">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Achievement unlocked!</h4>
                                <p>7-day streak milestone • Yesterday</p>
                            </div>
                        </div> -->
                    </div>
                </section>

                <!-- Quick Actions -->
                <section class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <button class="action-btn primary" onclick="window.location.href='../student/exam/start?question_id=qstn-Uabc123'">
                            <i class="fas fa-play"></i>
                            Start Practice Test
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-chart-bar"></i>
                            View Results
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-calendar-plus"></i>
                            Schedule Exam
                        </button>
                    </div>
                </section>
            </div>
        </div>

        <!--  Live Exams - Full Width Section -->
        <section class="live-exams full-width" style="margin-top: 20px;">
            <div class="section-header">
                <h2>Live Exams</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="exams-list">
                @foreach($tests as $test)
                <div class="exam-item live">
                    <div class="exam-date">
                        <span class="date-day">20</span>
                        <span class="date-month">Dec</span>
                    </div>
                    <div class="exam-content">
                        <h3>{{ $test->test_name }}</h3>
                        <p class="exam-time">9:00 AM - 12:00 PM</p>
                        <p class="exam-location">By Surajit Sarkar</p>
                    </div>
                    <div class="exam-status">
                        <span class="status-badge live">Live</span>
                    </div>
                    <div>
                        <button class="action-btn primary exam-start" onclick="window.location.href='../student/exam/start?test_id={{$test->test_id}}'">
                            Start Now
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </section>


        <!-- Enroll Exams - Full Width Section -->
        <section class="upcoming-exams full-width">
            <div class="section-header">
                <h2>Exam Enrollment</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="exams-list">
                <div class="enroll-item">   
                    <div class="exam-detail">
                        <div class="exam-date">
                            <span class="date-day">25</span>
                            <span class="date-month">Dec</span>
                        </div>
                        <div class="exam-content">
                            <h3>Physics Final Exam</h3>
                            <p class="exam-time">10:00 AM - 1:00 PM</p>
                            <p class="exam-location">By Tulsi Das Mukherjee</p>
                        </div>
                        <div class="exam-status">
                            <span class="status-badge upcoming">2 days</span>
                        </div>
                    </div>
                    <button class="action-btn primary exam-enroll">
                        Enroll Now
                    </button>
                </div>
                
                <div class="enroll-item">   
                    <div class="exam-detail">
                        <div class="exam-date">
                            <span class="date-day">28</span>
                            <span class="date-month">Dec</span>
                        </div>
                        <div class="exam-content">
                            <h3>Mathematics Mock Test</h3>
                            <p class="exam-time">2:00 PM - 5:00 PM</p>
                            <p class="exam-location">By Surajit Sarkar</p>
                        </div>
                        <div class="exam-status">
                            <span class="status-badge upcoming">5 days</span>
                        </div>
                    </div>
                    <button class="action-btn primary exam-enroll">
                        Enroll Now
                    </button>
                </div>
                
                <div class="enroll-item">   
                    <div class="exam-detail">
                        <div class="exam-date">
                            <span class="date-day">02</span>
                            <span class="date-month">Jan</span>
                        </div>
                        <div class="exam-content">
                            <h3>Chemistry Quiz</h3>
                            <p class="exam-time">11:00 AM - 12:30 PM</p>
                            <p class="exam-location">By Avijit Sengupta</p>
                        </div>
                        <div class="exam-status">
                            <span class="status-badge upcoming">10 days</span>
                        </div>
                    </div>
                    <button class="action-btn primary exam-enroll">
                        Enroll Now
                    </button>
                </div>
            </div>
            <div class="empty-state" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>No Enrolled Exams</h3>
                <p>You're all caught up! Check back later for new exams.</p>
                <!-- <button class="action-btn secondary">Browse Practice Tests</button> -->
            </div>
        </section>

        <!-- Upcoming Exams - Full Width Section -->
        <section class="upcoming-exams full-width">
            <div class="section-header">
                <h2>Upcoming Exams</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="exams-list">
                <div class="exam-item">
                    <div class="exam-date">
                        <span class="date-day">25</span>
                        <span class="date-month">Dec</span>
                    </div>
                    <div class="exam-content">
                        <h3>Physics Final Exam</h3>
                        <p class="exam-time">10:00 AM - 1:00 PM</p>
                        <p class="exam-location">By Tulsi Das Mukherjee</p>
                    </div>
                    <div class="exam-status">
                        <span class="status-badge upcoming">2 days</span>
                    </div>
                </div>
                
                <div class="exam-item">
                    <div class="exam-date">
                        <span class="date-day">28</span>
                        <span class="date-month">Dec</span>
                    </div>
                    <div class="exam-content">
                        <h3>Mathematics Mock Test</h3>
                        <p class="exam-time">2:00 PM - 5:00 PM</p>
                        <p class="exam-location">By Surajit Sarkar</p>
                    </div>
                    <div class="exam-status">
                        <span class="status-badge upcoming">5 days</span>
                    </div>
                </div>
                
                <div class="exam-item">
                    <div class="exam-date">
                        <span class="date-day">02</span>
                        <span class="date-month">Jan</span>
                    </div>
                    <div class="exam-content">
                        <h3>Chemistry Quiz</h3>
                        <p class="exam-time">11:00 AM - 12:30 PM</p>
                        <p class="exam-location">By Avijit Sengupta</p>
                    </div>
                    <div class="exam-status">
                        <span class="status-badge upcoming">10 days</span>
                    </div>
                </div>
            </div>
            <div class="empty-state" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>No Upcoming Exams</h3>
                <p>You're all caught up! Check back later for new exams.</p>
                <!-- <button class="action-btn secondary">Browse Practice Tests</button> -->
            </div>
        </section>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="#" class="bottom-nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Exams</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <i class="fas fa-book"></i>
            <span>Courses</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <i class="fas fa-chart-bar"></i>
            <span>Analytics</span>
        </a>
        <a href="#" class="bottom-nav-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- <script src="js/calendar.js"></script>
    <script src="js/dashboard.js"></script> -->
    <script src="{{ asset('assets/js/student/calendar.js') }}"></script>
    <script src="{{ asset('assets/js/student/dashboard.js') }}"></script>


</body>
</html>
