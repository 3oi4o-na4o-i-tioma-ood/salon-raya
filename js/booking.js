document.addEventListener('DOMContentLoaded', function() {
    // Clear all stored data when page loads
    sessionStorage.clear();
    localStorage.clear();
    
    const heroSections = document.querySelectorAll('.hero-section');
    const subcategoriesLists = document.querySelectorAll('.subcategories-list');
    const servicesList = document.querySelector('.services-list');
    const selectedCountElement = document.querySelector('.selected-count span');
    const totalDurationElement = document.querySelector('.total-duration span');
    const totalPriceElement = document.querySelector('.total-price span');
    const bookingSummary = document.querySelector('.booking-summary');
    const totalPriceButton = document.querySelector('.total-price');
    let selectedServices = [];
    let totalPrice = 0;
    let totalDuration = 0;

    // Reset button text on page load
    totalPriceButton.innerHTML = 'Избери час <span>0 лв.</span>';

    // Function to update summary
    function updateSummary() {
        selectedCountElement.textContent = selectedServices.length;
        totalDurationElement.textContent = totalDuration;
        totalPriceElement.textContent = totalPrice.toFixed(0) + ' лв.';
        // Update button text based on whether services are selected
        totalPriceButton.innerHTML = selectedServices.length > 0 ? 
            `Продължи <span>${totalPrice.toFixed(0)} лв.</span>` : 
            'Избери час <span>0 лв.</span>';
    }

    // Function to add selected service
    function addSelectedService(serviceName, servicePrice, serviceDuration) {
        selectedServices.push({
            name: serviceName,
            price: parseFloat(servicePrice),
            duration: serviceDuration
        });
        totalPrice += parseFloat(servicePrice);
        totalDuration += parseInt(serviceDuration) || 0;
        updateSummary();
        bookingSummary.style.display = 'block';
    }

    // Handle hero section clicks
    heroSections.forEach(section => {
        section.addEventListener('click', () => {
            heroSections.forEach(s => s.classList.remove('active'));
            section.classList.add('active');

            const category = section.getAttribute('data-category');
            subcategoriesLists.forEach(list => {
                if (list.getAttribute('data-category') === category) {
                    list.classList.add('active');
                    const firstSubcategory = list.querySelector('.subcategory');
                    if (firstSubcategory) {
                        firstSubcategory.click();
                    }
                } else {
                    list.classList.remove('active');
                }
            });
        });
    });

    // Handle subcategory clicks
    document.querySelectorAll('.subcategory').forEach(subcategory => {
        subcategory.addEventListener('click', () => {
            document.querySelectorAll('.subcategory').forEach(s => s.classList.remove('active'));
            subcategory.classList.add('active');

            const services = subcategory.getAttribute('data-services');
            if (services) {
                const servicesData = JSON.parse(services);
                servicesList.innerHTML = servicesData.map(service => {
                    let optionsHtml = '';
                    if (service.options) {
                        optionsHtml = `
                            <div class="service-options">
                                ${service.options.map(option => `
                                    <div class="service-option">
                                        <div class="option-info">
                                            <span class="option-name">${option.name}</span>
                                            <span class="option-duration">${option.duration} мин.</span>
                                        </div>
                                        <div class="option-price">
                                            <span class="price">${option.price} лв.</span>
                                            <button class="select-btn" 
                                                data-name="${service.name} (${option.name})" 
                                                data-price="${option.price}" 
                                                data-duration="${option.duration}">
                                                избери
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                    
                    return `
                        <div class="service-item">
                            <div class="service-item-content">
                                <div class="service-main-content">
                                    <div class="service-info">
                                        <h3>${service.name}</h3>
                                        <p>${service.description || ''}</p>
                                        <span class="service-duration">${service.duration || ''} мин.</span>
                                    </div>
                                    <div class="service-price-container">
                                        ${service.options ? 
                                            `<button class="options-btn">опции ▼</button>` :
                                            `<div class="service-price">
                                                <span class="price">${service.price} лв.</span>
                                                <button class="select-btn" 
                                                    data-name="${service.name}" 
                                                    data-price="${service.price}" 
                                                    data-duration="${service.duration}">
                                                    избери
                                                </button>
                                            </div>`
                                        }
                                    </div>
                                </div>
                                ${optionsHtml}
                            </div>
                        </div>
                    `;
                }).join('');

                // Add click handlers for select buttons
                document.querySelectorAll('.select-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const serviceName = btn.getAttribute('data-name');
                        const servicePrice = btn.getAttribute('data-price');
                        const serviceDuration = btn.getAttribute('data-duration');
                        addSelectedService(serviceName, servicePrice, serviceDuration);
                    });
                });

                // Add click handlers for options buttons
                document.querySelectorAll('.options-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const optionsDiv = btn.closest('.service-item').querySelector('.service-options');
                        if (optionsDiv) {
                            optionsDiv.classList.toggle('show');
                            btn.textContent = optionsDiv.classList.contains('show') ? 'опции ▲' : 'опции ▼';
                        }
                    });
                });
            }
        });
    });

    // Handle continue booking click
    document.querySelector('.total-price').addEventListener('click', () => {
        if (selectedServices.length > 0) {
            sessionStorage.setItem('selectedServices', JSON.stringify(selectedServices));
            window.location.href = 'booking-details.php';
        }
    });

    // Click the first hero section by default
    if (heroSections.length > 0) {
        heroSections[0].click();
        const firstSubcategory = document.querySelector('.subcategories-list[data-category="hair"] .subcategory');
        if (firstSubcategory) {
            firstSubcategory.click();
        }
    }

    // Get the service parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const selectedService = urlParams.get('service');

    // If a service is selected, show the appropriate category and subcategory
    if (selectedService) {
        // Map the service parameter to the appropriate category and subcategory
        const serviceMap = {
            'haircuts': { category: 'hair', subcategory: 0 },
            'coloring': { category: 'hair', subcategory: 1 },
            'curling': { category: 'hair', subcategory: 2 },
            'extensions': { category: 'hair', subcategory: 3 },
            'treatments': { category: 'hair', subcategory: 4 },
            'beard': { category: 'hair', subcategory: 5 },
            'other': { category: 'hair', subcategory: 6 },
            'makeup': { category: 'face', subcategory: 0 },
            'permanent': { category: 'face', subcategory: 1 },
            'women': { category: 'epilation', subcategory: 0 },
            'men': { category: 'epilation', subcategory: 1 },
            'classic': { category: 'massage', subcategory: 0 },
            'sport': { category: 'massage', subcategory: 1 }
        };

        if (serviceMap[selectedService]) {
            const { category, subcategory } = serviceMap[selectedService];
            
            // Show the appropriate category
            const categoryElement = document.querySelector(`.service-category[data-category="${category}"]`);
            if (categoryElement) {
                categoryElement.click();
                
                // Show the appropriate subcategory
                const subcategoryElement = document.querySelector(`.subcategories-list[data-category="${category}"] .subcategory:nth-child(${subcategory + 1})`);
                if (subcategoryElement) {
                    subcategoryElement.click();
                }
            }
        }
    }
}); 