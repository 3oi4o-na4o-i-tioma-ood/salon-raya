<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избор на час - Фризьорски салон Райа</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .appointment-container {
            max-width: 800px;
            margin: 100px auto 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding-bottom: 80px;
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .appointment-header h1 {
            font-size: 24px;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .specialist-selection {
            margin: 20px 0;
        }

        .specialist-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .specialist-option:hover {
            background: #f9f9f9;
        }

        .specialist-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .specialist-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-grow: 1;
        }

        .service-summary {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 0;
        }

        .selected-service {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .selected-service h3 {
            margin: 0;
            font-size: 16px;
            flex-grow: 1;
        }

        .selected-service .price {
            font-weight: 500;
            margin-left: 20px;
            white-space: nowrap;
        }

        .service-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .service-summary-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .service-summary-header .total-time {
            color: #666;
            font-size: 14px;
        }

        .calendar-container {
            margin: 20px 0;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-top: 10px;
        }

        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #f0f0f0;
        }

        .calendar-day.selected {
            background: #007bff;
            color: white;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }

        .time-slot {
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            background: #f0f0f0;
        }

        .time-slot.selected {
            background: #007bff;
            color: white;
        }

        .total-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .total-amount {
            font-size: 18px;
            font-weight: 500;
        }

        .continue-btn {
            padding: 12px 24px;
            background: #000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
            min-width: 150px;
        }

        .continue-btn:hover {
            background: #333;
        }

        .weekday-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 5px;
            text-align: center;
            font-weight: 500;
        }

        .weekday {
            padding: 5px;
            color: #666;
        }

        .navbar, .navbar-scrolled {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #111111;
            z-index: 1000;
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-scrolled {
            transform: translateY(-100%);
            background: rgba(17, 17, 17, 0.9);
            padding: 15px 40px;
        }

        .navbar-scrolled.visible {
            transform: translateY(0);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .navbar-back {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 20px;
            padding: 0;
            margin: 0;
        }

        .navbar .logo, .navbar-scrolled .logo {
            color: #fff;
            font-size: 18px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hamburger {
            display: flex;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hamburger span {
            display: block;
            width: 25px;
            height: 2px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .nav-menu {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(17, 17, 17, 0.95);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
            z-index: 999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .nav-menu.active {
            transform: translateX(0);
        }

        .nav-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: #999;
        }

        .calendar-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
            z-index: 1100;
            width: 95%;
            max-width: 500px;
            max-height: 85vh;
            overflow-y: auto;
            display: none;
        }

        .calendar-modal.active {
            display: block;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }

        .month-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .month-year {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .month-navigation h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .year {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .nav-arrows {
            display: flex;
            gap: 10px;
        }

        .nav-arrow {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #666;
            font-size: 18px;
        }

        .weekdays-header {
            display: grid;
            grid-template-columns: repeat(7, 45px);
            gap: 12px;
            margin-bottom: 10px;
            padding: 0;
        }

        .weekday {
            text-align: center;
            font-size: 13px;
            color: #666;
            text-transform: capitalize;
        }

        .calendar-section {
            position: relative;
            margin-bottom: 25px;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 45px);
            gap: 12px;
            padding: 0;
        }

        .calendar-day {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            color: #333;
            background: #f5f5f5;
            padding: 0;
            margin: 0;
        }

        .calendar-day.selected {
            background: #000;
            color: white;
        }

        .calendar-day.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: #f5f5f5;
        }

        .time-slots-container {
            padding: 0;
        }

        .time-slots-container h3 {
            margin: 20px 0 15px;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .time-slots-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .time-slot {
            flex: 0 0 auto;
            padding: 8px 16px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
            background: #f5f5f5;
            min-width: 70px;
        }

        .time-slot.selected {
            background: #000;
            color: white;
            border-color: #000;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: none;
        }

        .modal-overlay.active {
            display: block;
        }

        .modal-footer {
            margin-top: 20px;
            padding: 0 10px;
        }

        .continue-modal-btn {
            width: 100%;
            padding: 12px;
            background: #ccc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .continue-modal-btn:not(:disabled) {
            background: #000;
            cursor: pointer;
        }

        .continue-modal-btn:disabled {
            cursor: not-allowed;
        }

        .week-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            color: #666;
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .week-nav.prev {
            left: -35px;
        }

        .week-nav.next {
            right: -35px;
        }

        .calendar-wrapper {
            position: relative;
            width: fit-content;
            margin: 0 auto;
        }

        .dates-container {
            width: fit-content;
            margin: 0 auto;
        }

        .calendar-day.past {
            opacity: 0.3;
            background: #eee;
            cursor: not-allowed;
            color: #999;
        }

        .time-slot.past {
            opacity: 0.3;
            background: #eee;
            cursor: not-allowed;
            color: #999;
            pointer-events: none;
        }

        .appointment-date {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-top: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .appointment-date:hover {
            background: #f0f0f0;
        }

        .date-info {
            font-size: 16px;
            color: #333;
        }

        .date-arrow {
            color: #666;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <button class="navbar-back" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="logo">ФРИЗЬОРСКИ САЛОН Райа</div>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <nav class="navbar-scrolled">
        <div class="navbar-left">
            <button class="navbar-back" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="logo">ФРИЗЬОРСКИ САЛОН Райа</div>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <div class="nav-menu">
        <a href="index.php#hero">Начало</a>
        <a href="index.php#services">Услуги</a>
        <a href="index.php#about">За нас</a>
        <a href="index.php#contact">Контакти</a>
    </div>

    <div class="appointment-container">
        <div class="appointment-header">
            <h1>Your appointment</h1>
            <button class="close-btn" onclick="window.location.href='booking.php'">&times;</button>
        </div>

        <div class="service-summary">
            <div id="selected-services-container">
                <!-- Services will be populated by JavaScript -->
            </div>
        </div>
        <div id="appointment-date-container" style="display: none;">
            <div class="appointment-date" onclick="showDateSelection()">
                <span class="date-info" id="selected-date-time"></span>
                <span class="date-arrow">›</span>
            </div>
        </div>
    </div>

    <div class="total-section">
        <div class="total-amount">
            <span class="price">95 лв</span>
        </div>
        <button class="continue-btn">Избери час</button>
    </div>

    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="calendar-modal" id="calendarModal">
        <div class="month-navigation">
            <div class="month-year">
                <h3 id="currentMonth">December</h3>
                <span class="year">2024</span>
            </div>
            <div class="nav-arrows">
                <button class="nav-arrow" onclick="changeMonth(-1)">‹</button>
                <button class="nav-arrow" onclick="changeMonth(1)">›</button>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div class="weekdays-header">
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>
                <div class="weekday">Sun</div>
            </div>

            <div class="calendar-section">
                <button class="week-nav prev" onclick="previousWeek()">‹</button>
                <div class="calendar-days" id="calendarDays"></div>
                <button class="week-nav next" onclick="nextWeek()">›</button>
            </div>
        </div>

        <div class="time-slots-container">
            <h3>Morning</h3>
            <div class="time-slots-grid" id="morningSlots"></div>
            
            <h3>Day</h3>
            <div class="time-slots-grid" id="daySlots"></div>
            
            <h3>Evening</h3>
            <div class="time-slots-grid" id="eveningSlots"></div>
        </div>

        <div class="modal-footer">
            <button class="continue-modal-btn" id="continueBtn" disabled>Confirm</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            displaySelectedServices();
        });

        function displaySelectedServices() {
            const servicesContainer = document.getElementById('selected-services-container');
            const services = JSON.parse(sessionStorage.getItem('selectedServices') || '[]');
            const totalPrice = sessionStorage.getItem('totalPrice');

            // Clear existing content
            servicesContainer.innerHTML = '';

            // Display each selected service
            services.forEach(service => {
                const serviceElement = document.createElement('div');
                serviceElement.classList.add('selected-service');
                serviceElement.innerHTML = `
                    <h3>${service.name}</h3>
                    <span class="price">${service.price} лв</span>
                `;
                servicesContainer.appendChild(serviceElement);
            });

            // Update total price
            document.querySelector('.total-amount .price').textContent = `${totalPrice} лв`;
        }

        const modal = document.getElementById('calendarModal');
        const overlay = document.getElementById('modalOverlay');
        const continueBtn = document.getElementById('continueBtn');
        let selectedDate = null;
        let selectedTime = null;

        document.querySelector('.continue-btn').addEventListener('click', function() {
            if (this.textContent.includes('Confirm')) {
                // Store the appointment date and time in session storage
                const dateInfo = document.getElementById('selected-date-time').textContent;
                sessionStorage.setItem('appointmentDateTime', dateInfo);
                
                // Redirect to booking details page
                window.location.href = 'booking-details.php';
            } else {
                modal.classList.add('active');
                overlay.classList.add('active');
                initializeCalendar();
                initializeTimeSlots();
            }
        });

        function closeModal() {
            modal.classList.remove('active');
            overlay.classList.remove('active');
        }

        let currentWeekStart = new Date();
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function nextWeek() {
            // Add 7 days to current week
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            
            // Check if we've moved into a new month
            const newMonth = currentWeekStart.getMonth();
            const newYear = currentWeekStart.getFullYear();
            
            // Update month and year display
            document.getElementById('currentMonth').textContent = months[newMonth];
            document.querySelector('.year').textContent = newYear;
            
            // Initialize calendar with new date
            initializeCalendar(currentWeekStart);
        }

        function initializeCalendar(startDate = new Date()) {
            currentWeekStart = new Date(startDate);
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';
            
            // Get Monday of the current week
            const currentDay = startDate.getDay(); // 0 is Sunday, 1 is Monday, etc.
            const diff = currentDay === 0 ? -6 : 1 - currentDay; // Adjust to get Monday
            
            // Set date to Monday of the week
            startDate.setDate(startDate.getDate() + diff);
            currentWeekStart = new Date(startDate);
            
            // Get the month of the middle of the week
            const middleOfWeek = new Date(startDate);
            middleOfWeek.setDate(startDate.getDate() + 3);
            const displayMonth = middleOfWeek.getMonth();
            const displayYear = middleOfWeek.getFullYear();
            
            // Update month and year display
            document.getElementById('currentMonth').textContent = months[displayMonth];
            document.querySelector('.year').textContent = displayYear;
            
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time part for proper date comparison
            
            // Show a week of dates starting from Monday
            for (let i = 0; i < 7; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                date.setHours(0, 0, 0, 0); // Reset time part for proper date comparison
                
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = date.getDate();
                
                // Check if date is in the past
                if (date < today) {
                    dayElement.classList.add('past');
                } else {
                    // Only add click event for current and future dates
                    dayElement.addEventListener('click', () => selectDate(dayElement, date.getDate()));
                    
                    // Select today's date if it's in the current week
                    if (date.getTime() === today.getTime()) {
                        dayElement.classList.add('selected');
                        selectedDate = date.getDate();
                    }
                }
                
                calendarDays.appendChild(dayElement);
            }
        }

        function initializeTimeSlots() {
            const morningSlots = ['10:00', '10:30', '11:00', '11:30'];
            const daySlots = ['12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30'];
            const eveningSlots = ['16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];

            populateTimeSlots('morningSlots', morningSlots);
            populateTimeSlots('daySlots', daySlots);
            populateTimeSlots('eveningSlots', eveningSlots);
        }

        function populateTimeSlots(containerId, slots) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            const now = new Date();
            const selectedDay = new Date(currentWeekStart);
            selectedDay.setDate(selectedDay.getDate() + Array.from(document.querySelectorAll('.calendar-day')).findIndex(day => day.classList.contains('selected')));
            
            const isToday = selectedDay.getDate() === now.getDate() && 
                           selectedDay.getMonth() === now.getMonth() && 
                           selectedDay.getFullYear() === now.getFullYear();
            
            slots.forEach(time => {
                const slot = document.createElement('div');
                slot.classList.add('time-slot');
                slot.textContent = time;
                
                // Check if the time slot is in the past for today
                if (isToday) {
                    const [hours, minutes] = time.split(':').map(Number);
                    const slotTime = new Date(now);
                    slotTime.setHours(hours, minutes, 0, 0);
                    
                    if (slotTime < now) {
                        slot.classList.add('past');
                    } else {
                        slot.addEventListener('click', () => selectTime(slot, time));
                    }
                } else {
                    slot.addEventListener('click', () => selectTime(slot, time));
                }
                
                container.appendChild(slot);
            });
        }

        function selectTime(element, time) {
            if (element.classList.contains('past')) {
                return; // Don't allow selection of past time slots
            }
            
            document.querySelectorAll('.time-slot').forEach(el => {
                el.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedTime = time;
            updateContinueButton();
        }

        function selectDate(element, day) {
            if (element.classList.contains('past')) {
                return; // Don't allow selection of past dates
            }
            
            document.querySelectorAll('.calendar-day').forEach(el => {
                el.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedDate = day;
            
            // Reinitialize time slots when date changes
            initializeTimeSlots();
            updateContinueButton();
        }

        function updateContinueButton() {
            const continueBtn = document.getElementById('continueBtn');
            continueBtn.disabled = !(selectedDate && selectedTime);
        }

        function changeWeek(delta) {
            // Implement week navigation
            // For now, just reinitialize the current week
            initializeCalendar();
        }

        // Close modal when clicking outside
        overlay.addEventListener('click', closeModal);

        let currentMonthIndex = 11; // December
        let currentYear = 2024;

        function changeMonth(delta) {
            currentMonthIndex += delta;
            
            // Handle year change
            if (currentMonthIndex > 11) {
                currentMonthIndex = 0;
                currentYear++;
            } else if (currentMonthIndex < 0) {
                currentMonthIndex = 11;
                currentYear--;
            }

            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            
            document.getElementById('currentMonth').textContent = months[currentMonthIndex];
            document.querySelector('.year').textContent = currentYear;
            
            initializeCalendar(new Date(currentYear, currentMonthIndex, 1));
        }

        function previousWeek() {
            // Subtract 7 days from current week
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            
            // Check if we've moved into a different month
            const newMonth = currentWeekStart.getMonth();
            const newYear = currentWeekStart.getFullYear();
            
            // Update month and year display
            document.getElementById('currentMonth').textContent = months[newMonth];
            document.querySelector('.year').textContent = newYear;
            
            // Initialize calendar with new date
            initializeCalendar(currentWeekStart);
        }

        function handleContinue() {
            const dateContainer = document.getElementById('appointment-date-container');
            const dateInfo = document.getElementById('selected-date-time');
            const selectedDayElement = document.querySelector('.calendar-day.selected');
            const selectedTimeElement = document.querySelector('.time-slot.selected');
            const continueButton = document.querySelector('.continue-btn');
            const totalPrice = document.querySelector('.total-amount .price').textContent;
            
            if (selectedDayElement && selectedTimeElement) {
                const date = new Date(currentWeekStart);
                const dayIndex = Array.from(document.querySelectorAll('.calendar-day')).indexOf(selectedDayElement);
                date.setDate(currentWeekStart.getDate() + dayIndex);
                
                const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                
                const formattedDate = `${dayNames[date.getDay()]}, ${date.getDate()} ${monthNames[date.getMonth()]} ${selectedTimeElement.textContent}`;
                dateInfo.textContent = formattedDate;
                dateContainer.style.display = 'block';
                
                // Update the continue button text
                continueButton.innerHTML = `Confirm <span class="price">${totalPrice}</span>`;
                
                closeModal();
            }
        }

        function showDateSelection() {
            modal.classList.add('active');
            overlay.classList.add('active');
            initializeCalendar(currentWeekStart);
            initializeTimeSlots();
        }

        document.getElementById('continueBtn').addEventListener('click', handleContinue);

        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        const scrolledNavbar = document.querySelector('.navbar-scrolled');
        const hamburger = document.querySelectorAll('.hamburger');
        const menu = document.querySelector('.nav-menu');

        window.addEventListener('scroll', () => {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.style.transform = 'translateY(-100%)';
                scrolledNavbar.classList.add('visible');
            } else {
                navbar.style.transform = 'translateY(0)';
                scrolledNavbar.classList.remove('visible');
            }
            
            lastScrollTop = scrollTop;
        });

        hamburger.forEach(ham => {
            ham.addEventListener('click', () => {
                hamburger.forEach(h => h.classList.toggle('active'));
                menu.classList.toggle('active');
            });
        });
    </script>

    <script src="js/main.js"></script>
</body>
</html>
