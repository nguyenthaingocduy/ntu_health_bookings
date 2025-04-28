/**
 * Promotion Pop-up
 * Shows active promotions when the page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    // Wait a short time before showing the popup (1.5 seconds)
    setTimeout(function() {
        // Check if user has already seen the popup in this session
        if (!sessionStorage.getItem('popupShown')) {
            showPromotionPopup();
            // Mark that the popup has been shown in this session
            sessionStorage.setItem('popupShown', 'true');
        }
    }, 1500);

    // Make sure the close button works
    setupCloseHandlers();
});

/**
 * Setup event handlers for closing the popup
 */
function setupCloseHandlers() {
    // Close popup when the close button is clicked
    const closeBtn = document.getElementById('closePromotionBtn');
    if (closeBtn) {
        // Remove any existing event listeners first
        closeBtn.removeEventListener('click', hidePromotionPopup);
        // Add new event listener
        closeBtn.addEventListener('click', hidePromotionPopup);
    }

    // Close popup when clicking outside the popup content
    const overlay = document.getElementById('promotionOverlay');
    if (overlay) {
        // Remove any existing event listeners first
        overlay.removeEventListener('click', hidePromotionPopup);
        // Add new event listener
        overlay.addEventListener('click', hidePromotionPopup);
    }
}

/**
 * Show the promotion popup with active promotions
 */
function showPromotionPopup() {
    // Fetch active promotions from the API
    fetch('/api/active-promotions')
        .then(response => response.json())
        .then(data => {
            console.log('API response:', data);

            // Always show the popup, even if there are no promotions
            // This helps admins see that there are no promotions and create new ones

            // Update popup content with promotions (or empty array)
            const promotions = (data.promotions && data.promotions.length > 0)
                ? data.promotions
                : [];

            console.log('Found promotions:', promotions.length);

            // Update popup content
            updatePromotionContent(promotions);

            // Show the popup
            const popup = document.getElementById('promotionPopup');
            popup.classList.remove('hidden');

            // Add class to body to prevent scrolling
            document.body.classList.add('popup-active');

            // Add event listener to prevent interaction with main interface
            document.addEventListener('keydown', preventEscapeKey);

            // Setup close handlers again to ensure they work
            setupCloseHandlers();
        })
        .catch(error => {
            console.error('Error fetching promotions:', error);
        });
}

/**
 * Update the promotion popup content with promotion data
 */
