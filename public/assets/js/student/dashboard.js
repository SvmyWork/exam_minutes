// Dashboard functionality
class Dashboard {
    constructor() {
        this.examData = {
            completed: 23,
            upcoming: 8,
            dueSoon: 5,
            expired: 2
        };
        
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.animateStats();
        this.setupNotifications();
        this.updateDateTime();
        
        // Update stats every 30 seconds
        setInterval(() => this.updateStats(), 30000);
    }

    setupMobileMenu() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const closeSidebar = document.getElementById('closeSidebar');

        // Open sidebar
        hamburgerBtn?.addEventListener('click', () => {
            mobileSidebar.classList.add('active');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close sidebar
        const closeSidebarHandler = () => {
            mobileSidebar.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        };

        closeSidebar?.addEventListener('click', closeSidebarHandler);
        mobileOverlay?.addEventListener('click', closeSidebarHandler);

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileSidebar.classList.contains('active')) {
                closeSidebarHandler();
            }
        });
    }

    animateStats() {
        const counters = [
            { element: document.getElementById('completedCount'), target: this.examData.completed },
            { element: document.getElementById('upcomingCount'), target: this.examData.upcoming },
            { element: document.getElementById('dueSoonCount'), target: this.examData.dueSoon },
            { element: document.getElementById('expiredCount'), target: this.examData.expired }
        ];

        counters.forEach(counter => {
            if (counter.element) {
                this.animateCounter(counter.element, 0, counter.target, 1000);
            }
        });
    }

    animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        const range = end - start;

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            
            const current = Math.floor(start + (range * easeOutQuart));
            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                element.textContent = end;
            }
        };

        requestAnimationFrame(step);
    }

    updateStats() {
        // Simulate real-time updates (in a real app, this would fetch from an API)
        const variations = [-1, 0, 1];
        
        Object.keys(this.examData).forEach(key => {
            const variation = variations[Math.floor(Math.random() * variations.length)];
            const newValue = Math.max(0, this.examData[key] + variation);
            
            if (newValue !== this.examData[key]) {
                this.examData[key] = newValue;
                const element = document.getElementById(`${key}Count`);
                if (element) {
                    this.animateCounter(element, parseInt(element.textContent), newValue, 500);
                }
            }
        });
    }

    setupNotifications() {
        const notificationBtns = document.querySelectorAll('.notification-btn');
        
        notificationBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.showNotifications();
            });
        });
    }

    showNotifications() {
        // Create notification popup
        const notifications = [
            { title: 'Physics Mock Test', message: 'Starts in 2 hours', type: 'upcoming' },
            { title: 'Math Assignment', message: 'Due in 4 hours', type: 'due-soon' },
            { title: 'Chemistry Quiz', message: 'Results available', type: 'completed' }
        ];

        // Simple alert for now (in a real app, you'd create a proper notification UI)
        let notificationText = 'Recent Notifications:\n\n';
        notifications.forEach((notif, index) => {
            notificationText += `${index + 1}. ${notif.title}\n   ${notif.message}\n\n`;
        });

        alert(notificationText);
    }

    updateDateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Update any time displays if they exist
        const timeElements = document.querySelectorAll('.current-time');
        timeElements.forEach(element => {
            element.textContent = timeString;
        });

        // Update every minute
        setTimeout(() => this.updateDateTime(), 60000);
    }

    // Method to handle navigation clicks
    handleNavigation(page) {
        // In a real app, this would handle routing
        console.log(`Navigating to: ${page}`);
        
        // Update active states
        document.querySelectorAll('.nav-link, .nav-item, .bottom-nav-item').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to clicked item (this would be more sophisticated in a real app)
        event.target.closest('a').classList.add('active');
    }
}

// Activity feed functionality
class ActivityFeed {
    constructor() {
        this.activities = [
            {
                title: 'Mathematics Quiz 1',
                description: 'Completed with 85% score',
                time: '2 hours ago',
                type: 'completed',
                icon: 'check'
            },
            {
                title: 'Physics Mock Test',
                description: 'Scheduled for tomorrow',
                time: 'Due in 18 hours',
                type: 'upcoming',
                icon: 'calendar'
            },
            // {
            //     title: 'Chemistry Assignment',
            //     description: 'Submit before deadline',
            //     time: 'Due in 6 hours',
            //     type: 'due-soon',
            //     icon: 'clock'
            // },
            {
                title: 'Achievement unlocked!',
                description: 'Submit before deadline',
                time: 'Yesterday',
                type: 'achievement',
                icon: 'trophy'
            }
        ];
        
        this.renderActivities();
    }

