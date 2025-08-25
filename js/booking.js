document.addEventListener('DOMContentLoaded', function() {
    // Clear all stored data when page loads
    sessionStorage.clear();
    localStorage.clear();

    // Function to convert LV to Euro and format dual currency display
    function formatDualCurrency(priceLV) {
        const euroPrice = Math.ceil((priceLV / 1.95583) * 100) / 100; // Round up to cent
        return `${priceLV} лв. / ${euroPrice.toFixed(2)} €`;
    }
    
    const serviceCategories = document.querySelectorAll('.service-category');
    const subcategoriesLists = document.querySelectorAll('.subcategories-list');
    const servicesList = document.querySelector('.services-list');
    const selectedCountElement = document.querySelector('.selected-count span');
    const totalDurationElement = document.querySelector('.total-duration span');
    const totalPriceElement = document.querySelector('.total-price span');
    const bookingSummary = document.querySelector('.booking-summary');
    const totalPriceButton = document.querySelector('.total-price');
    const selectedServicesList = document.querySelector('.selected-services-list');
    const selectedServicesContainer = document.querySelector('.selected-services-container');
    let selectedServices = [];
    let totalPrice = 0;
    let totalDuration = 0;
    // Track selected service names to prevent duplicates
    let selectedServiceNames = new Set();

    // Reset button text on page load
    totalPriceButton.innerHTML = 'Избери час <span>0 лв. / 0.00 €</span>';

    // Function to update summary
    function updateSummary() {
        selectedCountElement.textContent = selectedServices.length;
        totalDurationElement.textContent = totalDuration;
        totalPriceElement.textContent = formatDualCurrency(totalPrice.toFixed(0));
        // Update button text based on whether services are selected
        totalPriceButton.innerHTML = selectedServices.length > 0 ? 
            `Продължи <span>${formatDualCurrency(totalPrice.toFixed(0))}</span>` : 
            'Избери час <span>0 лв. / 0.00 €</span>';
            
        // Update selected services list
        updateSelectedServicesList();
    }
    
    // Function to update the selected services list
    function updateSelectedServicesList() {
        selectedServicesContainer.innerHTML = '';
        
        if (selectedServices.length > 0) {
            selectedServicesList.classList.add('active');
            
            selectedServices.forEach((service, index) => {
                const serviceItem = document.createElement('div');
                serviceItem.className = 'selected-service-item';
                serviceItem.innerHTML = `
                    <div class="selected-service-name">${service.name}</div>
                    <div class="selected-service-price">${formatDualCurrency(service.price.toFixed(0))}</div>
                    <button class="remove-service-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                selectedServicesContainer.appendChild(serviceItem);
            });
            
            // Add event listeners for remove buttons
            document.querySelectorAll('.remove-service-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Remove button clicked!');
                    const index = parseInt(this.getAttribute('data-index'));
                    console.log('Removing service at index:', index);
                    removeService(index);
                });
            });
        } else {
            selectedServicesList.classList.remove('active');
        }
    }

    // Function to add selected service
    function addSelectedService(serviceName, servicePrice, serviceDuration) {
        // Check if service is already selected
        if (selectedServiceNames.has(serviceName)) {
            // Service already selected, show message
            alert('Тази услуга вече е избрана. Можете да изберете всяка услуга само веднъж.');
            return;
        }

        // Get base service name (without options)
        const baseServiceName = serviceName.split(' (')[0];
        
        // Check if another option of the same service is already selected
        let alreadySelected = false;
        selectedServiceNames.forEach(name => {
            if (name.startsWith(baseServiceName + ' (') || name === baseServiceName) {
                alreadySelected = true;
            }
        });
        
        if (alreadySelected) {
            alert('Друга опция на тази услуга вече е избрана. Може да изберете само една опция на услуга.');
            return;
        }

        // Add to selected services - INCLUDING DURATION
        selectedServices.push({
            name: serviceName,
            price: parseFloat(servicePrice),
            duration: serviceDuration // Make sure duration is included here
        });
        selectedServiceNames.add(serviceName);
        totalPrice += parseFloat(servicePrice);
        // Make sure to parse duration correctly here too if needed for summary
        // Handle potential ranges for summary duration display (take max or average? Let's take max for consistency)
        let durationToAdd = 0;
        if (typeof serviceDuration === 'string' && serviceDuration.includes('-')) {
            const parts = serviceDuration.split('-').map(Number);
            durationToAdd = Math.max(...parts);
        } else {
            durationToAdd = parseInt(serviceDuration) || 0; 
        }
        totalDuration += durationToAdd; 
        
        updateSummary();
        bookingSummary.style.display = 'block';
        
        // Disable all buttons for this service
        disableServiceButtons(serviceName);

        // Refresh the current view to reflect changes
        const activeSubcategory = document.querySelector('.subcategory.active');
        if (activeSubcategory) {
            // Save current state of open options before re-rendering
            const openOptionsStates = {};
            servicesList.querySelectorAll('.service-item').forEach((item, index) => {
                const optionsDiv = item.querySelector('.service-options');
                if (optionsDiv && optionsDiv.classList.contains('show')) {
                    openOptionsStates[index] = true;
                }
            });
            
            // Find the services data again to re-render
            const servicesData = JSON.parse(activeSubcategory.getAttribute('data-services') || '[]');
            renderServices(servicesData);
            
            // Restore open options state after re-rendering
            servicesList.querySelectorAll('.service-item').forEach((item, index) => {
                if (openOptionsStates[index]) {
                    const optionsDiv = item.querySelector('.service-options');
                    const optionsBtn = item.querySelector('.options-btn');
                    if (optionsDiv && optionsBtn) {
                        optionsDiv.classList.add('show');
                        optionsBtn.textContent = 'опции ▼';
                    }
                }
            });
        }
        
        // Save updated list to session storage
        sessionStorage.setItem('selectedServices', JSON.stringify(selectedServices));
    }
    
    // Function to disable all buttons for a specific service
    function disableServiceButtons(serviceName) {
        // Get the base service name (remove any option text in parentheses)
        const baseServiceName = serviceName.split(' (')[0];
        
        // Disable all buttons that match this service
        document.querySelectorAll('.select-btn').forEach(btn => {
            const btnServiceName = btn.getAttribute('data-name');
            
            // Check if this button is for the same base service
            if (btnServiceName === baseServiceName || 
                btnServiceName.startsWith(baseServiceName + ' (') || 
                btnServiceName === serviceName) {
                
                btn.disabled = true;
                btn.textContent = 'избрано';
                btn.classList.add('selected');
                
                // Also disable the parent service item if it has options
                const serviceItem = btn.closest('.service-item');
                if (serviceItem) {
                    // Mark the service item as selected
                    serviceItem.classList.add('service-selected');
                    
                    // If this service has options, style the options button but keep it functional
                    const optionsBtn = serviceItem.querySelector('.options-btn');
                    if (optionsBtn) {
                        optionsBtn.style.opacity = '0.5';
                        optionsBtn.style.pointerEvents = 'none';
                    }
                }
            }
        });
    }
    
    // Function to remove a service
    function removeService(index) {
        const removedService = selectedServices[index];
        
        // Get the base service name and remove both the exact service and any related services
        const baseServiceName = removedService.name.split(' (')[0];
        
        // Remove from the Set and fix all buttons
        selectedServiceNames.delete(removedService.name);
        
        // Remove from array
        totalPrice -= removedService.price;
        totalDuration -= parseInt(removedService.duration) || 0;
        selectedServices.splice(index, 1);
        
        // Update the UI
        updateSummary();
        
        // If no services left, hide the summary
        if (selectedServices.length === 0) {
            bookingSummary.style.display = 'none';
        }
        
        // Re-enable the buttons for this service by resetting all services
        document.querySelectorAll('.service-item').forEach(item => {
            item.classList.remove('service-selected');
        });
        
        document.querySelectorAll('.select-btn').forEach(btn => {
            const btnServiceName = btn.getAttribute('data-name');
            const btnBaseName = btnServiceName.split(' (')[0];
            
            // If this button is for the removed service or a variation of it
            if (btnBaseName === baseServiceName) {
                btn.disabled = false;
                btn.textContent = 'избери';
                btn.classList.remove('selected');
            }
        });
        
        document.querySelectorAll('.options-btn').forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
        });
        
        // Refresh the current view by clicking the active subcategory
        const activeSubcategory = document.querySelector('.subcategory.active');
        if (activeSubcategory) {
            activeSubcategory.click();
        }
    }
    
    // Function to re-enable buttons for a service
    function enableServiceButtons(serviceName) {
        // Get the base service name (remove any option text in parentheses)
        const baseServiceName = serviceName.split(' (')[0];
        
        // Re-enable all buttons that match this service
        document.querySelectorAll('.select-btn').forEach(btn => {
            const btnServiceName = btn.getAttribute('data-name');
            
            // Check if this button is for the same base service
            if (btnServiceName === baseServiceName || 
                btnServiceName.startsWith(baseServiceName + ' (') || 
                btnServiceName === serviceName) {
                
                btn.disabled = false;
                btn.textContent = 'избери';
                btn.classList.remove('selected');
                
                // Re-enable the parent service item
                const serviceItem = btn.closest('.service-item');
                if (serviceItem) {
                    serviceItem.classList.remove('service-selected');
                    
                    // Re-enable the options button too
                    const optionsBtn = serviceItem.querySelector('.options-btn');
                    if (optionsBtn) {
                        optionsBtn.style.opacity = '1';
                        optionsBtn.style.pointerEvents = 'auto';
                    }
                }
            }
        });
    }

    // ---- Function to Render Services for a Subcategory ----
    function renderServices(servicesData) {
        servicesList.innerHTML = servicesData.map(service => {
            let optionsHtml = '';
            if (service.options) {
                optionsHtml = `
                    <div class="service-options">
                        ${service.options.map(option => {
                            const fullServiceName = `${service.name} (${option.name})`;
                            const isSelected = selectedServiceNames.has(fullServiceName);
                            return `
                            <div class="service-option">
                                <div class="option-info">
                                    <span class="option-name">${option.name}</span>
                                    <span class="option-duration">${option.duration} мин.</span>
                                </div>
                                <div class="option-price">
                                    <span class="price">${formatDualCurrency(option.price)}</span>
                                    <button class="select-btn ${isSelected ? 'selected' : ''}" 
                                        data-name="${fullServiceName}" 
                                        data-price="${option.price}" 
                                        data-duration="${option.duration}"
                                        ${isSelected ? 'disabled' : ''}>
                                        ${isSelected ? 'избрано' : 'избери'}
                                    </button>
                                </div>
                            </div>
                        `}).join('')}
                    </div>
                `;
            }
            
            const isBaseServiceSelected = selectedServiceNames.has(service.name);
            const hasSelectedOption = service.options && service.options.some(option => 
                selectedServiceNames.has(`${service.name} (${option.name})`));
            const isDisabled = isBaseServiceSelected || hasSelectedOption;
            
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
                                        <span class="price">${formatDualCurrency(service.price)}</span>
                                        <button class="select-btn ${isDisabled ? 'selected' : ''}" 
                                            data-name="${service.name}" 
                                            data-price="${service.price}" 
                                            data-duration="${service.duration}"
                                            ${isDisabled ? 'disabled' : ''}>
                                            ${isDisabled ? 'избрано' : 'избери'}
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

        // Re-add click handlers after rendering
        addServiceButtonListeners();
    }

    // ---- Function to Add Event Listeners to Service Buttons ----
    function addServiceButtonListeners() {
        // Add click handlers for select buttons
        servicesList.querySelectorAll('.select-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (!btn.disabled) {
                    const serviceName = btn.getAttribute('data-name');
                    const servicePrice = btn.getAttribute('data-price');
                    const serviceDuration = btn.getAttribute('data-duration');
                    addSelectedService(serviceName, servicePrice, serviceDuration);
                }
            });
        });

        // Add click handlers for options buttons
        servicesList.querySelectorAll('.options-btn').forEach(btn => {
            // Remove any existing event listeners to prevent duplicates
            btn.replaceWith(btn.cloneNode(true));
        });
        
        // Re-select the buttons after cloning
        servicesList.querySelectorAll('.options-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const optionsDiv = btn.closest('.service-item').querySelector('.service-options');
                
                if (optionsDiv) {
                    const isCurrentlyVisible = optionsDiv.classList.contains('show');
                    
                    if (isCurrentlyVisible) {
                        // If currently visible, just hide it
                        optionsDiv.classList.remove('show');
                        btn.textContent = 'опции ▼';
                    } else {
                        // If not visible, hide all others first, then show this one
                        servicesList.querySelectorAll('.service-options').forEach(otherOptions => {
                            otherOptions.classList.remove('show');
                        });
                        servicesList.querySelectorAll('.options-btn').forEach(otherBtn => {
                            otherBtn.textContent = 'опции ▼';
                        });
                        
                        // Show current options
                        optionsDiv.classList.add('show');
                        btn.textContent = 'опции ▲';
                    }
                }
            });
        });
    }

    // ---- Function to Activate Category and Subcategory ----
    function activateAndRenderSubcategory(mainCategoryElement, subCategoryElement) {
        if (!mainCategoryElement || !subCategoryElement) return;

        // 1. Activate Main Category
        serviceCategories.forEach(c => c.classList.remove('active'));
        mainCategoryElement.classList.add('active');

        // 2. Show Correct Subcategory List
        const categoryType = mainCategoryElement.getAttribute('data-category');
        subcategoriesLists.forEach(list => {
            list.style.display = list.getAttribute('data-category') === categoryType ? 'flex' : 'none';
        });

        // 3. Activate Subcategory
        document.querySelectorAll('.subcategory').forEach(s => s.classList.remove('active'));
        subCategoryElement.classList.add('active');

        // 4. Render Services
        const services = subCategoryElement.getAttribute('data-services');
        if (services) {
            const servicesData = JSON.parse(services);
            renderServices(servicesData);
        } else {
            servicesList.innerHTML = '<p>Няма налични услуги в тази подкатегория.</p>';
        }
    }

    // ---- Event Listeners ----

    // Handle service category clicks
    serviceCategories.forEach(category => {
        category.addEventListener('click', function() {
            const categoryType = this.getAttribute('data-category');
            const subcategoryList = document.querySelector(`.subcategories-list[data-category="${categoryType}"]`);
            const firstSubcategoryElement = subcategoryList ? subcategoryList.querySelector('.subcategory') : null;
            
            // Activate and render the first subcategory of the clicked main category
            activateAndRenderSubcategory(this, firstSubcategoryElement);
        });
    });
    
    // Handle subcategory clicks (now just needs to render)
    document.querySelectorAll('.subcategory').forEach(subcategory => {
        subcategory.addEventListener('click', function() {
            // Find parent main category to keep it active
            const parentList = this.closest('.subcategories-list');
            const mainCategoryType = parentList.getAttribute('data-category');
            const mainCategoryElement = document.querySelector(`.service-category[data-category="${mainCategoryType}"]`);
            
            // Activate and render this specific subcategory
            activateAndRenderSubcategory(mainCategoryElement, this);
        });
    });

    // Get URL parameters for auto-selection
    const urlParams = new URLSearchParams(window.location.search);
    const serviceFromUrl = urlParams.get('service');
    const priceFromUrl = urlParams.get('price');
    const durationFromUrl = urlParams.get('duration');
    
    // Function to auto-select service from URL parameters
    function autoSelectServiceFromUrl() {
        console.log('URL Parameters:', {
            service: serviceFromUrl,
            price: priceFromUrl,
            duration: durationFromUrl
        });
        
        if (serviceFromUrl && priceFromUrl && durationFromUrl) {
            console.log('Auto-selecting service:', serviceFromUrl);
            // Add a small delay to ensure the page is fully loaded
            setTimeout(() => {
                console.log('Calling addSelectedService with:', serviceFromUrl, priceFromUrl, durationFromUrl);
                addSelectedService(serviceFromUrl, priceFromUrl, durationFromUrl);
            }, 1000); // Increased delay
        } else {
            console.log('Missing URL parameters for auto-selection');
        }
    }

    // Automatically select the first category and its first subcategory on page load
    if (serviceCategories.length > 0) {
        const firstMainCategory = serviceCategories[0];
        const firstSubcategoryList = document.querySelector(`.subcategories-list[data-category="${firstMainCategory.getAttribute('data-category')}"]`);
        const firstSubcategoryElement = firstSubcategoryList ? firstSubcategoryList.querySelector('.subcategory') : null;
        
        activateAndRenderSubcategory(firstMainCategory, firstSubcategoryElement);
    }
    
    // Auto-select service from URL if provided (after everything is loaded)
    autoSelectServiceFromUrl();

    // Handle continue booking click
    document.querySelector('.total-price').addEventListener('click', () => {
        if (selectedServices.length > 0) {
            sessionStorage.setItem('selectedServices', JSON.stringify(selectedServices));
            window.location.href = 'booking-details.php';
        }
    });

    // Service mapping for URL parameters to actual service names
    const categoryMapping = {
        'hair': { 
            name: 'hair',
            subcategories: {
                'haircuts': {
                    index: 0, // The first subcategory (Подстригване и прически)
                    services: {
                        'damsko-podstrigvane': 'Дамско подстригване',
                        'damsko-podstrigvane-izmivane': 'Дамско подстригване + измиване и подсушаване',
                        'damsko-podstrigvane-breton': 'Дамско подстригване на бретон',
                        'mujko-podstrigvane': 'Мъжко подстригване с ножица и машинка + измиване',
                        'mujko-podstrigvane-mashinka': 'Мъжко подстригване с машинка',
                        'detsko-podstrigvane': 'Детско подстригване до 12 години',
                        'pricheska-s-kok': 'Прическа с кок',
                        'oficialna-pricheska': 'Официална прическа',
                        'oformyane-vrat': 'Оформяне на врат',
                        'izmivane-maska': 'Измиване на коса + маска'
                    }
                },
                'coloring': {
                    index: 1, // The second subcategory (Боядисване и кичури)
                    services: {
                        'boyadisvane-wella': 'Боядисване с Wella + сешоар',
                        'boyadisvane-wella-base': 'Боядисване с Wella',
                        'boyadisvane-client': 'Боядисване с боя на клиента',
                        'obezcvetyavane': 'Обезцветяване',
                        'kichuri': 'Кичури',
                        'matirane': 'Матиране'
                    }
                },
                'straightening': {
                    index: 2, // The third subcategory (Къдрене и изправяне)
                    services: {
                        'izpravyane-presa': 'Изправяне с преса',
                        'izmivane-podstrigvane-izpravyane': 'Измиване подстригване + Изправяне с сешоар'
                    }
                },
                'extensions': {
                    index: 3, // The fourth subcategory (Екстеншъни)
                    services: {
                        'udalyavane-kosa': 'Удължаване на коса с щипки'
                    }
                },
                'treatments': {
                    index: 4, // The fifth subcategory (Терапии за коса)
                    services: {
                        'keratinova-terapiya': 'Кератинова терапия за коса',
                        'terapiya-vazstanovyavane': 'Терапия за бързо възстановяване на суха и изтощена коса с Wella',
                        'arganova-terapiya': 'Арганова терапия за коса',
                        'ampula-kostopad': 'Ампула за коса против косопад',
                        'maska-koprinena': 'Маска за копринена коса'
                    }
                },
                'beard': {
                    index: 5, // The sixth subcategory (Брада и бръснене)
                    services: {
                        'oformyane-brada': 'Оформяне на брада',
                        'tonirane-kosi': 'Тониране на сиви коси'
                    }
                },
                'other': {
                    index: 6, // The seventh subcategory (Други услуги за коса)
                    services: {
                        'probirane-ushi': 'Пробиване на уши'
                    }
                }
            }
        },
        'face': {
            name: 'face',
            subcategories: {
                'makeup': {
                    index: 0, // (Професионален грим)
                    services: {
                        'profesionalen-grim': 'Професионален грим',
                        'vecheren-grim': 'Вечерен грим',
                        'svatben-grim': 'Сватбен грим',
                        'oficialen-grim': 'Официален грим',
                        'ezhedneven-grim': 'Ежедневен грим',
                        'abiturientski-grim': 'Абитуриентски грим',
                        'foto-grim': 'Фото грим'
                    }
                },
                'permanent': {
                    index: 1, // (Перманентен грим)
                    services: {
                        'permanenten-vejdi': 'Перманентен грим на вежди'
                    }
                }
            }
        },
        'epilation': {
            name: 'epilation',
            subcategories: {
                'women': {
                    index: 0, // (Кола маска жени)
                    services: {
                        'podmishnitsi-kola': 'Подмишници - кола маска',
                        'celi-kraka-kola': 'Цели крака - кола маска',
                        'polovin-kraka-kola': '1/2 крака - кола маска',
                        'celi-race-kola': 'Цели ръце - кола маска',
                        'cyalo-tyalo-kola': 'Цяло тяло - кола маска',
                        'bradichka-kola': 'Брадичка - кола маска',
                        'gorna-ustna-kola': 'Горна устна - кола маска',
                        'bakenbardi-kola': 'Бакенбарди - кола маска',
                        'skuli-kola': 'Скули - кола маска'
                    }
                },
                'men': {
                    index: 1, // (Кола маска мъже)
                    services: {
                        'podmishnitsi-muje-kola': 'Подмишници - кола маска',
                        'grab-kola': 'Гръб - кола маска',
                        'gradi-korem-kola': 'Гърди + корем - кола маска',
                        'gradi-kola': 'Гърди - кола маска',
                        'korem-kola': 'Корем - кола маска',
                        'krast-kola': 'Кръст - кола маска',
                        'race-muje-kola': 'Цели ръце - кола маска',
                        'kraka-muje-kola': 'Цели крака - кола маска',
                        'cyalo-tyalo-muje-kola': 'Цяло тяло - кола маска',
                        'skuli-muje-kola': 'Скули - кола маска',
                        'vrat-kola': 'Врат - кола маска'
                    }
                }
            }
        },
        'massage': {
            name: 'massage',
            subcategories: {
                'classic': {
                    index: 0, // (Класически масаж)
                    services: {
                        'relaksirasht-masaj': 'Релаксиращ масаж',
                        'klasicheski-masaj': 'Класически масаж при Вики'
                    }
                },
                'sport': {
                    index: 1, // (Спортен масаж)
                    services: {
                        'sporten-masaj': 'Спортен масаж'
                    }
                }
            }
        }
    };

    // If we have parameters, pre-select the service
    if (category && service) {
        // First, click the main category button (hair, face, epilation, massage)
        const categoryButton = document.querySelector(`.service-category[data-category="${category}"]`);
        if (categoryButton) {
            categoryButton.click();
            
            // Find the subcategory based on the mapping
            const categoryData = categoryMapping[category];
            if (!categoryData) return;
            
            const subcategoryData = categoryData.subcategories[service];
            if (!subcategoryData) return;
            
            // Get the subcategory by index (more reliable than name matching)
            const subcategoryList = document.querySelector(`.subcategories-list[data-category="${category}"]`);
            const subcategories = subcategoryList ? subcategoryList.querySelectorAll('.subcategory') : [];
            const subcategoryIndex = subcategoryData.index;
            
            if (subcategoryIndex >= 0 && subcategoryIndex < subcategories.length) {
                const targetSubcategory = subcategories[subcategoryIndex];
                
                // Set up observer before clicking subcategory
                const servicesObserver = new MutationObserver((mutations, observer) => {
                    // Look for added service buttons
                    const buttons = document.querySelectorAll('.select-btn');
                    if (buttons.length > 0) {
                        // We found buttons, disconnect the observer
                        observer.disconnect();
                        
                        // If we have a detail parameter, select the corresponding service
                        if (detail) {
                            const serviceMap = subcategoryData.services || {};
                            const targetServiceName = serviceMap[detail];
                            
                            if (targetServiceName) {
                                // Find the button that matches this service name
                                let found = false;
                                buttons.forEach(button => {
                                    const btnServiceName = button.getAttribute('data-name');
                                    if ((btnServiceName === targetServiceName || 
                                         btnServiceName.startsWith(targetServiceName + ' (')) && 
                                        !button.disabled) {
                                        // Select this specific service
                                        button.click();
                                        found = true;
                                    }
                                });
                                
                                // If no exact match, fall back to the first button
                                if (!found && buttons.length > 0 && !buttons[0].disabled) {
                                    buttons[0].click();
                                }
                            } else if (buttons.length > 0 && !buttons[0].disabled) {
                                // No mapping found, fall back to the first button
                                buttons[0].click();
                            }
                        }
                    }
                });
                
                // Start observing the services list for changes
                servicesObserver.observe(servicesList, { 
                    childList: true,
                    subtree: true
                });
                
                // Now click the subcategory - this will trigger service loading
                targetSubcategory.click();
            }
        }
    }

    // Add event listener for service item clicks
    document.querySelectorAll('.service-item').forEach(item => {
        item.addEventListener('click', () => {
            // Check if this service has options
            const optionsBtn = item.querySelector('.options-btn');
            
            if (optionsBtn) {
                // If it has options, just expand the options menu
                const serviceItem = optionsBtn.closest('.service-item');
                if (!serviceItem.querySelector('.service-options.show')) {
                    optionsBtn.click();
                }
                
                // Scroll to the service
                optionsBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight the options button
                optionsBtn.style.background = '#f0f0f0';
                setTimeout(() => {
                    optionsBtn.style.background = '';
                }, 1500);
            } else {
                // If it doesn't have options, select the service directly
                const targetButton = item.querySelector('.select-btn');
                if (targetButton && !targetButton.disabled) {
                    // Scroll to the service
                    targetButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Highlight the button
                    targetButton.style.background = '#8b6ad4';
                    setTimeout(() => {
                        targetButton.style.background = '';
                    }, 1500);
                    
                    // Select the service
                    targetButton.click();
                }
            }
        });
    });
}); 