function updatePromotionContent(promotions) {
    const promotionDetails = document.getElementById('promotionDetails');
    promotionDetails.innerHTML = '';

    // Set salon name
    document.getElementById('salonName').innerHTML = '<span class="text-white">Beauty</span><span class="text-yellow-300">Salon</span>';

    // Set expiry date if available
    if (promotions.length > 0 && promotions[0].end_date) {
        const endDate = formatDate(promotions[0].end_date);
        document.getElementById('promotionExpiry').textContent = `Ưu đãi đến: ${endDate}`;
    } else {
        // Hiển thị thời gian khuyến mãi mẫu cho "Ưu đãi mùa hè"
        const currentDate = new Date();
        const endOfSummer = new Date(currentDate.getFullYear(), 8, 30); // 30/09 (tháng bắt đầu từ 0)
        const formattedEndDate = formatDate(endOfSummer);
        document.getElementById('promotionExpiry').textContent = `Ưu đãi đến: ${formattedEndDate}`;
    }

    // Create main promotion heading with animation
    const mainHeading = document.createElement('div');
    mainHeading.className = 'mb-6 animate-pulse';

    // Get the first promotion title if available
    let promotionTitle = 'ƯU ĐÃI ĐẶC BIỆT';
    if (promotions && promotions.length > 0) {
        // Check if name exists and use it
        if (promotions[0].name) {
            promotionTitle = promotions[0].name.toUpperCase();
            console.log('Using promotion name for title:', promotionTitle);
        } else {
            console.log('No promotion name found, using default title');
        }
    }

    // Log all promotions for debugging
    if (promotions && promotions.length > 0) {
        promotions.forEach((promo, index) => {
            console.log(`Promotion ${index + 1}:`, promo);
            console.log(`- Name: ${promo.name}`);
            console.log(`- Title (from database): ${promo.title}`);
            console.log(`- Description: ${promo.description}`);
            console.log(`- Discount: ${promo.formatted_discount_value}`);
            console.log(`- Discount Value: ${promo.discount_value}`);
            console.log(`- Discount Type: ${promo.discount_type}`);
            console.log(`- Code: ${promo.code}`);
        });
    }

    const heading = document.createElement('h3');
    heading.className = 'text-3xl font-bold text-yellow-300 mb-2 drop-shadow-lg';

    // Add sparkle icon
    const sparkleIcon = document.createElement('span');
    sparkleIcon.className = 'mr-2';
    sparkleIcon.innerHTML = '✨';
    heading.appendChild(sparkleIcon);

    // Add the title with special styling - always use "ƯU ĐÃI ĐẶC BIỆT" for consistency
    const titleSpan = document.createElement('span');
    titleSpan.className = 'bg-gradient-to-r from-yellow-300 to-yellow-500 text-transparent bg-clip-text';
    titleSpan.textContent = 'ƯU ĐÃI ĐẶC BIỆT';
    heading.appendChild(titleSpan);

    mainHeading.appendChild(heading);
    promotionDetails.appendChild(mainHeading);

    // If there are promotions, add a subtitle with count and names
    if (promotions && promotions.length > 0) {
        const subtitle = document.createElement('div');
        subtitle.className = 'mb-4';

        // Add first promotion name in a highlighted box
        if (promotions[0].name) {
            const promoNameBox = document.createElement('div');
            promoNameBox.className = 'bg-pink-500 bg-opacity-40 p-3 rounded-lg mb-3 border-l-4 border-yellow-300 animate-pulse';

            const promoNameText = document.createElement('p');
            promoNameText.className = 'text-white text-xl font-bold';
            // Make sure to display the name and formatted discount value
            if (promotions[0].name) {
                // Add the name with special styling
                promoNameText.innerHTML = `<span class="text-yellow-300">${promotions[0].name}</span>`;

                // Add discount value if available
                if (promotions[0].formatted_discount_value) {
                    promoNameText.innerHTML += ` - <span class="inline-block bg-yellow-400 text-pink-900 font-bold px-2 py-1 rounded-md mx-1 transform -rotate-2 shadow-md">${promotions[0].formatted_discount_value}</span>`;
                }
            } else {
                // Fallback if no name is found
                promoNameText.innerHTML = `<span class="text-yellow-300">Khuyến mãi đặc biệt</span>`;

                // Add discount value if available
                if (promotions[0].formatted_discount_value) {
                    promoNameText.innerHTML += ` - <span class="inline-block bg-yellow-400 text-pink-900 font-bold px-2 py-1 rounded-md mx-1 transform -rotate-2 shadow-md">${promotions[0].formatted_discount_value}</span>`;
                }
            }

            promoNameBox.appendChild(promoNameText);
            subtitle.appendChild(promoNameBox);
        }

        // Add count of promotions
        const subtitleText = document.createElement('p');
        subtitleText.className = 'text-white text-lg';

        if (promotions.length === 1) {
            subtitleText.textContent = `1 chương trình khuyến mãi đang diễn ra`;
        } else {
            subtitleText.textContent = `${promotions.length} chương trình khuyến mãi đang diễn ra`;
        }

        subtitle.appendChild(subtitleText);

        // Add list of promotion names if there are multiple promotions
        if (promotions.length > 1) {
            const namesList = document.createElement('ul');
            namesList.className = 'mt-2 space-y-1';

            promotions.forEach((promotion, idx) => {
                // Check if promotion has a name
                if (promotion.name) {
                    const nameItem = document.createElement('li');
                    nameItem.className = 'text-yellow-300 text-md transition-all duration-300 hover:translate-x-1';

                    // Add icon before name
                    const icon = document.createElement('span');
                    icon.className = 'mr-2';
                    icon.innerHTML = '🏷️'; // Tag icon
                    nameItem.appendChild(icon);

                    // Add name with animation delay based on index
                    const nameText = document.createElement('span');
                    nameText.style.animationDelay = `${idx * 0.2}s`;
                    nameText.className = 'animate-fadeIn';
                    nameText.textContent = promotion.name;

                    // Add discount value if available
                    if (promotion.formatted_discount_value) {
                        const discountSpan = document.createElement('span');
                        discountSpan.className = 'ml-2 inline-block bg-yellow-400 text-pink-900 font-bold px-1 py-0.5 rounded-md text-xs transform -rotate-2 shadow-sm';
                        discountSpan.textContent = promotion.formatted_discount_value.replace('GIẢM ', '');
                        nameText.appendChild(document.createTextNode(' '));
                        nameText.appendChild(discountSpan);
                    }

                    nameItem.appendChild(nameText);
                    namesList.appendChild(nameItem);

                    console.log('Added promotion to list:', promotion.name);
                } else {
                    console.log('Skipping promotion without name:', promotion);
                }
            });

            subtitle.appendChild(namesList);
        }

        promotionDetails.appendChild(subtitle);
    }

    // Initialize items array to store promotion data
    let items = [];

    console.log('Processing promotions for display:', promotions);

    // Add real promotions if available
    if (promotions && promotions.length > 0) {
        // Only add description and code, name is already shown in the highlighted box
        if (promotions[0].description || promotions[0].code) {
            let descriptionText = '';

            // Add description if available
            if (promotions[0].description) {
                if (descriptionText) {
                    descriptionText += ` - ${promotions[0].description}`;
                } else {
                    descriptionText = promotions[0].description;
                }
            }

            // Add code if available
            if (promotions[0].code) {
                if (descriptionText) {
                    descriptionText += ` - Mã: ${promotions[0].code}`;
                } else {
                    descriptionText = `Mã khuyến mãi: ${promotions[0].code}`;
                }
            }

            items.push({
                text: descriptionText,
                discount: null,
                isDescription: true
            });
        }

        // Add each promotion as an item
        promotions.forEach((promotion) => {
            console.log('Processing promotion:', promotion);

            let itemText = '';
            let discountValue = '';
            let suffix = '';

            // Get discount value directly from formatted_discount_value
            discountValue = promotion.formatted_discount_value || '';

            // If no formatted value, create one based on discount type
            if (!discountValue) {
                if (promotion.discount_type === 'percentage') {
                    discountValue = promotion.discount_value + '%';
                } else if (promotion.discount_type === 'fixed') {
                    discountValue = formatPrice(promotion.discount_value) + 'đ';
                } else {
                    discountValue = 'Ưu đãi';
                }
            }

            // For the item text, we want to show what the promotion applies to
            if (promotion.service) {
                // If promotion has a service, format as "Service Name"
                itemText = promotion.service.name || 'Dịch vụ';

                // Always add promotion name before service name
                if (promotion.name) {
                    // Add promotion name with service name
                    itemText = `${promotion.name} - ${itemText}`;
                    console.log('Added promotion name to item text:', itemText);
                }

                // Add price information if available
                if (promotion.service.price) {
                    const originalPrice = promotion.service.price;
                    let discountedPrice = originalPrice;

                    // Calculate discounted price
                    if (promotion.discount_type === 'percentage') {
                        discountedPrice = originalPrice - (originalPrice * promotion.discount_value / 100);
                    } else if (promotion.discount_type === 'fixed') {
                        discountedPrice = originalPrice - promotion.discount_value;
                    }

                    // Format prices
                    const formattedOriginal = formatPrice(originalPrice);
                    const formattedDiscounted = formatPrice(discountedPrice);

                    suffix = ` (${formattedDiscounted}đ thay vì ${formattedOriginal}đ)`;
                }
            } else {
                // Always show promotion name
                if (promotion.name) {
                    // Use promotion name
                    itemText = promotion.name;
                    console.log('Using promotion name for item without service:', itemText);

                    // Add "Áp dụng cho tất cả dịch vụ" as suffix if not already in description
                    if (!suffix) {
                        suffix = ' - Áp dụng cho tất cả dịch vụ';
                    }
                } else {
                    // Fallback if no name
                    itemText = 'Khuyến mãi đặc biệt';
                    console.log('Using fallback name for item without service');
                }

                // Add description if available and not already shown
                if (promotion.description && !items.some(item => item.isDescription)) {
                    suffix = ` - ${promotion.description}`;
                }
            }

            console.log('Adding promotion item:', { text: itemText, discount: discountValue, suffix: suffix });

            items.push({
                text: itemText,
                discount: discountValue,
                suffix: suffix
            });
        });
    } else {
        console.log('No promotions found, using fallback items');
        // Fallback items if no promotions are available
        // Sử dụng khuyến mãi đặc biệt "Ưu đãi mùa hè"
        promotionTitle = 'ƯU ĐÃI MÙA HÈ';
        items = [
            { text: 'Mã khuyến mãi: 002', discount: null, isDescription: true },
            { text: 'Tất cả các dịch vụ', discount: '20%', suffix: 'cho toàn bộ dịch vụ' },
            { text: 'Dịch vụ làm đẹp cao cấp', discount: '25%', suffix: 'cho khách hàng VIP' },
            { text: 'Đặt lịch trực tuyến', discount: '5%', suffix: 'thêm khi đặt lịch qua website' }
        ];
    }

    // Create promotion items with enhanced styling
    items.forEach((item, index) => {
        const itemDiv = document.createElement('div');

        // Special styling for description items
        if (item.isDescription) {
            // Create a description box
            itemDiv.className = 'mb-4 p-3 bg-pink-500 bg-opacity-20 rounded-lg';

            // Create description text
            const text = document.createElement('p');
            text.className = 'text-white text-lg italic';

            // For description items, we want to highlight the code if present
            if (item.text.includes('Mã:') || item.text.includes('Mã khuyến mãi:')) {
                // Check which format is used
                let parts;
                if (item.text.includes('Mã khuyến mãi:')) {
                    parts = item.text.split('Mã khuyến mãi:');

                    // Add the first part if it exists
                    if (parts[0].trim()) {
                        text.appendChild(document.createTextNode(parts[0]));
                    }

                    // Add the code label
                    const codeLabel = document.createElement('span');
                    codeLabel.textContent = 'Mã khuyến mãi: ';
                    text.appendChild(codeLabel);
                } else {
                    parts = item.text.split('Mã:');

                    // Add the first part
                    text.appendChild(document.createTextNode(parts[0]));

                    // Add the code label
                    const codeLabel = document.createElement('span');
                    codeLabel.textContent = 'Mã: ';
                    text.appendChild(codeLabel);
                }

                // Add the code with highlighting
                const codeValue = document.createElement('span');
                codeValue.className = 'text-yellow-300 font-bold';
                codeValue.textContent = parts[1].trim();
                text.appendChild(codeValue);
            } else if (item.text.includes('Tên chương trình:')) {
                // Highlight promotion name
                const parts = item.text.split('Tên chương trình:');

                // Add the label
                const nameLabel = document.createElement('span');
                nameLabel.textContent = 'Tên chương trình: ';
                text.appendChild(nameLabel);

                // Add the name with highlighting
                const nameValue = document.createElement('span');
                nameValue.className = 'text-yellow-300 font-bold';
                nameValue.textContent = parts[1].trim();
                text.appendChild(nameValue);
            } else {
                text.textContent = item.text;
            }

            // Append elements
            itemDiv.appendChild(text);
            promotionDetails.appendChild(itemDiv);
            return;
        }

        // Add special highlight for the first real promotion item
        if (index === 0) {
            // First item gets special styling
            itemDiv.className = 'promotion-item mb-5 flex items-start bg-pink-500 bg-opacity-40 p-3 rounded-lg transform hover:scale-102 transition-all';
        } else {
            itemDiv.className = 'promotion-item mb-4 flex items-start hover:bg-pink-500 hover:bg-opacity-20 p-2 rounded-lg transition-all';
        }

        // Create bullet point with animation
        const bullet = document.createElement('span');
        bullet.className = index === 0 ? 'promotion-bullet text-yellow-300 mr-3 text-2xl' : 'promotion-bullet text-yellow-300 mr-3 text-xl';
        bullet.innerHTML = index === 0 ? '★' : '✦'; // Star for first real item, diamond for others

        // Create content wrapper
        const contentWrapper = document.createElement('div');
        contentWrapper.className = 'flex-1';

        // Create promotion text
        const text = document.createElement('p');
        text.className = 'text-white text-lg leading-tight';

        // Format the text with highlighted discount
        if (item.discount) {
            // Create a wrapper for the discount to add special styling
            const discountSpan = document.createElement('span');

            // Make the discount more prominent based on value
            const discountValue = parseFloat(item.discount);
            if (!isNaN(discountValue) && discountValue >= 20) {
                // For high discounts (20% or more), make them very prominent
                discountSpan.className = 'inline-block bg-yellow-400 text-pink-900 font-bold px-3 py-1 rounded-md mx-1 transform -rotate-2 shadow-md text-xl animate-pulse';
            } else if (index === 0) {
                // For the first item, make it prominent
                discountSpan.className = 'inline-block bg-yellow-400 text-pink-900 font-bold px-3 py-1 rounded-md mx-1 transform -rotate-2 shadow-md text-xl';
            } else {
                // For other items
                discountSpan.className = 'inline-block bg-yellow-400 text-pink-900 font-bold px-2 py-1 rounded-md mx-1 transform -rotate-2 shadow-md';
            }

            discountSpan.textContent = item.discount;

            // Create a strong element for the service name
            const strongText = document.createElement('strong');

            // Check if the text contains a promotion name (format: "Name - Service")
            if (item.text.includes(' - ')) {
                const parts = item.text.split(' - ');

                // Create a span for the promotion name with special styling
                const promoNameSpan = document.createElement('span');
                promoNameSpan.className = 'text-yellow-300';
                promoNameSpan.textContent = parts[0];

                // Add the promotion name
                strongText.appendChild(promoNameSpan);

                // Add the separator
                strongText.appendChild(document.createTextNode(' - '));

                // Add the service name
                const serviceNameSpan = document.createElement('span');
                serviceNameSpan.textContent = parts[1];
                strongText.appendChild(serviceNameSpan);
            } else {
                // Just use the text as is
                strongText.textContent = item.text;
            }

            // Add elements to the text
            text.appendChild(strongText);
            text.appendChild(document.createTextNode(' '));
            text.appendChild(discountSpan);

            if (item.suffix) {
                const suffixSpan = document.createElement('span');
                suffixSpan.className = 'text-gray-200 ml-1';
                suffixSpan.textContent = item.suffix;
                text.appendChild(suffixSpan);
            }
        } else {
            // For description items, we want to highlight the code if present
            if (item.text.includes('Mã:') || item.text.includes('Mã khuyến mãi:')) {
                // Check which format is used
                let parts;
                if (item.text.includes('Mã khuyến mãi:')) {
                    parts = item.text.split('Mã khuyến mãi:');

                    // Add the first part if it exists
                    if (parts[0].trim()) {
                        text.appendChild(document.createTextNode(parts[0]));
                    }

                    // Add the code label
                    const codeLabel = document.createElement('span');
                    codeLabel.textContent = 'Mã khuyến mãi: ';
                    text.appendChild(codeLabel);
                } else {
                    parts = item.text.split('Mã:');

                    // Add the first part
                    text.appendChild(document.createTextNode(parts[0]));

                    // Add the code label
                    const codeLabel = document.createElement('span');
                    codeLabel.textContent = 'Mã: ';
                    text.appendChild(codeLabel);
                }

                // Add the code with highlighting
                const codeValue = document.createElement('span');
                codeValue.className = 'text-yellow-300 font-bold';
                codeValue.textContent = parts[1].trim();
                text.appendChild(codeValue);
            } else {
                text.textContent = item.text;
            }
        }

        // Append elements
        contentWrapper.appendChild(text);
        itemDiv.appendChild(bullet);
        itemDiv.appendChild(contentWrapper);
        promotionDetails.appendChild(itemDiv);

        console.log('Added promotion item to DOM:', item);
    });

    // Add call-to-action at the bottom with enhanced styling
    const ctaInfo = document.createElement('div');
    ctaInfo.className = 'mt-6 p-4 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg border-l-4 border-yellow-400 shadow-lg transform hover:scale-102 transition-all';

    const ctaText = document.createElement('p');
    ctaText.className = 'text-white font-medium flex items-center';

    // Add icon with animation
    const icon = document.createElement('i');
    icon.className = 'fas fa-gift text-yellow-300 mr-2 text-xl';
    ctaText.appendChild(icon);

    // Add text with highlight
    const textSpan = document.createElement('span');
    textSpan.innerHTML = 'Đặt lịch ngay hôm nay để nhận <strong class="text-yellow-300">ưu đãi đặc biệt!</strong>';
    ctaText.appendChild(textSpan);

    ctaInfo.appendChild(ctaText);
    promotionDetails.appendChild(ctaInfo);

    // If no real promotions were found, add a note about the summer promotion
    if (promotions.length === 0) {
        const summerNote = document.createElement('div');
        summerNote.className = 'mt-4 p-3 bg-pink-500 bg-opacity-30 rounded-lg border-l-4 border-yellow-400';

        const summerText = document.createElement('p');
        summerText.className = 'text-white text-sm';
        summerText.innerHTML = 'Sử dụng mã <strong class="text-yellow-300 bg-pink-700 px-2 py-1 rounded">002</strong> khi đặt lịch để nhận ưu đãi. Áp dụng cho tất cả khách hàng.';

        summerNote.appendChild(summerText);
        promotionDetails.appendChild(summerNote);

        // Only show admin note if user is admin
        if (document.querySelector('a[href="/admin/promotions"]')) {
            const adminNote = document.createElement('div');
            adminNote.className = 'mt-2 p-2 bg-blue-500 bg-opacity-30 rounded-lg border-l-4 border-blue-400';

            const adminText = document.createElement('p');
            adminText.className = 'text-white text-xs';
            adminText.innerHTML = 'Không tìm thấy khuyến mãi nào trong cơ sở dữ liệu. <a href="/admin/promotions" class="text-blue-300 underline">Tạo khuyến mãi mới</a>.';

            adminNote.appendChild(adminText);
            promotionDetails.appendChild(adminNote);
        }
    }
}

