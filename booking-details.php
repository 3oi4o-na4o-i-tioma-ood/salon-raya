<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Детайли за вашата резервация в салон Райа. Информация за избраната услуга, дата и час.">
    <title>Детайли за резервацията - Салон Рая</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .booking-details-container {
            max-width: 800px;
            margin: 100px auto 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .booking-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            position: relative;
        }

        .booking-header .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f5f5f5;
        }

        .booking-header-text h1 {
            font-size: 20px;
            margin: 0;
            font-weight: 500;
        }

        .booking-header-text p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 500;
            margin: 0 0 15px;
        }

        .booking-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .detail-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .detail-icon {
            color: #666;
            font-size: 18px;
        }

        .detail-text {
            font-size: 16px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .required {
            color: red;
            margin-left: 3px;
        }

        .privacy-checkbox {
            margin: 20px 0;
        }

        .privacy-checkbox label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .privacy-checkbox input[type="checkbox"] {
            margin-top: 3px;
        }

        .privacy-checkbox a {
            color: #007bff;
            text-decoration: none;
        }

        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 15px 20px;
            background: #000;
            border-radius: 8px;
            color: white;
        }

        .total-text {
            font-size: 16px;
            font-weight: 500;
        }

        .total-price {
            font-size: 16px;
            font-weight: 500;
            color: #b19cd9;
        }

        .book-btn {
            width: 100%;
            padding: 12px 24px;
            background: #ccc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: not-allowed;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .book-btn.active {
            background: #000;
            cursor: pointer;
        }

        .form-group input.error {
            border-color: #ff4444;
        }

        .error-message {
            color: #ff4444;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error-message.visible {
            display: block;
        }

        .section-content {
            margin-bottom: 30px;
        }

        .back-button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 10px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Calendar Modal Styles */
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
            max-height: 95vh;
            overflow: hidden;
            display: none;
        }

        @media (max-width: 600px) {
            .calendar-modal {
                width: 100%;
                padding: 15px;
            }
        }

        .calendar-sections {
            display: flex;
            width: 200%;
            transition: transform 0.3s ease;
            overflow: hidden;
            background: white;
            gap: 5%;
        }

        .calendar-section {
            width: 47.5%;
            flex-shrink: 0;
            padding-top: 35px;
            overflow: hidden;
            background: white;
            position: relative;
            max-height: 450px;
            overflow-y: auto;
        }

        /* Add a white background that covers any potential content bleeding through */
        .calendar-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: -1px;
            bottom: 0;
            width: 2px;
            background: white;
        }

        .calendar-sections.show-hours {
            transform: translateX(-52.5%);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 500;
            margin: 0;
            color: #333;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .calendar-nav button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #666;
        }

        .calendar-nav span {
            margin: 0 3px;
            color: #666;
            font-size: 15px;
        }

        .back-to-dates {
            display: none;
        }

        .calendar-sections.show-hours .back-to-dates {
            display: block;
        }

        /* Add smooth scrolling */
        .calendar-modal::-webkit-scrollbar {
            width: 8px;
        }

        .calendar-modal::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .calendar-modal::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .calendar-modal::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Keep header fixed while scrolling */
        .modal-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
            padding: 0 0 20px 0;
        }

        .month-navigation {
            position: sticky;
            top: 60px;
            background: white;
            z-index: 1;
            padding: 0 0 25px 0;
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
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            width: 38px;
            height: 38px;
            margin: auto;
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
            cursor: not-allowed;
            transition: all 0.3s ease;
        }

        .continue-modal-btn:not(:disabled) {
            background: #000;
            cursor: pointer;
        }

        .continue-modal-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .calendar-grid {
            margin: 15px auto 12px;
            max-width: 280px;
        }

        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: 500;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            width: 38px;
            height: 38px;
            margin: auto;
        }

        .calendar-day:hover:not(.disabled) {
            background-color: #f0f0f0;
        }

        .calendar-day.selected {
            background-color: #000;
            color: white;
        }

        .calendar-day.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .time-slots {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 6px;
            margin: 0 auto;
            width: 80%;
            justify-content: center;
            padding-left: 25px;
            margin-top: 10px;
        }

        .time-section {
            margin-bottom: 20px;
        }

        .time-section h4 {
            margin-bottom: 10px;
            color: #666;
        }

        .slot-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
        }

        .time-slot {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            background: white;
            margin: 0 auto;
            width: 80px;
        }

        .time-slot:hover {
            background-color: #f0f0f0;
        }

        .time-slot.selected {
            background-color: #000;
            color: white;
            border-color: #000;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .calendar-nav button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            color: #666;
        }

        .calendar-nav span {
            margin: 0 5px;
        }

        .back-to-dates {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px;
        }

        .back-to-dates:hover {
            color: #000;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        @media (max-width: 600px) {
            .close-modal {
                top: 20px;
                right: 20px;
            }
        }

        .close-modal:hover {
            color: #000;
        }

        .section-nav {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 0;
            width: 80%;
            margin-left: auto;
        }

        .nav-btn {
            padding: 10px 24px;
            background: #000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
            min-width: 120px;
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .nav-btn:not(:disabled):hover {
            background: #333;
        }

        .calendar-section .calendar-grid {
            margin: 15px auto 12px;
            max-width: 320px;
        }

        /* Ensure this doesn't affect other calendar-grid elements */
        .calendar-grid {
            max-width: 320px;
        }

        .calendar-section .section-nav {
            margin-top: 20px;
            margin-bottom: 0;
        }

        .time-slot.disabled {
            background-color: #f5f5f5;
            color: #999;
            cursor: not-allowed;
            border-color: #ddd;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="booking-details-container">
        <div class="booking-header">
            <button class="back-button" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="booking-header-text">
                <h1>Резервация на час</h1>
                <p>Моля, попълнете вашите данни</p>
            </div>
        </div>

        <form id="bookingForm" action="save_appointment.php" method="POST">
            <div class="section-content">
                <h2 class="section-title">Избрани услуги</h2>
                <div id="selectedServices">
                    <!-- Services will be populated by JavaScript -->
                </div>
            </div>

            <div class="section-content">
                <h2 class="section-title">Изберете дата и час</h2>
                <div class="booking-detail" id="dateTimeSelector">
                    <div class="detail-left">
                        <i class="fas fa-calendar detail-icon"></i>
                        <div class="detail-text" id="selectedDateTime">
                            Изберете дата и час
                        </div>
                    </div>
                    <i class="fas fa-chevron-right detail-icon"></i>
                </div>
            </div>

            <div class="section-content">
                <h2 class="section-title">Вашите данни</h2>
                <div class="form-group">
                    <label for="client_name">Име<span class="required">*</span></label>
                    <input type="text" id="client_name" name="client_name" required>
                    <div class="error-message">Моля, въведете вашето име</div>
                </div>

                <div class="form-group">
                    <label for="phone">Телефон<span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" required>
                    <div class="error-message">Моля, въведете валиден телефонен номер</div>
                </div>

                <div class="form-group">
                    <label for="email">Имейл<span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                    <div class="error-message">Моля, въведете валиден имейл адрес</div>
                </div>

                <div class="form-group">
                    <label for="comment">Коментар</label>
                    <textarea id="comment" name="comment"></textarea>
                </div>
            </div>

            <div class="privacy-checkbox">
                <label>
                    <input type="checkbox" id="privacyCheckbox" required>
                    Съгласен съм с <a href="#">условията за ползване</a> и <a href="#">политиката за поверителност</a>
                </label>
            </div>

            <div class="total-section">
                <div class="total-text">Обща сума:</div>
                <div class="total-price">0 лв.</div>
            </div>

            <input type="hidden" id="service" name="service">
            <button type="submit" class="book-btn" id="bookButton" disabled>Резервирай</button>
        </form>
    </div>

    <div class="modal-overlay" id="modalOverlay"></div>
    <div id="calendarModal" class="calendar-modal">
        <div class="calendar-sections">
            <!-- Date Selection Section -->
            <div class="calendar-section">
                <div class="section-header">
                    <h3 class="section-title">Изберете дата</h3>
                    <div class="calendar-nav">
                        <button class="prev-month"><i class="fas fa-chevron-left"></i></button>
                        <span id="currentMonth"></span>
                        <span id="currentYear"></span>
                        <button class="next-month"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
                <div class="calendar-grid">
                    <div class="weekdays">
                        <div>Пн</div>
                        <div>Вт</div>
                        <div>Ср</div>
                        <div>Чт</div>
                        <div>Пт</div>
                        <div>Сб</div>
                        <div>Нд</div>
                    </div>
                    <div id="calendarDays" class="calendar-days"></div>
            </div>
                <div class="section-nav">
                    <button id="continueToHours" class="nav-btn" disabled>Продължи</button>
        </div>
            </div>
            
            <!-- Time Selection Section -->
            <div class="calendar-section">
                <div class="section-header">
                    <h3 class="section-title">Изберете час</h3>
                    <button class="back-to-dates"><i class="fas fa-arrow-left"></i> Назад</button>
                </div>
                <div class="time-slots" id="timeSlots">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
                <div class="section-nav">
                    <button id="confirmDateTime" class="nav-btn" disabled>Потвърди</button>
            </div>
            </div>
        </div>
        <button class="close-modal"><i class="fas fa-times"></i></button>
    </div>

    <script src="js/booking-details.js"></script>
</body>
</html>
