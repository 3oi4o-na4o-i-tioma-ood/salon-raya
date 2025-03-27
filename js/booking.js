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

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    const service = urlParams.get('service');
    const detail = urlParams.get('detail');

    // If we have parameters, pre-select the service
    if (category && service) {
        // First, click the main category button (hair, face, epilation, massage)
        const categoryButton = document.querySelector(`.service-category[data-category="${category}"]`);
        if (categoryButton) {
            categoryButton.click();
            
            // Wait for the service categories to load
            setTimeout(() => {
                // Then click the specific service category
                const serviceCategory = document.querySelector(`.service-category-item[data-category="${service}"]`);
                if (serviceCategory) {
                    serviceCategory.click();
                    
                    // Finally, if we have a detail, scroll to it
                    if (detail) {
                        const detailElement = document.querySelector(`[data-detail="${detail}"]`);
                        if (detailElement) {
                            detailElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                }
            }, 100);
        }
    }
}); 