/**
 * Extract discount value from formatted string
 */
function extractDiscountValue(formattedString) {
    // Extract percentage or number from strings like "GIẢM 20%" or "GIẢM 100.000đ"
    const percentMatch = formattedString.match(/(\d+)%/);
    if (percentMatch) {
        return percentMatch[1] + '%';
    }

    const valueMatch = formattedString.match(/(\d+[\d\.,]+)/);
    if (valueMatch) {
        return valueMatch[1] + 'đ';
    }

    return formattedString;
}

/**
 * Hide the promotion popup
 */
function hidePromotionPopup() {
    console.log('Hiding promotion popup');
    const popup = document.getElementById('promotionPopup');
    if (popup) {
        popup.classList.add('hidden');

        // Remove class from body to allow scrolling again
        document.body.classList.remove('popup-active');

        // Remove event listener
        document.removeEventListener('keydown', preventEscapeKey);

        console.log('Popup hidden successfully');
    } else {
        console.error('Promotion popup element not found');
    }
}

/**
 * Handle Escape key to close popup
 */
function preventEscapeKey(e) {
    if (e.key === 'Escape') {
        // Instead of preventing default, let's close the popup
        e.preventDefault();
        e.stopPropagation();
        hidePromotionPopup();
        console.log('Closing popup with Escape key');
    }
}

/**
 * Format date to dd/mm/yyyy
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
}

/**
 * Format price with thousand separators
 */
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
