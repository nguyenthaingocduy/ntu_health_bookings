/**
 * Service Promotion Pop-up
 * Shows a random service promotion when the page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    // Wait a short time before showing the popup (1.5 seconds)
    setTimeout(function() {
        // Check if user has already seen the popup in this session
        if (!sessionStorage.getItem('popupShown')) {
            showRandomServicePopup();
            // Mark that the popup has been shown in this session
            sessionStorage.setItem('popupShown', 'true');
        }
    }, 1500);

    // Close popup when the close button is clicked
    document.getElementById('closePopupBtn').addEventListener('click', function() {
        hideServicePopup();
    });

    // Close popup when clicking outside the popup content
    document.getElementById('servicePromotionPopup').addEventListener('click', function(e) {
        if (e.target === this) {
            hideServicePopup();
        }
    });

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
 * Show a random service promotion popup
 */
function showRandomServicePopup() {
    // Fetch random featured service from the server
    fetch('/api/random-featured-service')
        .then(response => response.json())
        .then(service => {
            if (service) {
                // Set the service details in the popup
                document.getElementById('popupServiceTitle').textContent = service.name;
                document.getElementById('popupServiceImage').src = service.image_url;
                document.getElementById('popupServicePrice').textContent = formatPrice(service.promotion || service.price);
                document.getElementById('serviceId').value = service.id;
                
                // Show the popup
                const popup = document.getElementById('servicePromotionPopup');
                popup.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Prevent scrolling
            }
        })
        .catch(error => {
            console.error('Error fetching random service:', error);
        });
}

/**
 * Hide the service promotion popup
 */
function hideServicePopup() {
    const popup = document.getElementById('servicePromotionPopup');
    popup.classList.add('hidden');
    document.body.classList.remove('overflow-hidden'); // Re-enable scrolling
}

/**
 * Format price with thousand separators
 */
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
