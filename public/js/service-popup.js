/**
 * Service Promotion Pop-up
 * Shows a random promotion or service when the page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    // This popup is now disabled in favor of the new promotion popup
    // Uncomment the code below if you want to use this popup instead
    /*
    // Wait a short time before showing the popup (1.5 seconds)
    setTimeout(function() {
        // Check if user has already seen the popup in this session
        if (!sessionStorage.getItem('popupShown')) {
            showRandomPromotionPopup();
            // Mark that the popup has been shown in this session
            sessionStorage.setItem('popupShown', 'true');
        }
    }, 1500);
    */

    // Close popup when the close button is clicked
    document.getElementById('closePopupBtn').addEventListener('click', function() {
        hideServicePopup();
    });

    // Close minimized popup when the close button is clicked
    document.getElementById('closeMinimizedBtn').addEventListener('click', function() {
        hideServicePopup();
    });

    // Close popup when clicking outside the popup content (only when centered)
    document.getElementById('popupOverlay').addEventListener('click', function() {
        hideServicePopup();
    });

    // Minimize popup when the minimize button is clicked
    document.getElementById('minimizePopupBtn').addEventListener('click', function() {
        minimizePopup();
    });

    // Expand popup when the expand button is clicked
    document.getElementById('expandPopupBtn').addEventListener('click', function() {
        expandPopup();
    });

    // Make the popup draggable
    makeDraggable(document.getElementById('popupContent'));

    // Handle the promotion form submission
    document.getElementById('promotionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Send the form data to the server
        fetch('/api/promotion-signup', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Đăng ký thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
                hideServicePopup();
            } else {
                // Show error message
                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
        });
    });
});

/**
 * Show a random promotion or service popup
 */
function showRandomPromotionPopup() {
    // Fetch random promotion from the server
    fetch('/api/random-promotion')
        .then(response => response.json())
        .then(result => {
            if (result.type === 'promotion') {
                // Display promotion with service
                const promotion = result.data.promotion;
                const service = result.data.service;

                // Set the service details in the popup
                document.getElementById('popupServiceTitle').textContent = service.name;
                document.getElementById('popupServiceImage').src = service.image_url;
                document.getElementById('serviceId').value = service.id;

                // Set promotion details
                document.getElementById('promotionTitle').textContent = promotion.title;
                document.getElementById('promotionPeriod').textContent = `Từ ${formatDate(promotion.start_date)} đến ${formatDate(promotion.end_date)}`;

                // Show promotion badge
                const promotionBadge = document.getElementById('promotionBadge');
                promotionBadge.classList.remove('hidden');
                document.getElementById('promotionBadgeText').textContent = promotion.formatted_discount_value;

                // Calculate and display prices
                const originalPrice = service.price;
                let discountedPrice = originalPrice;

                if (promotion.discount_type === 'percentage') {
                    discountedPrice = originalPrice - (originalPrice * promotion.discount_value / 100);
                } else {
                    discountedPrice = originalPrice - promotion.discount_value;
                }

                if (discountedPrice < 0) discountedPrice = 0;

                document.getElementById('popupOriginalPrice').textContent = formatPrice(originalPrice) + 'đ';
                document.getElementById('popupServicePrice').textContent = formatPrice(discountedPrice) + 'đ';
            } else {
                // Display regular service
                const service = result.data;

                // Set the service details in the popup
                document.getElementById('popupServiceTitle').textContent = service.name;
                document.getElementById('popupServiceImage').src = service.image_url;
                document.getElementById('popupServicePrice').textContent = formatPrice(service.price) + 'đ';
                document.getElementById('serviceId').value = service.id;

                // Hide promotion-specific elements
                document.getElementById('promotionBadge').classList.add('hidden');
                document.getElementById('promotionTitle').textContent = '';
                document.getElementById('promotionPeriod').textContent = '';
                document.getElementById('popupOriginalPrice').textContent = '';
            }

            // Show the popup
            const popup = document.getElementById('servicePromotionPopup');
            const popupContent = document.getElementById('popupContent');
            const popupOverlay = document.getElementById('popupOverlay');

            // Reset any previous transform
            popupContent.style.transform = 'translate(0, 0)';

            // Make sure the overlay is visible
            popupOverlay.classList.remove('hidden');

            // Update promotion badge text
            if (result.type === 'promotion') {
                document.getElementById('promotionBadgeText2').textContent = promotion.formatted_discount_value;
            } else {
                document.getElementById('promotionBadgeText2').textContent = "DỊCH VỤ ĐẶC BIỆT";
            }

            // Show the popup centered
            popup.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling
        })
        .catch(error => {
            console.error('Error fetching random promotion:', error);
        });
}

