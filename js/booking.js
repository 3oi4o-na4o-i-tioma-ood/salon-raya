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
                        'boyadisvane-wella': 'Боядисване с wella + сешоар',
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
                            // Always select the first button for simplicity
                            buttons[0].click();
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
            // Click the button to select the service
            const targetButton = item.querySelector('.select-btn');
            if (targetButton) {
                // Expand options if needed
                const serviceItem = targetButton.closest('.service-item');
                const optionsBtn = serviceItem?.querySelector('.options-btn');
                if (optionsBtn && !serviceItem.querySelector('.service-options.show')) {
                    optionsBtn.click();
                }
                
                // Scroll to the service
                targetButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Highlight the button
                targetButton.style.background = '#8b6ad4';
                setTimeout(() => {
                    targetButton.style.background = '';
                }, 1500);
                
                // Automatically select the service
                targetButton.click();
            }
        });
    });
}); 