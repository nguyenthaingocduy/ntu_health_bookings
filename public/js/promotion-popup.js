/**
 * Promotion Pop-up
 * Shows active promotions when the page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Promotion popup script loaded');

    // Wait a short time before showing the popup (2 seconds)
    setTimeout(function() {
        console.log('Checking if popup should be shown...');

        // Check if popup was closed in this session
        const popupClosedThisSession = sessionStorage.getItem('promotionPopupClosed');

        console.log('Popup closed this session:', popupClosedThisSession);

        // Show popup if it hasn't been closed in this session
        if (!popupClosedThisSession) {
            console.log('Showing promotion popup...');
            showPromotionPopup();
        } else {
            console.log('Popup was closed in this session - will not show');
        }
    }, 2000);

    // Make sure the close button works
    setupCloseHandlers();
});

/**
 * Setup event handlers for closing the popup
 */
function setupCloseHandlers() {
    console.log('Setting up close handlers');

    // Close popup when the close button is clicked
    const closeBtn = document.getElementById('closePromotionBtn');
    if (closeBtn) {
        console.log('Close button found:', closeBtn);

        // Remove any existing event listeners first
        closeBtn.removeEventListener('click', hidePromotionPopup);

        // Add new event listener with detailed logging
        closeBtn.addEventListener('click', function(e) {
            console.log('Close button clicked!');
            e.preventDefault();
            e.stopPropagation();
            hidePromotionPopup();
        });

        console.log('Close button handler attached successfully');
    } else {
        console.error('Close button not found!');
    }

    // Close popup when clicking outside the popup content
    const overlay = document.getElementById('promotionOverlay');
    if (overlay) {
        console.log('Overlay found:', overlay);

        // Remove any existing event listeners first
        overlay.removeEventListener('click', hidePromotionPopup);

        // Add new event listener - only close if clicking on overlay, not content
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                console.log('Overlay clicked (outside content)');
                hidePromotionPopup();
            }
        });

        console.log('Overlay handler attached successfully');
    } else {
        console.error('Overlay not found!');
    }
}

/**
 * Show the promotion popup with active promotions
 */
function showPromotionPopup() {
    console.log('showPromotionPopup() called');

    // Check if popup element exists
    const popup = document.getElementById('promotionPopup');
    if (!popup) {
        console.error('Promotion popup element not found!');
        return;
    }

    // Fetch active promotions from the API
    console.log('Fetching promotions from API...');
    fetch('/api/active-promotions')
        .then(response => {
            console.log('API response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API response data:', data);

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
            console.log('Showing popup...');
            const popup = document.getElementById('promotionPopup');
            if (popup) {
                popup.classList.remove('hidden');
                console.log('Popup classes after show:', popup.className);

                // Add class to body to prevent scrolling
                document.body.classList.add('popup-active');

                // Add event listener to prevent interaction with main interface
                document.addEventListener('keydown', preventEscapeKey);

                // Setup close handlers again to ensure they work
                setupCloseHandlers();

                console.log('Popup should now be visible');
            } else {
                console.error('Popup element not found when trying to show');
            }
        })
        .catch(error => {
            console.error('Error fetching promotions:', error);
        });
}

/**
 * Update promotion statistics in the summary section
 */
