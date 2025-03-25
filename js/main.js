document.addEventListener('DOMContentLoaded', () => {
    // Get navigation elements
    const navbar = document.querySelector('.navbar');
    const navbarScrolled = document.querySelector('.navbar-scrolled');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const allHamburgers = document.querySelectorAll('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    // Toggle menu when any hamburger is clicked
    allHamburgers.forEach(hamburger => {
        hamburger.addEventListener('click', () => {
            allHamburgers.forEach(h => h.classList.toggle('active'));
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
    });

    // Close menu when a nav link is clicked
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            allHamburgers.forEach(hamburger => hamburger.classList.remove('active'));
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Show/hide scrolled navbar based on subtitle position
    window.addEventListener('scroll', () => {
        const subtitlePosition = heroSubtitle.getBoundingClientRect().top;
        if (subtitlePosition <= 0) {
            navbarScrolled.classList.add('visible');
        } else {
            navbarScrolled.classList.remove('visible');
        }
    });

    // Form validation and date/time restrictions
    if (document.querySelector('.booking-form')) {
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);

        // Set time restrictions (9 AM to 6 PM)
        timeInput.addEventListener('change', () => {
            const selectedTime = timeInput.value;
            const hour = parseInt(selectedTime.split(':')[0]);

            if (hour < 9 || hour >= 18) {
                alert('Please select a time between 9:00 AM and 6:00 PM');
                timeInput.value = '';
            }
        });
    }

    // Handle service category clicks
    const serviceCategories = document.querySelectorAll('.service-category');
    const hairIcon = document.querySelector('.service-category img[alt="Коса"]').parentElement;
    let isFirstLoad = true;

    // Function to simulate click on hair icon
    const clickHairIcon = () => {
        if (isFirstLoad) {
            hairIcon.classList.add('active');
            // Show hair services section
            document.querySelector('.hair-categories').style.display = 'flex';
            document.querySelector('.face-categories').style.display = 'none';
            document.querySelector('.epilation-categories').style.display = 'none';
            document.querySelector('.massage-categories').style.display = 'none';
            document.querySelector('.service-details-list').style.display = 'block';
            document.querySelectorAll('.face-details, .epilation-details, .massage-details').forEach(cat => cat.style.display = 'none');
            document.querySelector('.service-details-category[data-category="haircuts"]').style.display = 'block';
            isFirstLoad = false;
        }
    };

    // Auto-click hair icon on page load
    clickHairIcon();

    serviceCategories.forEach((category, categoryIndex) => {
        category.addEventListener('click', function () {
            // Remove active class from all categories
            serviceCategories.forEach(cat => cat.classList.remove('active'));

            // Add active class to clicked category
            this.classList.add('active');

            const categories = ['hair', 'face', 'epilation', 'massage'];

            for (const categoryClassIndex in categories) {
                const categoryClass = categories[categoryClassIndex] + '-categories';
                const detailsClass = categories[categoryClassIndex] + '-details';

                const isVisible = categoryIndex === Number(categoryClassIndex);

                console.log(categoryClass, detailsClass, isVisible);
                document.querySelector(`.${categoryClass}`).style.display = isVisible ? 'flex' : 'none';
                document.querySelector(`.${detailsClass}`).style.display = isVisible ? 'block' : 'none';
            }

            document.querySelector('.service-details-list').style.display = 'block';
        });
    });

    // Service category switching
    const categoryItems = document.querySelectorAll('.service-category-item');

    categoryItems.forEach(item => {
        item.addEventListener('click', () => {
            const category = item.dataset.category;
            const parentList = item.closest('.service-categories-list');
            const isFaceCategory = parentList.classList.contains('face-categories');
            const isEpilationCategory = parentList.classList.contains('epilation-categories');
            const isMassageCategory = parentList.classList.contains('massage-categories');

            // Update active states for category items in the same section
            parentList.querySelectorAll('.service-category-item').forEach(cat => cat.classList.remove('active'));
            item.classList.add('active');

            // Hide all service details
            document.querySelectorAll('.service-details-category').forEach(details => {
                details.style.display = 'none';
            });

            // Show corresponding details category
            const targetDetails = document.querySelector(`.service-details-category[data-category="${category}"]${isFaceCategory ? '.face-details' : isEpilationCategory ? '.epilation-details' : isMassageCategory ? '.massage-details' : ':not(.face-details):not(.epilation-details):not(.massage-details)'}`)
            if (targetDetails) {
                targetDetails.style.display = 'block';
            }
        });
    });

    // Handle appointment form submission
    const appointmentForm = document.getElementById('appointment-form');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(appointmentForm);

            // Send form data to server
            fetch('save_appointment.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Резервацията е успешно запазена!');
                        appointmentForm.reset();
                    } else {
                        alert('Възникна грешка при запазването на резервацията. Моля, опитайте отново.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Възникна грешка при комуникацията със сървъра. Моля, опитайте отново.');
                });
        });
    }
}); 