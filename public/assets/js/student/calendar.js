// Calendar functionality
class Calendar {
    constructor() {
        this.currentDate = new Date(2024, 11, 1); // December 2024
        this.today = new Date(2024, 11, 14); // December 14, 2024 (today)
        this.examDates = [
            new Date(2024, 11, 14), // December 14, 2024 (today - highlighted)
            new Date(2024, 11, 18), // December 18, 2024
            new Date(2024, 11, 22), // December 22, 2024
            new Date(2024, 11, 28), // December 28, 2024
            new Date(2025, 0, 5),   // January 5, 2025
            new Date(2025, 0, 12),  // January 12, 2025
            new Date(2025, 0, 20),  // January 20, 2025
        ];
        
        this.monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        this.dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.render();
    }

    setupEventListeners() {
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');
        
        prevBtn?.addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.render();
        });
        
        nextBtn?.addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.render();
        });
    }

    render() {
        this.updateHeader();
        this.generateCalendar();
    }

    updateHeader() {
        const monthElement = document.getElementById('currentMonth');
        if (monthElement) {
            const monthYear = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
            monthElement.textContent = monthYear;
        }
    }

    generateCalendar() {
        const calendarElement = document.getElementById('calendar');
        if (!calendarElement) return;

        // Clear previous calendar
        calendarElement.innerHTML = '';

        // Create calendar structure
        const calendarHeader = this.createCalendarHeader();
        const calendarGrid = this.createCalendarGrid();

        calendarElement.appendChild(calendarHeader);
        calendarElement.appendChild(calendarGrid);
    }

    createCalendarHeader() {
        const header = document.createElement('div');
        header.className = 'calendar-header';

        this.dayNames.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day-header';
            dayElement.textContent = day;
            header.appendChild(dayElement);
        });

        return header;
    }

    createCalendarGrid() {
        const grid = document.createElement('div');
        grid.className = 'calendar-grid';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        // Get first day of the month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        // Add previous month's trailing days
        for (let i = firstDay - 1; i >= 0; i--) {
            const dayElement = this.createDayElement(
                daysInPrevMonth - i, 
                true, 
                new Date(year, month - 1, daysInPrevMonth - i)
            );
            grid.appendChild(dayElement);
        }

        // Add current month's days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = this.createDayElement(
                day, 
                false, 
                new Date(year, month, day)
            );
            grid.appendChild(dayElement);
        }

        // Add next month's leading days
        const totalCells = grid.children.length;
        const remainingCells = 42 - totalCells; // 6 rows * 7 days
        
        for (let day = 1; day <= remainingCells; day++) {
            const dayElement = this.createDayElement(
                day, 
                true, 
                new Date(year, month + 1, day)
            );
            grid.appendChild(dayElement);
        }

        return grid;
    }

    createDayElement(dayNumber, isOtherMonth, date) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.textContent = dayNumber;

        // Add classes based on day type
        if (isOtherMonth) {
            dayElement.classList.add('other-month');
        }

        // Check if it's today
        if (this.isSameDay(date, this.today)) {
            dayElement.classList.add('today');
        }

        // Check if it has an exam
        if (this.hasExam(date)) {
            dayElement.classList.add('has-exam');
            dayElement.title = `Exam scheduled on ${date.toLocaleDateString()}`;
        }

        // Add click event
        dayElement.addEventListener('click', () => {
            this.onDateClick(date, dayElement);
        });

        // Add hover effects for exam dates
        if (this.hasExam(date)) {
            dayElement.addEventListener('mouseenter', () => {
                this.showExamTooltip(dayElement, date);
            });
            
            dayElement.addEventListener('mouseleave', () => {
                this.hideExamTooltip();
            });
        }

        return dayElement;
    }

    isSameDay(date1, date2) {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
    }

    hasExam(date) {
        return this.examDates.some(examDate => this.isSameDay(date, examDate));
    }

    onDateClick(date, element) {
        // Remove previous selection
        document.querySelectorAll('.calendar-day.selected').forEach(el => {
            el.classList.remove('selected');
        });

        // Add selection to clicked date
        element.classList.add('selected');

        // Show exam details if available
        if (this.hasExam(date)) {
            this.showExamDetails(date);
        } else {
            this.showDateInfo(date);
        }
    }

    showExamDetails(date) {
        const examInfo = this.getExamInfo(date);
        const message = `Exam Details for ${date.toLocaleDateString()}:\n\n${examInfo}`;
        
        // In a real app, this would show a proper modal/popup
        alert(message);
    }

    showDateInfo(date) {
        const message = `Selected Date: ${date.toLocaleDateString()}\n\nNo exams scheduled for this date.`;
        
        // In a real app, this would show a proper modal/popup
        alert(message);
    }

    getExamInfo(date) {
        // Mock exam data based on date
        const examTypes = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English'];
        const examType = examTypes[date.getDate() % examTypes.length];
        
        const timeSlots = ['09:00 AM', '11:00 AM', '02:00 PM', '04:00 PM'];
        const timeSlot = timeSlots[date.getDate() % timeSlots.length];
        
        const durations = ['2 hours', '3 hours', '1.5 hours'];
        const duration = durations[date.getDate() % durations.length];
        
        return `Subject: ${examType}\nTime: ${timeSlot}\nDuration: ${duration}\nMode: Online\nStatus: Upcoming`;
    }

    showExamTooltip(element, date) {
        // Remove existing tooltips
        this.hideExamTooltip();
        
        const tooltip = document.createElement('div');
        tooltip.className = 'exam-tooltip';
        tooltip.innerHTML = `
            <div class="tooltip-header">Exam Scheduled</div>
            <div class="tooltip-content">
                <div class="tooltip-date">${date.toLocaleDateString()}</div>
                <div class="tooltip-subject">${this.getExamSubject(date)}</div>
            </div>
        `;
        
        // Position tooltip
        const rect = element.getBoundingClientRect();
        tooltip.style.position = 'absolute';
        tooltip.style.top = `${rect.top - 80}px`;
        tooltip.style.left = `${rect.left + rect.width / 2}px`;
        tooltip.style.transform = 'translateX(-50%)';
        tooltip.style.zIndex = '1000';
        tooltip.style.background = 'rgba(0, 0, 0, 0.8)';
        tooltip.style.color = 'white';
        tooltip.style.padding = '0.5rem';
        tooltip.style.borderRadius = '8px';
        tooltip.style.fontSize = '0.8rem';
        tooltip.style.whiteSpace = 'nowrap';
        tooltip.style.pointerEvents = 'none';
        
        document.body.appendChild(tooltip);
    }

    hideExamTooltip() {
        const existingTooltip = document.querySelector('.exam-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }
    }

    getExamSubject(date) {
        const subjects = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English'];
        return subjects[date.getDate() % subjects.length];
    }

    // Method to add new exam date
    addExamDate(date) {
        if (!this.hasExam(date)) {
            this.examDates.push(new Date(date));
            this.render();
        }
    }

    // Method to remove exam date
    removeExamDate(date) {
        this.examDates = this.examDates.filter(examDate => !this.isSameDay(examDate, date));
        this.render();
    }

    // Method to get upcoming exams
    getUpcomingExams(days = 7) {
        const upcoming = [];
        const cutoffDate = new Date();
        cutoffDate.setDate(cutoffDate.getDate() + days);

        this.examDates.forEach(examDate => {
            if (examDate >= this.today && examDate <= cutoffDate) {
                upcoming.push({
                    date: examDate,
                    subject: this.getExamSubject(examDate),
                    daysUntil: Math.ceil((examDate - this.today) / (1000 * 60 * 60 * 24))
                });
            }
        });

        return upcoming.sort((a, b) => a.date - b.date);
    }

    // Method to navigate to specific month
    navigateToMonth(year, month) {
        this.currentDate = new Date(year, month, 1);
        this.render();
    }

    // Method to navigate to today
    navigateToToday() {
        this.currentDate = new Date(this.today);
        this.render();
        
        // Highlight today's date
        setTimeout(() => {
            const todayElement = document.querySelector('.calendar-day.today');
            if (todayElement) {
                todayElement.click();
            }
        }, 100);
    }
}

