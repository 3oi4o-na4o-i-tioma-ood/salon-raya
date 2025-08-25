<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: sign-in.php');
    exit();
}

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    die("Connection failed");
}
$conn->set_charset("utf8mb4");

// Get appointments for selected date
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_date = ? ORDER BY appointment_time");
$stmt->bind_param("s", $selectedDate);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Работен Панел - Салон 
Райа
</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="canonical" href="https://salonraia.eu/worker-dashboard.php">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="dashboard-body">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <a href="/">
                <img src="images/logo.svg" alt="SALON RAYA LOGO" class="dashboard-logo">
            </a>
            <div class="dashboard-title">Работен панел</div>
            <div class="header-actions">
                <a href="google-auth.php" class="google-cal-button">
                    <i class="fab fa-google"></i> Свързване с Календар
                </a>
                <a href="sign-in.php?logout=true" class="exit-button">
                    <i class="fas fa-sign-out-alt"></i> Изход
                </a>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Navigation Tabs -->
            <div class="dashboard-tabs">
                <button class="tab-button active" data-tab="bookings">
                    <i class="fas fa-calendar"></i> Резервации
                </button>
                <button class="tab-button" data-tab="reviews">
                    <i class="fas fa-star"></i> Добави отзив в страницата
                </button>
                <button class="tab-button" data-tab="trash">
                    <i class="fas fa-trash"></i> Кошче
                </button>
                <button class="tab-button" data-tab="prices">
                    <i class="fas fa-euro-sign"></i> Цени
                </button>
            </div>

            <div class="bookings-section tab-content active" id="bookings-tab">
                <div class="bookings-content">
                    <div class="date-picker">
                        <div id="calendar"></div>
                    </div>
                    <div class="appointments-section">
                        <div class="current-date">
                            <?php echo date('d.m.Y', strtotime($selectedDate)); ?>
                        </div>
                        <div class="appointments-list">
                            <?php if (count($appointments) > 0): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <div
                                        class="appointment-card <?php echo isset($appointment['status']) && $appointment['status'] === 'cancelled' ? 'cancelled' : ''; ?>">
                                        <div class="appointment-header">
                                            <span
                                                class="appointment-time"><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?></span>
                                            <?php if (isset($appointment['status']) && $appointment['status'] === 'cancelled'): ?>
                                                <span class="status-badge cancelled">Отменена</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="appointment-details">
                                            <h3 class="client-name"><?php echo htmlspecialchars($appointment['client_name']); ?>
                                            </h3>
                                            <p class="service"><?php echo htmlspecialchars($appointment['service']); ?></p>
                                            <p class="contact">
                                                <span class="contact-item">
                                                    <i class="fas fa-phone"></i> <a
                                                        href="tel:<?php echo htmlspecialchars($appointment['phone']); ?>"><?php echo htmlspecialchars($appointment['phone']); ?></a>
                                                </span>
                                                <span class="contact-item">
                                                    <i class="fas fa-envelope"></i> <a
                                                        href="mailto:<?php echo htmlspecialchars($appointment['email']); ?>"><?php echo htmlspecialchars($appointment['email']); ?></a>
                                                </span>
                                            </p>
                                            <?php if (!empty($appointment['comment'])): ?>
                                                <p class="notes"><i class="fas fa-sticky-note"></i>
                                                    <?php echo htmlspecialchars($appointment['comment']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-appointments">
                                    <i class="far fa-calendar-times"></i>
                                    <p>Няма резервации за избраната дата</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Management Section -->
            <div class="reviews-section tab-content" id="reviews-tab">
                <div class="reviews-header">
                    <h2>Управление на отзиви</h2>
                </div>

                <div class="reviews-content">
                    <div class="stored-reviews">
                        <h3>Чакащи одобрение</h3>
                        <div class="reviews-list" id="storedReviews">
                            <!-- Stored reviews will be loaded here -->
                        </div>
                    </div>

                    <div class="main-page-reviews">
                        <h3>Отзиви на главната страница</h3>
                        <div class="reviews-list" id="mainPageReviews">
                            <!-- Main page reviews will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trash Tab Content -->
        <div class="tab-content" id="trashContent">
            <div class="trash-section">
                <div class="trash-header">
                    <h2>Кошче</h2>
                    <small>Изтрити отзиви, които могат да бъдат възстановени</small>
                </div>
                <div class="trash-content">
                    <div class="trash-reviews">
                        <div class="reviews-list" id="trashReviews">
                            <!-- Trash reviews will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prices Tab Content -->
        <div class="tab-content" id="pricesContent">
            <div class="prices-section">
                <div class="prices-header"></div>
                <div class="prices-content">
                    <div class="price-list-container">
                        <div class="price-list-card single-card">
                            <div class="card-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="card-content">
                                <div class="card-actions">
                                    <a href="generate-xlsx-price-list.php" class="excel-main-btn" target="_blank">
                                        <i class="fas fa-download"></i>
                                        Изтегли ценова листа (XLSX)
                                    </a>
                                    <button class="sync-btn" onclick="openSyncModal()">
                                        <i class="fas fa-upload"></i>
                                        Качи файл с промени
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Calendar functionality
            flatpickr("#calendar", {
                inline: true,
                defaultDate: "<?php echo $selectedDate; ?>",
                dateFormat: "Y-m-d",
                locale: "bg",
                minDate: "today",
                onChange: function (selectedDates, dateStr) {
                    window.location.href = 'worker-dashboard.php?date=' + dateStr;
                }
            });

            // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetTab = button.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    button.classList.add('active');
                    
                    // Show corresponding content
                    if (targetTab === 'bookings') {
                        document.getElementById('bookings-tab').classList.add('active');
                    } else if (targetTab === 'reviews') {
                        document.getElementById('reviews-tab').classList.add('active');
                        loadReviews();
                    } else if (targetTab === 'trash') {
                        document.getElementById('trashContent').classList.add('active');
                        loadTrashReviews();
                    } else if (targetTab === 'prices') {
                        document.getElementById('pricesContent').classList.add('active');
                    }
                });
            });



            // Load reviews function
            function loadReviews() {
                fetch('manage-reviews.php?action=get_reviews')
                    .then(response => response.json())
                    .then(data => {
                        displayReviews(data.stored, 'storedReviews');
                        displayReviews(data.mainPage, 'mainPageReviews');
                    })
                    .catch(error => {
                        console.error('Error loading reviews:', error);
                    });
            }

            // Display reviews function
            function displayReviews(reviews, containerId) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                if (reviews.length === 0) {
                    container.innerHTML = '<p class="no-reviews">Няма отзиви</p>';
                    return;
                }

                reviews.forEach(review => {
                    const reviewCard = document.createElement('div');
                    reviewCard.className = 'review-card';
                    reviewCard.innerHTML = `
                        <div class="review-header">
                            <div class="client-info">
                                <div class="client-avatar" style="background-color: ${review.background_color}">
                                    ${review.client_initial || review.client_name.charAt(0)}
                                </div>
                                <div class="client-details">
                                    <h4>${review.client_name}</h4>
                                    <div class="stars">${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</div>
                                </div>
                            </div>
                            <div class="review-actions">
                                ${containerId === 'storedReviews' ? 
                                    `<button class="add-to-main-btn" onclick="addToMainPage(${review.id})">
                                        <i class="fas fa-plus"></i> Добави
                                    </button>` : 
                                    `<button class="added-btn" disabled>
                                        <i class="fas fa-check"></i> Добавено
                                    </button>`
                                }
                                <button class="delete-btn" onclick="deleteReview(${review.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="review-text">${review.review_text}</p>
                        <div class="review-meta">
                            <small>Добавен: ${new Date(review.created_at).toLocaleDateString('bg-BG')}</small>
                            ${review.google_link ? `<a href="${review.google_link}" target="_blank" class="google-link">
                                <i class="fab fa-google"></i> Google отзив
                            </a>` : ''}
                        </div>
                    `;
                    container.appendChild(reviewCard);
                });
            }

            // Global functions for review actions
            window.addToMainPage = function(reviewId) {
                fetch('manage-reviews.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=add_to_main_page&review_id=${reviewId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadReviews();
                        alert('Отзивът е добавен на главната страница!');
                    } else {
                        alert('Грешка: ' + data.message);
                    }
                });
            };



            window.deleteReview = function(reviewId) {
                if (confirm('Сигурни ли сте, че искате да преместите този отзив в кошчето?')) {
                    fetch('manage-reviews.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `action=delete&review_id=${reviewId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadReviews();
                            alert('Отзивът е преместен в кошчето!');
                        } else {
                            alert('Грешка: ' + data.message);
                        }
                    });
                }
            };

            // Load trash reviews function
            function loadTrashReviews() {
                fetch('manage-reviews.php?action=get_trash')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayTrashReviews(data.trash);
                        } else {
                            alert('Грешка при зареждането на кошчето: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Възникна грешка при зареждането на кошчето.');
                    });
            }

            function displayTrashReviews(reviews) {
                const container = document.getElementById('trashReviews');
                container.innerHTML = '';

                if (reviews.length === 0) {
                    container.innerHTML = '<div class="no-reviews">Кошчето е празно.</div>';
                    return;
                }

                reviews.forEach(review => {
                    const reviewCard = document.createElement('div');
                    reviewCard.className = 'review-card trash-review-card';
                    
                    reviewCard.innerHTML = `
                        <div class="review-header">
                            <div class="user-info">
                                <div class="user-icon" style="background-color: ${review.background_color};">${review.client_initial}</div>
                                <div class="user-details">
                                    <h4>${review.client_name}</h4>
                                    <div class="stars">
                                        ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                                    </div>
                                </div>
                            </div>
                            <div class="review-actions">
                                <button class="restore-btn" onclick="restoreReview(${review.id})">
                                    <i class="fas fa-undo"></i> Възстанови
                                </button>
                                <button class="permanent-delete-btn" onclick="permanentDeleteReview(${review.id})">
                                    <i class="fas fa-trash-alt"></i> Изтрий завинаги
                                </button>
                            </div>
                        </div>
                        <p class="review-text">${review.review_text}</p>
                        <div class="review-meta">
                            <small>Изтрит: ${new Date(review.deleted_at).toLocaleDateString('bg-BG')}</small>
                            ${review.google_link ? `<a href="${review.google_link}" target="_blank" class="google-link">
                                <i class="fab fa-google"></i> Google отзив
                            </a>` : ''}
                        </div>
                    `;
                    container.appendChild(reviewCard);
                });
            }

            window.restoreReview = function(reviewId) {
                if (confirm('Искате ли да възстановите този отзив?')) {
                    fetch('manage-reviews.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `action=restore&review_id=${reviewId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadTrashReviews();
                            alert('Отзивът е възстановен!');
                        } else {
                            alert('Грешка: ' + data.message);
                        }
                    });
                }
            };

            window.permanentDeleteReview = function(reviewId) {
                if (confirm('ВНИМАНИЕ: Това ще изтрие отзива завинаги! Сигурни ли сте?')) {
                    fetch('manage-reviews.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `action=permanent_delete&review_id=${reviewId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadTrashReviews();
                            alert('Отзивът е изтрит завинаги!');
                        } else {
                            alert('Грешка: ' + data.message);
                        }
                    });
                }
            };

            // Sync Modal Functions
            window.openSyncModal = function() {
                document.getElementById('syncModal').classList.remove('hidden');
            };

            window.closeSyncModal = function() {
                document.getElementById('syncModal').classList.add('hidden');
                // Reset form
                document.getElementById('excelFileInput').value = '';
                document.querySelector('.sync-upload-btn').disabled = true;
                document.getElementById('uploadStatus').classList.add('hidden');
            };

            // File input change handler
            document.getElementById('excelFileInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFileSelection(file);
                }
            });

            // Drag and drop functionality
            const uploadArea = document.querySelector('.upload-area');
            
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelection(files[0]);
                }
            });

            function handleFileSelection(file) {
                const allowedTypes = [
                    'text/csv'
                ];
                
                if (allowedTypes.includes(file.type) || file.name.match(/\.(csv)$/i)) {
                    document.querySelector('.sync-upload-btn').disabled = false;
                    showUploadStatus('Файлът е избран: ' + file.name, 'success');
                } else {
                    showUploadStatus('Моля, изберете валиден CSV файл (.csv)', 'error');
                }
            }

            function showUploadStatus(message, type) {
                const statusDiv = document.getElementById('uploadStatus');
                statusDiv.textContent = message;
                statusDiv.className = 'upload-status ' + type;
                statusDiv.classList.remove('hidden');
            }

            window.syncPrices = function() {
                const fileInput = document.getElementById('excelFileInput');
                const file = fileInput.files[0];
                
                if (!file) {
                    showUploadStatus('Моля, изберете файл първо', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('excel_file', file);

                showUploadStatus('Качване и синхронизиране...', 'loading');
                document.querySelector('.sync-upload-btn').disabled = true;

                fetch('sync-prices.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showUploadStatus('Цените са успешно синхронизирани!', 'success');
                        setTimeout(() => {
                            closeSyncModal();
                            location.reload(); // Reload to show updated prices
                        }, 2000);
                    } else {
                        showUploadStatus('Грешка: ' + data.message, 'error');
                        document.querySelector('.sync-upload-btn').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showUploadStatus('Възникна грешка при синхронизирането', 'error');
                    document.querySelector('.sync-upload-btn').disabled = false;
                });
            };

        });
    </script>

    <style>
        .exit-button {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #ff0000;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: 500;
        }

        .exit-button:hover {
            background-color: #fff0f0;
        }

        .google-cal-button {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #4285F4;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: 500;
        }

        .google-cal-button:hover {
            background-color: #e8f0fe;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        /* Dashboard Tabs */
        .dashboard-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .tab-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            color: #666;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            color: #a484e8;
        }

        .tab-button.active {
            color: #a484e8;
            border-bottom-color: #a484e8;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block !important;
        }

        /* Reviews Management Styles */
        .reviews-section {
            padding: 1rem;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .reviews-header h2 {
            margin: 0;
            color: #333;
        }



        .reviews-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .stored-reviews, .main-page-reviews {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stored-reviews h3, .main-page-reviews h3 {
            margin: 0 0 1rem 0;
            color: #333;
            font-size: 1.2rem;
        }

        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .review-card {
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            padding: 1rem;
            background: #f8f9fa;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .client-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .client-details h4 {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        .stars {
            color: #ffd700;
            font-size: 0.9rem;
        }

        .review-actions {
            display: flex;
            gap: 0.5rem;
        }

        .add-to-main-btn, .added-btn, .delete-btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .add-to-main-btn {
            background: #28a745;
            color: white;
        }

        .add-to-main-btn:hover {
            background: #218838;
        }

        .added-btn {
            background: #6c757d;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .added-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .review-text {
            margin: 0 0 0.75rem 0;
            line-height: 1.5;
            color: #555;
        }

        .review-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #666;
        }

        .google-link {
            color: #4285f4;
            text-decoration: none;
        }

        .google-link:hover {
            text-decoration: underline;
        }

        .no-reviews {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 2rem;
        }

        /* Trash Section Styles */
        .trash-section {
            padding: 1rem;
        }

        .trash-header {
            margin-bottom: 2rem;
        }

        .trash-header h2 {
            margin: 0 0 0.5rem 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .trash-header small {
            color: #999;
            font-style: italic;
        }

        /* Prices Section Styles */
        .prices-section {
            padding: 1rem;
        }

        .prices-header {
            margin-bottom: 2rem;
        }

        .prices-header h2 {
            margin: 0 0 0.5rem 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .prices-header small {
            color: #666;
            font-style: italic;
        }

        .prices-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .price-list-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .price-list-card {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: box-shadow 0.3s ease;
        }

        .price-list-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .card-icon {
            margin-bottom: 1rem;
        }

        .card-icon i {
            font-size: 3rem;
            color: #28a745;
        }

        .card-content h3 {
            margin: 0 0 1rem 0;
            color: #333;
            font-size: 1.5rem;
        }

        .card-content p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .card-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .excel-main-btn, .excel-download-btn, .sync-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            width: 100%;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .excel-main-btn {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(164, 132, 232, 0.3);
        }

        .excel-main-btn:hover {
            background: linear-gradient(135deg, var(--accent-dark), #7c5cc8);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(164, 132, 232, 0.4);
        }

        .excel-download-btn {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(164, 132, 232, 0.3);
        }

        .excel-download-btn:hover {
            background: linear-gradient(135deg, var(--accent-dark), #7c5cc8);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(164, 132, 232, 0.4);
        }

        .sync-btn {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(164, 132, 232, 0.3);
        }

        .sync-btn:hover {
            background: linear-gradient(135deg, var(--accent-dark), #7c5cc8);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(164, 132, 232, 0.4);
        }

        .single-card {
            text-align: center;
        }

        .single-card .card-icon i {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        .single-card .card-content h3 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .single-card .card-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: #666;
        }

        .price-summary {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .price-summary h4 {
            margin: 0 0 1rem 0;
            color: #333;
            text-align: center;
        }

        .summary-stats {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e1e5e9;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            font-weight: 500;
            color: #555;
        }

        .stat-value {
            color: #a484e8;
            font-weight: 600;
        }

        .trash-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .trash-review-card {
            border-left: 4px solid #dc3545;
            background: #fff8f8;
        }

        .restore-btn, .permanent-delete-btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .restore-btn {
            background: var(--accent-color);
            color: white;
        }

        .restore-btn:hover {
            background: var(--accent-dark);
        }

        .permanent-delete-btn {
            background: #5a2b66;
            color: white;
        }

        .permanent-delete-btn:hover {
            background: #4b2456;
        }

        .trash-reviews .review-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }



        /* Responsive Design */
        @media (max-width: 768px) {
            .reviews-content {
                grid-template-columns: 1fr;
            }

            .price-list-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .card-actions {
                flex-direction: column;
                align-items: center;
            }

            .excel-main-btn, .excel-download-btn, .sync-btn {
                width: 100%;
                justify-content: center;
                font-size: 1rem;
                padding: 0.875rem 1.5rem;
            }

            .single-card .card-icon i {
                font-size: 3rem;
            }

            .single-card .card-content h3 {
                font-size: 1.5rem;
            }

            .single-card .card-content p {
                font-size: 1rem;
            }

            .dashboard-tabs {
                overflow-x: auto;
            }

            .tab-button {
                white-space: nowrap;
            }
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            flex-wrap: wrap;
            gap: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .salon-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dashboard-logo {
            height: 40px;
            width: auto;
            margin-bottom: 0;
        }

        .salon-details h1 {
            font-size: 18px;
            margin: 0;
        }

        .salon-details p {
            font-size: 14px;
            margin: 2px 0 0 0;
        }

        .bookings-content {
            display: flex;
            gap: 20px;
        }

        @media (min-width: 600px) {
            .bookings-section {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 850px) {
            .bookings-content {
                flex-direction: column;
            }
        }

        .appointment-card {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            background: #f8f8f8;
            border-radius: 8px;
            border-left: 4px solid #a484e8;
        }

        @media (max-width: 600px) {
            .appointment-card {
                flex-direction: column;
                gap: 1rem;
            }
        }

        .appointments-section {
            flex-grow: 1;
        }

        .current-date {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
            padding: 8px 12px;
            background: #f5f5f5;
            border-radius: 4px;
            display: inline-block;
        }

        .appointments-list {
            width: 100%;
        }

        .appointment-card {
            padding: 12px;
            margin-bottom: 10px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .appointment-time {
            font-size: 14px;
            padding: 6px 10px;
            background: #f5f5f5;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .appointment-details h3 {
            font-size: 15px;
            margin: 0 0 5px 0;
        }

        .appointment-details p {
            font-size: 14px;
            margin: 3px 0;
        }

        @media (max-width: 600px) {
            .appointment-details .contact {
                flex-direction: column;
                align-items: flex-start;
                gap: 0;
            }
        }

        .no-appointments {
            padding: 15px;
            text-align: center;
            background: #fff;
            border-radius: 4px;
        }

        .no-appointments i {
            font-size: 24px;
            margin-bottom: 8px;
            color: #999;
        }

        .no-appointments p {
            font-size: 14px;
            margin: 0;
            color: #666;
        }

        .date-picker {
            max-width: 308px;
            flex-shrink: 0;
        }

        @media (max-width: 600px) {
            .date-picker {
                margin: auto;
            }
        }

        .date-picker .dayContainer {
            width: 100%;
            min-width: 0;
        }

        .flatpickr-current-month {
            display: flex;
        }

        .flatpickr-calendar {
            width: 100%;
            font-size: 14px;
            margin: 0 auto;
        }

        .flatpickr-day {
            height: auto;
            aspect-ratio: 1;
            line-height: normal;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .date-picker .flatpickr-days {
            width: 100%;
        }

        /* Update today's styling */
        .flatpickr-day.today {
            border: none;
            background: none;
        }

        .flatpickr-day.today.selected {
            background: #0366d6;
            color: #fff;
            border-color: #0366d6;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #0366d6;
            color: #fff;
            border-color: #0366d6;
        }

        .flatpickr-day.today:hover {
            background: #e6e6e6;
        }

        .flatpickr-day.today.selected:hover {
            background: #0366d6;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay,
        .flatpickr-day.flatpickr-disabled {
            color: #999 !important;
            background: none !important;
            cursor: default !important;
        }

        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover,
        .flatpickr-day.flatpickr-disabled:hover {
            background: none !important;
        }

        /* Style for past days */
        .flatpickr-day.flatpickr-disabled {
            text-decoration: none;
            color: #999 !important;
        }

        .new-reservation-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .indicator-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .indicator-content i {
            font-size: 20px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            text-align: center;
            max-width: 90%;
            width: 400px;
        }

        .notification-popup h3 {
            margin: 0 0 15px 0;
            color: #333;
        }

        .notification-popup p {
            margin: 0 0 20px 0;
            color: #666;
            line-height: 1.5;
        }

        .notification-popup button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .notification-popup button:hover {
            background: #45a049;
        }

        .notification-popup.hidden {
            display: none;
        }

        /* Sync Modal Styles */
        .sync-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .sync-modal.hidden {
            display: none;
        }

        .sync-modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .sync-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .sync-modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .close-modal:hover {
            background-color: #f0f0f0;
        }

        .sync-modal-body {
            padding: 1.5rem;
        }

        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin: 1rem 0;
            transition: border-color 0.3s;
        }

        .upload-area:hover {
            border-color: #a484e8;
        }

        .upload-area.dragover {
            border-color: #a484e8;
            background-color: #f8f6ff;
        }

        .upload-icon {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .upload-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-btn:hover {
            background: #0056b3;
        }

        .sync-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .cancel-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cancel-btn:hover {
            background: #5a6268;
        }

        .sync-upload-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sync-upload-btn:hover:not(:disabled) {
            background: #218838;
        }

        .sync-upload-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .upload-status {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
        }

        .upload-status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .upload-status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .upload-status.loading {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }



        .appointment-card.cancelled {
            background-color: #ffebee;
            border-left: 4px solid #e74c3c;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-left: 10px;
            color: white;
        }

        .status-badge.cancelled {
            background-color: #e74c3c;
        }

        .status-badge.pending {
            background-color: #f39c12;
        }

        .status-badge.confirmed {
            background-color: #2ecc71;
        }

        .contact i {
            margin-right: 6px;
            color: #666;
        }

        .contact a {
            color: inherit;
            text-decoration: none;
        }

        .contact-item {
            display: block;
            margin-bottom: 5px;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 600px) {
            .dashboard-header {
                justify-content: center;
                padding: 10px;
            }
            .dashboard-title {
                order: -1;
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div id="notificationPopup" class="notification-popup hidden">
        <h3>Разрешете Известията</h3>
        <p>Натиснете бутона по-долу, за да разрешите известия за нови резервации.</p>
        <button id="enableNotifications">Разреши известия</button>
    </div>

    <!-- Sync Modal -->
    <div id="syncModal" class="sync-modal hidden">
        <div class="sync-modal-content">
            <div class="sync-modal-header">
                <h3>Синхронизирай промени от CSV</h3>
                <button class="close-modal" onclick="closeSyncModal()">&times;</button>
            </div>
            <div class="sync-modal-body">
                <p>Качете актуализирания CSV файл за да синхронизирате промените в услугите и цените на сайта.</p>
                <div class="upload-area">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p>Плъзнете и пуснете CSV файла тук или</p>
                    <input type="file" id="excelFileInput" accept=".csv" style="display: none;">
                    <button class="upload-btn" onclick="document.getElementById('excelFileInput').click();">
                        <i class="fas fa-folder-open"></i>
                        Изберете файл
                    </button>
                </div>
                <div id="uploadStatus" class="upload-status hidden"></div>
            </div>
            <div class="sync-modal-footer">
                <button class="cancel-btn" onclick="closeSyncModal()">Отказ</button>
                <button class="sync-upload-btn" onclick="syncPrices()" disabled>
                    <i class="fas fa-sync"></i>
                    Синхронизирай
                </button>
            </div>
        </div>
    </div>



    <script>
        let audioPermissionGranted = false;
        let audioContext;
        let audioElement;
        let serviceWorkerRegistration = null;
        let pushSubscription = null;
        let userHasInteracted = false;

        // Check if user has notification permission (only consider 'granted' as valid)
        const hasNotificationPermission = 'Notification' in window && Notification.permission === 'granted';

        // Clear interaction memory if notifications are denied
        if (Notification.permission === 'denied') {
            sessionStorage.removeItem('userInteracted');
        }

        // Function to show notification popup if needed
        function showNotificationPopupIfNeeded() {
            if (!hasNotificationPermission) {
                document.getElementById('notificationPopup').classList.remove('hidden');
            } else if (Notification.permission === 'granted') {
                // If permission already granted, initialize notifications without showing popup
                initializePushNotifications();
            }
        }

        // Check for existing interaction in session storage - only valid if notifications are granted
        if (sessionStorage.getItem('userInteracted') === 'true' && hasNotificationPermission) {
            userHasInteracted = true;

            if (Notification.permission === 'granted') {
                initializePushNotifications();
            }
        } else {
            // Show popup after a short delay (to let the page load)
            setTimeout(showNotificationPopupIfNeeded, 500);
        }

        // Detect any user interaction with the page
        function markUserInteraction(event) {
            // Don't count clicks on the document as interaction if the notification popup is visible
            // This ensures users must explicitly interact with the permission button
            const notificationPopup = document.getElementById('notificationPopup');
            if (!notificationPopup.classList.contains('hidden') &&
                !notificationPopup.contains(event.target)) {
                // If clicked outside the popup while it's visible, don't count as interaction
                return;
            }

            if (!userHasInteracted) {
                userHasInteracted = true;

                // Only remember interaction if notifications are granted
                if (Notification.permission === 'granted') {
                    sessionStorage.setItem('userInteracted', 'true');
                }

                // Only hide popup if notifications are granted
                // Otherwise it should stay visible until they explicitly interact with the permission button
                if (Notification.permission === 'granted') {
                    document.getElementById('notificationPopup').classList.add('hidden');
                }

                // Initialize audio on first interaction
                initializeAudio();
            }
        }

        // Add interaction event listeners - only for deliberate actions
        ['click', 'touchstart', 'keydown'].forEach(eventType => {
            document.addEventListener(eventType, markUserInteraction, { once: true });
        });

        // Handle notification permission button click
        document.getElementById('enableNotifications').addEventListener('click', function () {
            if ('Notification' in window) {
                Notification.requestPermission().then(function (permission) {
                    if (permission === 'granted') {
                        console.log('Notification permission granted');
                        document.getElementById('notificationPopup').classList.add('hidden');
                        sessionStorage.setItem('userInteracted', 'true');
                        initializeAudio();
                        initializePushNotifications();
                    } else if (permission === 'denied') {
                        // Clear interaction if user explicitly denies
                        sessionStorage.removeItem('userInteracted');
                    }
                });
            }
        });

        // Initialize push notifications
        async function initializePushNotifications() {
            try {
                // Register service worker
                serviceWorkerRegistration = await navigator.serviceWorker.register('/notification-worker.js');

                // Request push subscription
                pushSubscription = await serviceWorkerRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: 'BG5UHBERE8s7_dbqGPohOTitg5VbEpC4CWdanwIL0g5AXl_1MjkEPIDmEwF4UnCSEzGiPJ7moFWKjEGzLehH-EM'
                });

                // Send subscription to server
                const response = await fetch('save_push_subscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(pushSubscription)
                });
                await response.json();
            } catch (error) {
                console.error('Push notification setup failed:', error);
            }
        }

        // Initialize audio on user interaction
        function initializeAudio() {
            if (!audioElement) {
                audioElement = new Audio('sounds/notification.mp3');
                audioElement.load();
            }
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
            audioPermissionGranted = true;
        }

        // Function to show notification
        function showNotification(reservation) {
            const message = `${reservation.client_name} резервира ${reservation.service} за ${new Date(reservation.appointment_date).toLocaleDateString('bg-BG')} в ${reservation.appointment_time}`;

            if (serviceWorkerRegistration) {
                serviceWorkerRegistration.showNotification('Нова Резервация!', {
                    body: message
                });
            }
        }
    </script>
</body>

</html>