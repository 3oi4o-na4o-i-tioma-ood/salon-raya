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
    let bookedTimesForSelectedDate = []; // Store booked times

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

    // --- Fetch Booked Times --- 
    async function fetchBookedTimes(date) {
        const formattedDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
        bookedTimesForSelectedDate = []; // Reset before fetching
        try {
            const response = await fetch(`get_booked_times.php?date=${formattedDate}`);
            if (!response.ok) {
                throw new Error('Network response was not ok for booked times');
            }
            bookedTimesForSelectedDate = await response.json();
            console.log(`Booked times for ${formattedDate}:`, bookedTimesForSelectedDate);
        } catch (error) {
            console.error('Error fetching booked times:', error);
            // Handle error appropriately, maybe disable all time slots
        }
    }

    // --- Generate and Update Time Slots (Modified for Duration Check) ---
    function generateTimeSlots() {
        const slots = [];
        
        // Generate slots from 10:00 to 16:30 with 30-minute intervals
        for (let hour = 10; hour <= 16; hour++) {
            for (let minute of [0, 30]) {
                const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                slots.push({
                    time: timeString,
                    disabled: false
                });
            }
        }
        return slots;
    }

    function updateTimeSlots() {
        const slots = generateTimeSlots();
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';
        
        // Calculate total duration from selected services
        const currentSelectedServices = JSON.parse(sessionStorage.getItem('selectedServices') || '[]');
        const totalDurationMinutes = currentSelectedServices.reduce((sum, s) => sum + (parseInt(s.duration) || 0), 0);
        
        // Convert booked times to minutes for easier comparison
        const bookedMinutesSet = new Set(bookedTimesForSelectedDate.map(timeToMinutes));
        const salonClosingTimeMinutes = 20 * 60; // 8:00 PM

        console.log("Updating time slots. Duration:", totalDurationMinutes, "Booked Minutes:", Array.from(bookedMinutesSet));

        slots.forEach(slot => {
            const timeSlotElement = document.createElement('div');
            timeSlotElement.className = 'time-slot';
            const slotStartMinutes = timeToMinutes(slot.time);
            const slotEndMinutes = slotStartMinutes + totalDurationMinutes;
            let isDisabled = false;
            let disableReason = '';

            // Check 1: Is the start time itself already booked?
            if (bookedMinutesSet.has(slotStartMinutes)) {
                isDisabled = true;
                disableReason = ' (заето)';
            }

            // Check 2: Does the appointment exceed closing time?
            if (!isDisabled && slotEndMinutes > salonClosingTimeMinutes) {
                isDisabled = true;
                disableReason = ' (няма време)'; // Not enough time before closing
            }

            // Check 3: Does the duration overlap with any *other* booked slot?
            if (!isDisabled) {
                for (let checkMin = slotStartMinutes; checkMin < slotEndMinutes; checkMin += 30) {
                    if (bookedMinutesSet.has(checkMin)) {
                         if (checkMin !== slotStartMinutes) { 
                            isDisabled = true;
                            timeSlotElement.classList.add('conflict');
                            break; 
                         }
                    }
                }
            }
            
            slot.disabled = isDisabled;

            if (selectedTime === slot.time && !isDisabled) { // Can only select if not disabled
                timeSlotElement.classList.add('selected');
            } else if (selectedTime === slot.time && isDisabled) {
                 // If the previously selected time becomes disabled, deselect it
                 selectedTime = null; 
                 confirmDateTimeBtn.disabled = true; 
            }

            if (slot.disabled) {
                timeSlotElement.classList.add('disabled');
                timeSlotElement.textContent = `${slot.time}${timeSlotElement.classList.contains('conflict') ? '' : disableReason}`;
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
                 // Optionally inform the user
                 // alert("Избраният час вече не е наличен.");
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
        } else {
            console.warn("Attempted to select a disabled time slot:", time);
            // Optionally provide feedback to user
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

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        fetch('save_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Change back to json()
        })
        .then(data => {
            if (data.success) {
                // Restore original redirect
                window.location.href = 'booking-confirmation.php';
            } else {
                console.error('Booking failed:', data.message);
                alert('Booking failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Don't redirect on error
            alert('Error submitting form: ' + error.message);
        });
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