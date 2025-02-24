<?php
// Get available time slots
$times = [
    '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
];
?>

<!-- Add Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Add Flatpickr Bulgarian locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/bg.js"></script>

<form id="appointment-form" class="booking-form" method="POST">
    <div class="form-group">
        <label for="client_name">Име и фамилия *</label>
        <input type="text" id="client_name" name="client_name" required>
    </div>

    <div class="form-group">
        <label for="phone">Телефон *</label>
        <input type="tel" id="phone" name="phone" required>
    </div>

    <div class="form-group">
        <label for="email">Имейл *</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="service">Изберете услуга *</label>
        <select id="service" name="service" required>
            <option value="">Изберете услуга</option>
            <optgroup label="Коса">
                <option value="Дамско подстригване">Дамско подстригване</option>
                <option value="Дамско подстригване + измиване и подсушаване">Дамско подстригване + измиване и подсушаване</option>
                <option value="Сешоар">Сешоар</option>
            </optgroup>
            <optgroup label="Лице">
                <option value="Почистване на лице">Почистване на лице</option>
                <option value="Терапия за лице">Терапия за лице</option>
            </optgroup>
            <optgroup label="Епилация">
                <option value="Цяло тяло">Цяло тяло</option>
                <option value="Крака">Крака</option>
                <option value="Ръце">Ръце</option>
            </optgroup>
        </select>
    </div>

    <div class="form-group">
        <label for="appointment_date">Изберете дата *</label>
        <input type="date" id="appointment_date" name="appointment_date" required>
    </div>

    <div class="form-group">
        <label for="appointment_time">Изберете час *</label>
        <select id="appointment_time" name="appointment_time" required>
            <option value="">Изберете час</option>
            <?php foreach ($times as $time): ?>
                <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="comment">Коментар (по желание)</label>
        <textarea id="comment" name="comment" rows="3"></textarea>
    </div>

    <button type="submit" class="submit-btn">Запази час</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointment-form');
    
    // Set minimum date to today
    const dateInput = document.getElementById('appointment_date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // Initialize flatpickr with Bulgarian locale
    flatpickr("#appointment_date", {
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "bg",
        disable: [
            function(date) {
                // Disable Sundays
                return date.getDay() === 0;
            }
        ]
    });

    // Form submission
    appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('save_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Резервацията е успешно запазена!');
                appointmentForm.reset();
                // Reinitialize flatpickr after form reset
                flatpickr("#appointment_date", {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    locale: "bg",
                    disable: [
                        function(date) {
                            return date.getDay() === 0;
                        }
                    ]
                });
            } else {
                alert(data.message || 'Възникна грешка при запазването на резервацията. Моля, опитайте отново.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Възникна грешка при комуникацията със сървъра. Моля, опитайте отново.');
        });
    });
});
</script> 