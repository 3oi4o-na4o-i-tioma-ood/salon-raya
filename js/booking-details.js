document.addEventListener('DOMContentLoaded', function() {
    // Get selected services from session storage
    const selectedServices = JSON.parse(sessionStorage.getItem('selectedServices') || '[]');
    const servicesContainer = document.getElementById('selectedServices');
    const totalPriceElement = document.querySelector('.total-price');
    const bookButton = document.getElementById('bookButton');
    const form = document.getElementById('bookingForm');

    // Calendar Modal Elements
    const dateTimeSelector = document.getElementById('dateTimeSelector');
    const calendarModal = document.getElementById('calendarModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const closeModalBtn = document.querySelector('.close-modal');
    const selectedDateTimeText = document.getElementById('selectedDateTime');
    const calendarSections = document.querySelector('.calendar-sections');
    const continueToHoursBtn = document.getElementById('continueToHours');
    const confirmDateTimeBtn = document.getElementById('confirmDateTime');
    const backToDatesBtn = document.querySelector('.back-to-dates');
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const currentMonthElement = document.getElementById('currentMonth');
    const currentYearElement = document.getElementById('currentYear');
    const calendarDays = document.getElementById('calendarDays');
    const morningSlots = document.getElementById('morningSlots');
    const afternoonSlots = document.getElementById('afternoonSlots');
    const eveningSlots = document.getElementById('eveningSlots');

    // Hidden inputs for form submission
    const appointmentDateInput = document.createElement('input');
    appointmentDateInput.type = 'hidden';
    appointmentDateInput.name = 'appointment_date';
    form.appendChild(appointmentDateInput);

    const appointmentTimeInput = document.createElement('input');
    appointmentTimeInput.type = 'hidden';
    appointmentTimeInput.name = 'appointment_time';
    form.appendChild(appointmentTimeInput);

    // Display selected services and calculate total
    let totalPrice = 0;
    const serviceNames = [];
    selectedServices.forEach(service => {
        const serviceElement = document.createElement('div');
        serviceElement.className = 'booking-detail';
        serviceElement.innerHTML = `
            <div class="detail-left">
                <i class="fas fa-check detail-icon"></i>
                <div class="detail-text">${service.name}</div>
            </div>
            <div class="detail-right">${service.price} лв.</div>
        `;
        servicesContainer.appendChild(serviceElement);
        totalPrice += service.price;
        serviceNames.push(service.name);
    });

    // Update total price
    totalPriceElement.textContent = totalPrice.toFixed(0) + ' лв.';
    
    // Store services in hidden input
    document.getElementById('service').value = serviceNames.join(', ');

    // Calendar functionality
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;
    let bookedIntervalsForSelectedDate = []; // Store booked intervals [ {start: min, end: min} ]

    const months = ['Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни', 'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'];

    function updateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const today = new Date();
        
        currentMonthElement.textContent = months[month];
        currentYearElement.textContent = year;

        // Disable prev month button if we're at current month
        const isPrevMonthDisabled = month === today.getMonth() && year === today.getFullYear();
        prevMonthBtn.disabled = isPrevMonthDisabled;
        prevMonthBtn.style.opacity = isPrevMonthDisabled ? '0.3' : '1';

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay() || 7; // Convert Sunday (0) to 7

        calendarDays.innerHTML = '';

        // Add empty cells for days before the first day of the month
        for (let i = 1; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day disabled';
            calendarDays.appendChild(emptyDay);
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            const date = new Date(year, month, day);
            if (date < new Date().setHours(0, 0, 0, 0)) {
                dayElement.classList.add('disabled');
            } else {
                dayElement.addEventListener('click', () => selectDate(date));
            }

            if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                dayElement.classList.add('selected');
            }

            calendarDays.appendChild(dayElement);
        }
    }

    // --- Helper: Time String to Minutes ---
    function timeToMinutes(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // --- Helper: Minutes to Time String ---
    function minutesToTime(totalMinutes) {
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    }

    // --- Fetch Booked Times (Modified to store intervals) ---
    async function fetchBookedTimes(date) {
        const formattedDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
        bookedIntervalsForSelectedDate = []; // Reset before fetching
        try {
            const response = await fetch(`get_booked_times.php?date=${formattedDate}`);
            if (!response.ok) {
                throw new Error('Network response was not ok for booked times');
            }
            bookedIntervalsForSelectedDate = await response.json(); // Expecting [{start: min, end: min}, ...]
        } catch (error) {
            console.error('Error fetching booked intervals:', error);
            // Handle error appropriately
        }
    }

    // --- Generate and Update Time Slots (Modified for Day-Specific Hours) ---
    function generateTimeSlots() {
        const slots = [];
        const interval = 30; // 30 minutes interval
        let openingTimeMinutes = 10 * 60; // Default 10:00 AM
        let closingTimeMinutes = 20 * 60; // Default 8:00 PM

        if (selectedDate) { // Check if a date is actually selected
            const dayOfWeek = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday
            if (dayOfWeek === 0 || dayOfWeek === 6) { // Weekend
                openingTimeMinutes = 12 * 60; // 12:00 PM
                closingTimeMinutes = 17 * 60; // 5:00 PM
            }
        }
        // Else, use default weekday hours if no date selected (though UI should prevent this)

        // Calculate last possible start time based on dynamic closing time
        const lastSlotStartMinutes = closingTimeMinutes - interval;

        for (let minutes = openingTimeMinutes; minutes <= lastSlotStartMinutes; minutes += interval) {
            const timeString = minutesToTime(minutes);
            slots.push({
                time: timeString,
                disabled: false
            });
        }
        return slots;
    }

    // --- Update Time Slots (Modified for Day-Specific Hours) ---
    function updateTimeSlots() {
        const slots = generateTimeSlots(); // Generation now depends on selectedDate
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';
        
        const currentSelectedServices = JSON.parse(sessionStorage.getItem('selectedServices') || '[]');
        
        const totalDurationMinutes = currentSelectedServices.reduce((sum, s) => {
             // Handle potential duration ranges (e.g., "40-55") - take the max for safety
             let duration = 0;
             if (s.duration && typeof s.duration === 'string' && s.duration.includes('-')) {
                 const parts = s.duration.split('-').map(Number);
                 duration = Math.max(...parts);
             } else {
                 duration = parseInt(s.duration) || 0; // Default to 0 if not parseable
             }
             return sum + duration;
        }, 0);
        
        // Use a default duration only if calculated is zero
        const finalDuration = totalDurationMinutes > 0 ? totalDurationMinutes : 60; 

        // Determine closing time based on selected date
        let salonClosingTimeMinutes = 20 * 60; // Default 8:00 PM
        if (selectedDate) {
            const dayOfWeek = selectedDate.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) { // Weekend
                salonClosingTimeMinutes = 17 * 60; // 5:00 PM
            }
        }

        slots.forEach(slot => {
            const timeSlotElement = document.createElement('div');
            timeSlotElement.className = 'time-slot';
            const slotStartMinutes = timeToMinutes(slot.time);
            const slotEndMinutes = slotStartMinutes + finalDuration;
            let isDisabled = false;
            let disableReason = '';
            timeSlotElement.classList.remove('conflict');

            // Check 1: Exceeds closing time
            if (slotEndMinutes > salonClosingTimeMinutes) {
                isDisabled = true;
                disableReason = ' (няма време)';
            }

            // Check 2: Overlap check (uses bookedIntervalsForSelectedDate)
            if (!isDisabled) {
                for (const bookedInterval of bookedIntervalsForSelectedDate) {
                    if (bookedInterval.start < slotEndMinutes && bookedInterval.end > slotStartMinutes) {
                        isDisabled = true;
                        if (bookedInterval.start === slotStartMinutes) {
                             disableReason = ' (заето)';
                        } else {
                            timeSlotElement.classList.add('conflict');
                        }
                        break; 
                    }
                }
            }
            
            slot.disabled = isDisabled;

            // --- Styling and Selection Logic (minor adjustments) ---
            if (selectedTime === slot.time && !isDisabled) { 
                timeSlotElement.classList.add('selected');
            } else if (selectedTime === slot.time && isDisabled) {
                 selectedTime = null; 
                 confirmDateTimeBtn.disabled = true; 
            }

            if (slot.disabled) {
                timeSlotElement.classList.add('disabled');
                timeSlotElement.textContent = `${slot.time}${disableReason}`;
            } else {
                 timeSlotElement.textContent = slot.time;
                 timeSlotElement.addEventListener('click', () => selectTime(slot.time));
            }
           
            container.appendChild(timeSlotElement);
        });
        
        // Re-check if the currently selected time is still valid after update
        if (selectedTime) {
             const selectedSlotElement = Array.from(container.children).find(el => !el.classList.contains('disabled') && el.textContent === selectedTime);
             if (!selectedSlotElement) {
                 // The selected time is no longer valid/available
                 selectedTime = null;
                 confirmDateTimeBtn.disabled = true;
             } else {
                  selectedSlotElement.classList.add('selected'); // Ensure it stays visually selected
                  confirmDateTimeBtn.disabled = false; 
             }
        }
    }

    // --- Select Date (Modified) ---
    async function selectDate(date) { // Make async to wait for fetch
        selectedDate = date;
        selectedTime = null; 
        confirmDateTimeBtn.disabled = true; // Disable confirm until time is selected
        continueToHoursBtn.disabled = false; // Enable button to go to hours
        updateCalendar(); // Update calendar display
        
        // Fetch booked times for the newly selected date
        await fetchBookedTimes(selectedDate);
        
        // Time slots will be updated when user clicks 'Continue to Hours'
        // or if the view is already showing hours.
        if (calendarSections.classList.contains('show-hours')) {
             updateTimeSlots(); // Update immediately if hours are visible
        }
    }

    // --- Select Time (Modified to check if disabled) ---
    function selectTime(time) {
        // Find the clicked element to ensure it's not disabled before selecting
        const clickedSlotElement = Array.from(document.getElementById('timeSlots').children).find(el => el.textContent === time);
        
        if (clickedSlotElement && !clickedSlotElement.classList.contains('disabled')) {
            selectedTime = time;
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.toggle('selected', !slot.classList.contains('disabled') && slot.textContent === time);
            });
            confirmDateTimeBtn.disabled = false;
        }
    }

    function formatDate(date) {
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}.${month}.${year}`;
    }

    // Event Listeners
    dateTimeSelector.addEventListener('click', () => {
        calendarModal.style.display = 'block';
        modalOverlay.classList.add('active');
        calendarSections.classList.remove('show-hours');
        updateCalendar();
        updateTimeSlots();
    });

    closeModalBtn.addEventListener('click', () => {
        calendarModal.style.display = 'none';
        modalOverlay.classList.remove('active');
    });

    modalOverlay.addEventListener('click', () => {
        calendarModal.style.display = 'none';
        modalOverlay.classList.remove('active');
    });

    continueToHoursBtn.addEventListener('click', () => {
        if (selectedDate) {
            calendarSections.classList.add('show-hours');
            // Ensure time slots are updated with availability for the selected date
            updateTimeSlots(); 
        }
    });

    backToDatesBtn.addEventListener('click', () => {
        calendarSections.classList.remove('show-hours');
    });

    confirmDateTimeBtn.addEventListener('click', () => {
        if (selectedDate && selectedTime) {
            const formattedDate = formatDate(selectedDate);
            selectedDateTimeText.textContent = `${formattedDate} - ${selectedTime}`;
            appointmentDateInput.value = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getDate().toString().padStart(2, '0')}`;
            appointmentTimeInput.value = selectedTime;
            calendarModal.style.display = 'none';
            modalOverlay.classList.remove('active');
            validateForm();
        }
    });

    // Add phone validation
    const phoneInput = document.getElementById('phone');
    const phoneError = phoneInput.nextElementSibling;

    function validatePhone(phone) {
        // Accept following formats:
        // 1. Starting with 0 followed by 9 digits (e.g., 0888123456)
        // 2. Starting with + and 1-4 digits for country code, followed by 9-10 digits (e.g., +359888123456, +1234567890)
        // 3. Starting with 00 and 1-4 digits for country code, followed by 9-10 digits (e.g., 00359888123456)
        const phoneRegex = /^(?:(?:\+\d{1,4}|\d{2}\d{1,4})|0)\d{9,10}$/;
        return phoneRegex.test(phone);
    }

    phoneInput.addEventListener('input', function() {
        const isValid = validatePhone(this.value);
        phoneError.style.display = isValid ? 'none' : 'block';
        phoneError.textContent = isValid ? '' : 'Моля, въведете валиден телефонен номер';
        validateForm();
    });

    // Add email validation
    const emailInput = document.getElementById('email');
    const emailError = emailInput.nextElementSibling;

    function validateEmail(email) {
        // Accept any email in format: something@something.something
        const emailRegex = /^[^@]+@[^@]+\.[^@]+$/;
        return emailRegex.test(email);
    }

    emailInput.addEventListener('input', function() {
        const isValid = validateEmail(this.value);
        emailError.style.display = isValid ? 'none' : 'block';
        emailError.textContent = isValid ? '' : 'Моля, въведете валиден имейл адрес';
        validateForm();
    });

    // Enable book button when form is valid
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
            }
            // Special validation for phone
            if (field.id === 'phone' && !validatePhone(field.value)) {
                isValid = false;
            }
            // Special validation for email
            if (field.id === 'email' && !validateEmail(field.value)) {
                isValid = false;
            }
        });
        
        // Also check if date and time are selected
        if (!appointmentDateInput.value || !appointmentTimeInput.value) {
            isValid = false;
        }
        
        bookButton.disabled = !isValid;
        bookButton.classList.toggle('active', isValid);
    }

    // Add event listeners for form validation
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', validateForm);
    });

    // Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Записване...';

        const formData = new FormData(form);
        
        // Add selected services in a format PHP can easily parse
        formData.append('service_details', JSON.stringify(selectedServices));

        try {
            const response = await fetch('save_appointment.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                sessionStorage.removeItem('selectedServices');
                window.location.href = 'booking-confirmation.php';
            } else {
                alert(`Грешка: ${result.message}`);
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            alert('Възникна грешка при изпращане на резервацията. Моля, опитайте отново.');
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    });

    // Event Listeners
    prevMonthBtn.addEventListener('click', () => {
        const today = new Date();
        const newDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
        
        // Only allow going to previous month if it's not before current month
        if (newDate.getMonth() >= today.getMonth() && newDate.getFullYear() >= today.getFullYear()) {
            currentDate = newDate;
            updateCalendar();
        }
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
        updateCalendar();
    });

    // Initialize calendar
    updateCalendar();
}); 