/**
 * Hide the service promotion popup
 */
function hideServicePopup() {
    const popup = document.getElementById('servicePromotionPopup');
    const fullView = document.getElementById('fullView');
    const minimizedView = document.getElementById('minimizedView');
    const popupContent = document.getElementById('popupContent');

    // Hide the popup
    popup.classList.add('hidden');

    // Reset to full view for next time
    fullView.classList.remove('hidden');
    minimizedView.classList.add('hidden');

    // Remove any classes added for minimized state
    popupContent.classList.remove('minimized');
    popupContent.classList.remove('rounded-md');

    // Reset transform
    popupContent.style.transform = 'translate(0, 0)';

    // Re-enable scrolling
    document.body.classList.remove('overflow-hidden');
}

/**
 * Minimize the popup
 */
function minimizePopup() {
    const fullView = document.getElementById('fullView');
    const minimizedView = document.getElementById('minimizedView');
    const popupContent = document.getElementById('popupContent');
    const overlay = document.getElementById('popupOverlay');

    // Update the minimized title with the service name
    const serviceTitle = document.getElementById('popupServiceTitle').textContent;
    document.getElementById('minimizedTitle').textContent = serviceTitle || 'Khuyến mãi đặc biệt';

    // Hide full view, show minimized view
    fullView.classList.add('hidden');
    minimizedView.classList.remove('hidden');

    // Hide the overlay
    overlay.classList.add('hidden');

    // Add a class to make the popup smaller
    popupContent.classList.add('minimized');
    popupContent.classList.add('rounded-md');

    // Move to bottom right if not already positioned
    if (!popupContent.style.transform) {
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;

        // Calculate position (bottom right with some margin)
        const posX = windowWidth - 250;
        const posY = windowHeight - 50;

        popupContent.style.transform = `translate(${posX}px, ${posY}px)`;
    }
}

/**
 * Expand the minimized popup
 */
function expandPopup() {
    const fullView = document.getElementById('fullView');
    const minimizedView = document.getElementById('minimizedView');
    const popupContent = document.getElementById('popupContent');

    // Show full view, hide minimized view
    fullView.classList.remove('hidden');
    minimizedView.classList.add('hidden');

    // Remove the minimized class
    popupContent.classList.remove('minimized');
    popupContent.classList.remove('rounded-md');
}

/**
 * Format price with thousand separators
 */
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Format date to dd/mm/yyyy
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
}

/**
 * Make an element draggable
 * @param {HTMLElement} element - The element to make draggable
 */
function makeDraggable(element) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    // Get the header element to use as the drag handle
    const header = element.querySelector('.bg-gradient-to-r');

    if (header) {
        // If there's a header, use it as the drag handle
        header.onmousedown = dragMouseDown;
    } else {
        // Otherwise, use the entire element as the drag handle
        element.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();

        // Get the mouse cursor position at startup
        pos3 = e.clientX;
        pos4 = e.clientY;

        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();

        // Calculate the new cursor position
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;

        // Set the element's new position
        const popup = document.getElementById('servicePromotionPopup');

        // Get the current transform values
        const style = window.getComputedStyle(element);
        const transform = style.getPropertyValue('transform');

        // Parse the transform matrix
        let matrix;
        if (transform === 'none') {
            matrix = {
                a: 1, b: 0, c: 0, d: 1,
                tx: 0, ty: 0
            };
        } else {
            const values = transform.split('(')[1].split(')')[0].split(',');
            matrix = {
                a: parseFloat(values[0]),
                b: parseFloat(values[1]),
                c: parseFloat(values[2]),
                d: parseFloat(values[3]),
                tx: parseFloat(values[4]),
                ty: parseFloat(values[5])
            };
        }

        // Update the transform
        const newX = matrix.tx - pos1;
        const newY = matrix.ty - pos2;

        element.style.transform = `translate(${newX}px, ${newY}px)`;
    }

    function closeDragElement() {
        // Stop moving when mouse button is released
        document.onmouseup = null;
        document.onmousemove = null;
    }
}