function updatePromotionStats(promotions) {
    console.log('Updating promotion stats with:', promotions);

    // Update total promotions count
    const totalPromotionsElement = document.getElementById('totalPromotions');
    if (totalPromotionsElement) {
        totalPromotionsElement.textContent = promotions.length || 0;
    }

    // Calculate and update max discount
    const maxDiscountElement = document.getElementById('maxDiscount');
    if (maxDiscountElement) {
        let maxDiscount = 0;
        let maxDiscountText = '0%';

        if (promotions && promotions.length > 0) {
            promotions.forEach(promotion => {
                if (promotion.discount_type === 'percentage' && promotion.discount_value > maxDiscount) {
                    maxDiscount = promotion.discount_value;
                    maxDiscountText = promotion.discount_value + '%';
                } else if (promotion.discount_type === 'fixed') {
                    // For fixed discounts, show the amount
                    maxDiscountText = formatPrice(promotion.discount_value) + 'đ';
                }
            });
        } else {
            // Default fallback
            maxDiscountText = '20%';
        }

        maxDiscountElement.textContent = maxDiscountText;
    }

    // Update valid until (days remaining)
    const validUntilElement = document.getElementById('validUntil');
    if (validUntilElement) {
        let daysRemaining = '--';

        if (promotions && promotions.length > 0 && promotions[0].end_date) {
            const endDate = new Date(promotions[0].end_date);
            const today = new Date();
            const timeDiff = endDate.getTime() - today.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (daysDiff > 0) {
                daysRemaining = daysDiff + ' ngày';
            } else {
                daysRemaining = 'Hết hạn';
            }
        } else {
            // Default fallback
            const endOfSummer = new Date(new Date().getFullYear(), 8, 30); // 30/09
            const today = new Date();
            const timeDiff = endOfSummer.getTime() - today.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (daysDiff > 0) {
                daysRemaining = daysDiff + ' ngày';
            }
        }

        validUntilElement.textContent = daysRemaining;
    }

    // Update terms and conditions based on promotions
    updatePromotionTerms(promotions);
}

/**
 * Update terms and conditions based on promotion data
 */
function updatePromotionTerms(promotions) {
    const termsElement = document.getElementById('promotionTerms');
    if (!termsElement) return;

    // Clear existing terms
    termsElement.innerHTML = '';

    // Add dynamic terms based on promotions
    const terms = [];

    if (promotions && promotions.length > 0) {
        // Add specific terms based on promotion data
        terms.push('• Áp dụng cho tất cả khách hàng đã đăng ký');

        // Check for minimum purchase requirements
        const hasMinPurchase = promotions.some(p => p.minimum_purchase && p.minimum_purchase > 0);
        if (hasMinPurchase) {
            const minAmount = Math.min(...promotions.filter(p => p.minimum_purchase).map(p => p.minimum_purchase));
            terms.push(`• Áp dụng cho đơn hàng từ ${formatPrice(minAmount)}đ`);
        }

        // Check for usage limits
        const hasUsageLimit = promotions.some(p => p.usage_limit && p.usage_limit > 0);
        if (hasUsageLimit) {
            terms.push('• Số lượng có hạn, áp dụng theo thứ tự đăng ký');
        }

        terms.push('• Không áp dụng đồng thời với ưu đãi khác');
        terms.push('• Chỉ áp dụng khi đặt lịch trực tuyến');
    } else {
        // Default terms
        terms.push('• Áp dụng cho tất cả khách hàng');
        terms.push('• Không áp dụng đồng thời với ưu đãi khác');
        terms.push('• Có thể thay đổi mà không báo trước');
    }

    // Add terms to DOM
    terms.forEach(term => {
        const li = document.createElement('li');
        li.textContent = term;
        li.className = 'text-sm text-gray-700';
        termsElement.appendChild(li);
    });
}

/**
 * Update the promotion popup content with promotion data
 */