// Calendar utilities
class CalendarUtils {
    static getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    }

    static getFirstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    }

    static isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
    }

    static formatDate(date, format = 'MM/DD/YYYY') {
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const year = date.getFullYear();

        switch (format) {
            case 'MM/DD/YYYY':
                return `${month}/${day}/${year}`;
            case 'DD/MM/YYYY':
                return `${day}/${month}/${year}`;
            case 'YYYY-MM-DD':
                return `${year}-${month}-${day}`;
            default:
                return date.toLocaleDateString();
        }
    }

    static addDays(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    static subtractDays(date, days) {
        return CalendarUtils.addDays(date, -days);
    }

    static daysBetween(date1, date2) {
        const oneDay = 24 * 60 * 60 * 1000;
        return Math.round(Math.abs((date1 - date2) / oneDay));
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const calendar = new Calendar();
    
    // Add quick navigation to today
    const todayBtn = document.createElement('button');
    todayBtn.textContent = 'Today';
    todayBtn.className = 'today-btn';
    todayBtn.style.cssText = `
        background: #4c6ef5;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        margin-left: 1rem;
    `;
    
    todayBtn.addEventListener('click', () => {
        calendar.navigateToToday();
    });
    
    // Add today button to calendar controls
    const calendarControls = document.querySelector('.calendar-controls');
    if (calendarControls) {
        calendarControls.appendChild(todayBtn);
    }
    
    // Show upcoming exams in console (for debugging)
    const upcomingExams = calendar.getUpcomingExams();
    if (upcomingExams.length > 0) {
        console.log('Upcoming Exams:', upcomingExams);
    }
    
    // Keyboard navigation for calendar
    document.addEventListener('keydown', (e) => {
        if (e.target.closest('.calendar')) {
            const selectedDay = document.querySelector('.calendar-day.selected');
            let targetDay = null;
            
            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    targetDay = selectedDay?.previousElementSibling;
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    targetDay = selectedDay?.nextElementSibling;
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (selectedDay) {
                        const index = Array.from(selectedDay.parentNode.children).indexOf(selectedDay);
                        targetDay = selectedDay.parentNode.children[index - 7];
                    }
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    if (selectedDay) {
                        const index = Array.from(selectedDay.parentNode.children).indexOf(selectedDay);
                        targetDay = selectedDay.parentNode.children[index + 7];
                    }
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    if (selectedDay) {
                        selectedDay.click();
                    }
                    break;
            }
            
            if (targetDay && targetDay.classList.contains('calendar-day')) {
                targetDay.click();
            }
        }
    });
});