    renderActivities() {
        const activityList = document.querySelector('.activity-list');
        if (!activityList) return;

        // Clear existing activities
        activityList.innerHTML = '';

        this.activities.forEach((activity, index) => {
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            activityItem.style.animationDelay = `${index * 0.1}s`;
            
            activityItem.innerHTML = `
                <div class="activity-icon ${activity.type}">
                    <i class="fas fa-${activity.icon}"></i>
                </div>
                <div class="activity-content">
                    <h4>${activity.title}</h4>
                    <p>${activity.description}</p>
                    <span class="activity-time">${activity.time}</span>
                </div>
            `;
            
            activityList.appendChild(activityItem);
        });
    }

    addActivity(activity) {
        this.activities.unshift(activity);
        
        // Keep only the last 10 activities
        if (this.activities.length > 10) {
            this.activities = this.activities.slice(0, 10);
        }
        
        this.renderActivities();
    }
}

// Progress tracking
class ProgressTracker {
    constructor() {
        this.progressData = {
            totalExams: 25,
            completedExams: 20,
            averageScore: 78.5
        };
        
        this.updateProgress();
    }

    updateProgress() {
        const completionRate = (this.progressData.completedExams / this.progressData.totalExams) * 100;
        
        // Update any progress bars if they exist
        // const progressBars = document.querySelectorAll('.progress-bar');
        // progressBars.forEach(bar => {
        //     bar.style.width = `${completionRate}%`;
        // });

        // Update progress text
        const progressTexts = document.querySelectorAll('.progress-percentage');
        progressTexts.forEach(text => {
            text.textContent = `${Math.round(completionRate)}%`;
        });

        // Update any progress bars if they exist
        function updateCircleProgress(percentage) {
            const circle = document.querySelector('.progress-fill');
            const text = document.querySelector('.progress-percentage');
            const radius = 60;
            const circumference = 2 * Math.PI * radius;

            // Calculate offset based on percentage
            const offset = circumference * (1 - percentage / 100);

            circle.style.strokeDasharray = `${circumference}`;
            circle.style.strokeDashoffset = `${offset}`;
            text.textContent = `${percentage}%`;
        }
        // Set progress to 40%
        updateCircleProgress(Math.round(completionRate));
        
        
    }
}

// Theme management
class ThemeManager {
    constructor() {
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.applyTheme();
    }

    applyTheme() {
        document.body.setAttribute('data-theme', this.currentTheme);
        
        // Update theme toggle buttons
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.innerHTML = this.currentTheme === 'light' 
                ? '<i class="fas fa-moon"></i>' 
                : '<i class="fas fa-sun"></i>';
        }
    }

    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.currentTheme);
        this.applyTheme();
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize main dashboard
    const dashboard = new Dashboard();
    
    // Initialize activity feed
    const activityFeed = new ActivityFeed();
    
    // Initialize progress tracker
    const progressTracker = new ProgressTracker();
    
    // Initialize theme manager
    const themeManager = new ThemeManager();
    
    // Add global event listeners
    document.addEventListener('click', (e) => {
        // Handle navigation clicks
        if (e.target.closest('.nav-link, .nav-item, .bottom-nav-item')) {
            e.preventDefault();
            const page = e.target.closest('a').textContent.trim().toLowerCase();
            dashboard.handleNavigation(page);
        }
        
        // Handle theme toggle
        if (e.target.closest('.theme-toggle')) {
            themeManager.toggleTheme();
        }
    });
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add keyboard navigation support
    document.addEventListener('keydown', (e) => {
        // Navigate with arrow keys when focus is on navigation
        if (e.target.closest('.desktop-nav, .sidebar-nav, .mobile-bottom-nav')) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const next = e.target.closest('a').nextElementSibling;
                if (next) next.focus();
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = e.target.closest('a').previousElementSibling;
                if (prev) prev.focus();
            }
        }
    });
    
    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', () => {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log(`Dashboard loaded in ${loadTime}ms`);
        });
    }
});

// Service Worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);
            })
            .catch((registrationError) => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