function updatePromotionContent(promotions) {
    console.log('updatePromotionContent called with:', promotions);

    const promotionDetails = document.getElementById('promotionDetails');
    if (!promotionDetails) {
        console.error('promotionDetails element not found!');
        return;
    }

    promotionDetails.innerHTML = '';
    console.log('Cleared promotionDetails content');

    // Update terms and conditions based on promotions
    updatePromotionTerms(promotions);

    // Note: salonName element is now part of the static header, no need to update

    // Set expiry date if available - check if element exists
    const promotionExpiryElement = document.getElementById('promotionExpiry');
    if (promotionExpiryElement) {
        if (promotions.length > 0 && promotions[0].end_date) {
            const endDate = formatDate(promotions[0].end_date);
            promotionExpiryElement.textContent = `Ưu đãi đến: ${endDate}`;
        } else {
            // Hiển thị thời gian khuyến mãi mẫu cho "Ưu đãi mùa hè"
            const currentDate = new Date();
            const endOfSummer = new Date(currentDate.getFullYear(), 8, 30); // 30/09 (tháng bắt đầu từ 0)
            const formattedEndDate = formatDate(endOfSummer);
            promotionExpiryElement.textContent = `Ưu đãi đến: ${formattedEndDate}`;
        }
    } else {
        console.warn('promotionExpiry element not found');
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
        subtitleText.className = 'text-gray text-lg';

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
                    nameItem.className = 'text-black text-md transition-all duration-300 hover:translate-x-1';

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

            console.log('Adding promotion item:', { text: itemText, discount: discountValue, suffix: suffix, promotion: promotion });

            items.push({
                text: itemText,
                discount: discountValue,
                suffix: suffix,
                promotion: promotion // Include full promotion data for details
            });
        });
    } else {
        console.log('No promotions found, using fallback items');
        // Fallback items if no promotions are available
        // Sử dụng khuyến mãi đặc biệt "Ưu đãi mùa hè"
        promotionTitle = 'ƯU ĐÃI MÙA HÈ';

        // Create fallback promotion with simple fixed dates
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth() + 1;
        const currentDay = today.getDate();

        // Simple date strings
        const startDateStr = `${currentDay.toString().padStart(2, '0')}/${currentMonth.toString().padStart(2, '0')}/${currentYear}`;
        const endDateStr = `30/09/${currentYear}`;

        const fallbackPromotion = {
            code: 'SUMMER2025',
            start_date: `${currentYear}-${currentMonth.toString().padStart(2, '0')}-${currentDay.toString().padStart(2, '0')}`,
            end_date: `${currentYear}-09-30`,
            minimum_purchase: 0,
            usage_limit: 100,
            usage_count: 15
        };

        console.log('Fallback promotion created:', fallbackPromotion);

        items = [
            {
                text: `Mã: SUMMER2025 • Từ ${startDateStr} đến ${endDateStr}`,
                discount: null,
                isDescription: true,
                promotion: fallbackPromotion
            },
            { text: 'Khuyến mãi mùa hè', discount: null, isDescription: true },
            { text: 'Tất cả các dịch vụ', discount: '20%', suffix: 'cho toàn bộ dịch vụ', promotion: fallbackPromotion },
            { text: 'Dịch vụ làm đẹp cao cấp', discount: '25%', suffix: 'cho khách hàng VIP', promotion: fallbackPromotion },
            { text: 'Đặt lịch trực tuyến', discount: '5%', suffix: 'thêm khi đặt lịch qua website', promotion: fallbackPromotion }
        ];
    }

    // Create promotion items with modern styling
    items.forEach((item, index) => {
        const itemDiv = document.createElement('div');

        // Special styling for description items
        if (item.isDescription) {
            // Create a description box with better contrast
            itemDiv.className = 'mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-sm';

            // Create description text
            const text = document.createElement('p');
            text.className = 'text-gray-800 text-base font-semibold';

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
                codeValue.className = 'bg-gradient-to-r from-orange-500 to-red-500 text-yellow-500 px-3 py-1 rounded-full font-bold shadow-md';
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
                nameValue.className = 'bg-gradient-to-r from-purple-500 to-pink-500 text-black px-3 py-1 rounded-full font-bold shadow-md';
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

        // Modern card design for promotion items with better contrast
        if (index === 0) {
            // First item gets special styling with better readability
            itemDiv.className = 'promotion-item mb-4 p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 border border-indigo-300';
        } else {
            itemDiv.className = 'promotion-item mb-3 p-3 bg-white border-2 border-gray-200 rounded-lg hover:shadow-lg hover:border-indigo-300 transition-all duration-200';
        }

        // Create icon instead of bullet with better colors
        const icon = document.createElement('div');
        icon.className = index === 0 ? 'flex-shrink-0 w-2 h-2 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full flex items-center justify-center mr-4 shadow-md' : 'flex-shrink-0 w-2 h-2 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mr-3 shadow-sm';

        const iconSvg = document.createElement('svg');
        iconSvg.className = index === 0 ? 'w-2 h-2 text-black' : 'w-2 h-2 text-indigo-600';
        iconSvg.fill = 'currentColor';
        iconSvg.viewBox = '0 0 20 20';
        iconSvg.innerHTML = index === 0 ?
            '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>' :
            '<path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>';

        icon.appendChild(iconSvg);

        // Create content wrapper
        const contentWrapper = document.createElement('div');
        contentWrapper.className = 'flex-1';

        // Create promotion text
        const text = document.createElement('div');
        text.className = index === 0 ? 'text-black' : 'text-gray-800';

        // Format the text with highlighted discount and more details
        if (item.discount) {
            // Create service name
            const serviceName = document.createElement('div');
            serviceName.className = index === 0 ? 'font-semibold text-black text-lg mb-2' : 'font-medium text-base mb-1';
            serviceName.textContent = item.text;

            // Add promotion details if available
            if (item.promotion) {
                const detailsDiv = document.createElement('div');
                detailsDiv.className = index === 0 ? 'text-black text-sm mb-2' : 'text-gray-600 text-sm mb-2';

                const details = [];
                if (item.promotion.code) {
                    details.push(`Mã: ${item.promotion.code}`);
                }
                if (item.promotion.start_date && item.promotion.end_date) {
                    const startDate = formatDate(item.promotion.start_date);
                    const endDate = formatDate(item.promotion.end_date);
                    details.push(`Từ ${startDate} đến ${endDate}`);
                }
                if (item.promotion.minimum_purchase && item.promotion.minimum_purchase > 0) {
                    details.push(`Tối thiểu: ${formatPrice(item.promotion.minimum_purchase)}đ`);
                }
                if (item.promotion.usage_limit && item.promotion.usage_limit > 0) {
                    const remaining = item.promotion.usage_limit - (item.promotion.usage_count || 0);
                    details.push(`Còn lại: ${remaining} suất`);
                }

                if (details.length > 0) {
                    detailsDiv.textContent = details.join(' • ');
                    text.appendChild(serviceName);
                    text.appendChild(detailsDiv);
                } else {
                    text.appendChild(serviceName);
                }
            } else {
                text.appendChild(serviceName);
            }

            // Create discount badge with better contrast
            const discountBadge = document.createElement('div');
            discountBadge.className = index === 0 ?
                'inline-flex items-center bg-gradient-to-r from-green-400 to-emerald-500 text-white font-bold px-3 py-1 rounded-full text-lg shadow-md' :
                'inline-flex items-center bg-gradient-to-r from-orange-500 to-red-500 text-yellow-500 font-bold px-2 py-1 rounded-full text-sm shadow-sm';

            // Add discount icon
            const discountIcon = document.createElement('svg');
            discountIcon.className = 'w-2 h-2 mr-1';
            discountIcon.fill = 'currentColor';
            discountIcon.viewBox = '0 0 20 20';
            discountIcon.innerHTML = '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';

            discountBadge.appendChild(discountIcon);
            discountBadge.appendChild(document.createTextNode(`Giảm ${item.discount}`));

            // Add suffix if available with better contrast
            if (item.suffix) {
                const suffixDiv = document.createElement('div');
                suffixDiv.className = index === 0 ? 'text-black text-sm mt-1 font-medium' : 'text-gray-700 text-sm mt-1 font-medium';
                suffixDiv.textContent = item.suffix;
                text.appendChild(serviceName);
                text.appendChild(discountBadge);
                text.appendChild(suffixDiv);
            } else {
                text.appendChild(serviceName);
                text.appendChild(discountBadge);
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

        // Append elements with flex layout
        contentWrapper.appendChild(text);
        itemDiv.appendChild(icon);
        itemDiv.appendChild(contentWrapper);
        itemDiv.style.display = 'flex';
        itemDiv.style.alignItems = 'flex-start';
        promotionDetails.appendChild(itemDiv);

        console.log('Added promotion item to DOM:', item);
    });

    // Add call-to-action at the bottom with better contrast
    const ctaInfo = document.createElement('div');
    ctaInfo.className = 'mt-6 p-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg border border-indigo-300';

    const ctaText = document.createElement('div');
    ctaText.className = 'text-white text-center';

    // Add main CTA text
    const mainText = document.createElement('div');
    mainText.className = 'font-bold text-black text-lg mb-2';
    mainText.textContent = '🎉 Đặt lịch ngay để nhận ưu đãi!';

    // Add sub text
    const subText = document.createElement('div');
    subText.className = 'text-black text-sm font-medium';
    subText.textContent = 'Số lượng có hạn - Đừng bỏ lỡ cơ hội tuyệt vời này';

    ctaText.appendChild(mainText);
    ctaText.appendChild(subText);
    ctaInfo.appendChild(ctaText);
    promotionDetails.appendChild(ctaInfo);

    // If no real promotions were found, add a note about the summer promotion
    if (promotions.length === 0) {
        const summerNote = document.createElement('div');
        summerNote.className = 'mt-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-lg shadow-sm';

        const summerText = document.createElement('div');
        summerText.className = 'text-gray-800 text-sm text-center font-medium';

        const codeSpan = document.createElement('span');
        codeSpan.className = 'inline-block bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1 rounded-full font-bold mx-1 shadow-md';
        codeSpan.textContent = 'SUMMER2025';

        summerText.appendChild(document.createTextNode('💡 Sử dụng mã '));
        summerText.appendChild(codeSpan);
        summerText.appendChild(document.createTextNode(' khi đặt lịch để nhận ưu đãi đặc biệt!'));

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
    console.log('hidePromotionPopup() called');

    const popup = document.getElementById('promotionPopup');
    console.log('Popup element:', popup);

    if (popup) {
        console.log('Adding hidden class to popup');
        popup.classList.add('hidden');

        // Remove class from body to allow scrolling again
        console.log('Removing popup-active class from body');
        document.body.classList.remove('popup-active');

        // Remove event listener
        console.log('Removing escape key listener');
        document.removeEventListener('keydown', preventEscapeKey);

        console.log('Popup hidden successfully');

        // Mark popup as closed in this session (will show again on page refresh)
        sessionStorage.setItem('promotionPopupClosed', 'true');
        console.log('Marked popup as closed for this session');
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
    if (!dateString) {
        console.warn('formatDate: dateString is empty or null');
        return 'Không xác định';
    }

    console.log('formatDate input:', dateString);

    let date;

    // Try different date parsing methods
    if (typeof dateString === 'string') {
        // Handle dd/mm/yyyy format (already formatted)
        if (dateString.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/)) {
            console.log('Date already in dd/mm/yyyy format:', dateString);
            return dateString;
        }
        // Handle ISO date strings (YYYY-MM-DD)
        else if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            const parts = dateString.split('-');
            date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        }
        // Handle other formats
        else {
            date = new Date(dateString);
        }
    } else {
        date = new Date(dateString);
    }

    // Check if date is valid
    if (isNaN(date.getTime())) {
        console.warn('formatDate: Invalid date created from:', dateString);
        return 'Không xác định';
    }

    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();

    const result = `${day}/${month}/${year}`;
    console.log('formatDate result:', result);

    return result;
}

/**
 * Format price with thousand separators
 */
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
