/**
 * NVKT API Helper
 * Thư viện JavaScript để tương tác với API của nhân viên kỹ thuật
 */

const NVKTApi = {
    /**
     * Lấy danh sách lịch hẹn
     * 
     * @param {Object} params - Các tham số truy vấn (date, status, limit)
     * @returns {Promise} - Promise chứa kết quả trả về
     */
    getAppointments: async function(params = {}) {
        try {
            // Xây dựng query string từ params
            const queryParams = new URLSearchParams();
            if (params.date) queryParams.append('date', params.date);
            if (params.status) queryParams.append('status', params.status);
            if (params.limit) queryParams.append('limit', params.limit);
            
            const queryString = queryParams.toString();
            const url = `/api/nvkt/appointments${queryString ? '?' + queryString : ''}`;
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Lỗi khi lấy danh sách lịch hẹn:', error);
            throw error;
        }
    },
    
    /**
     * Lấy thông tin chi tiết lịch hẹn
     * 
     * @param {string} id - ID của lịch hẹn
     * @returns {Promise} - Promise chứa kết quả trả về
     */
    getAppointmentDetail: async function(id) {
        try {
            const response = await fetch(`/api/nvkt/appointments/${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Lỗi khi lấy thông tin chi tiết lịch hẹn:', error);
            throw error;
        }
    },
    
    /**
     * Cập nhật trạng thái buổi chăm sóc
     * 
     * @param {string} id - ID của lịch hẹn
     * @param {Object} data - Dữ liệu cập nhật (status, notes)
     * @returns {Promise} - Promise chứa kết quả trả về
     */
    updateAppointmentStatus: async function(id, data) {
        try {
            const response = await fetch(`/api/nvkt/appointments/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Lỗi khi cập nhật trạng thái buổi chăm sóc:', error);
            throw error;
        }
    },
    
    /**
     * Thêm ghi chú chuyên môn
     * 
     * @param {Object} data - Dữ liệu ghi chú (customer_id, appointment_id, title, content)
     * @returns {Promise} - Promise chứa kết quả trả về
     */
    addProfessionalNote: async function(data) {
        try {
            const response = await fetch('/api/nvkt/professional-notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Lỗi khi thêm ghi chú chuyên môn:', error);
            throw error;
        }
    },
    
    /**
     * Hiển thị thông báo
     * 
     * @param {string} message - Nội dung thông báo
     * @param {string} type - Loại thông báo (success, error, warning, info)
     */
    showNotification: function(message, type = 'info') {
        // Kiểm tra xem đã có container thông báo chưa
        let notificationContainer = document.getElementById('nvkt-notification-container');
        
        if (!notificationContainer) {
            // Tạo container thông báo nếu chưa có
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'nvkt-notification-container';
            notificationContainer.style.position = 'fixed';
            notificationContainer.style.top = '20px';
            notificationContainer.style.right = '20px';
            notificationContainer.style.zIndex = '9999';
            document.body.appendChild(notificationContainer);
        }
        
        // Tạo thông báo mới
        const notification = document.createElement('div');
        notification.className = 'nvkt-notification';
        notification.style.marginBottom = '10px';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = '8px';
        notification.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        notification.style.display = 'flex';
        notification.style.alignItems = 'center';
        notification.style.justifyContent = 'space-between';
        notification.style.minWidth = '300px';
        notification.style.maxWidth = '400px';
        notification.style.animation = 'nvkt-notification-fade-in 0.3s ease-out forwards';
        
        // Thiết lập màu sắc dựa trên loại thông báo
        switch (type) {
            case 'success':
                notification.style.backgroundColor = '#10B981';
                notification.style.color = 'white';
                break;
            case 'error':
                notification.style.backgroundColor = '#EF4444';
                notification.style.color = 'white';
                break;
            case 'warning':
                notification.style.backgroundColor = '#F59E0B';
                notification.style.color = 'white';
                break;
            default:
                notification.style.backgroundColor = '#3B82F6';
                notification.style.color = 'white';
                break;
        }
        
        // Thêm nội dung thông báo
        notification.innerHTML = `
            <div>${message}</div>
            <button style="background: none; border: none; color: white; cursor: pointer; margin-left: 10px; font-size: 18px;">&times;</button>
        `;
        
        // Thêm thông báo vào container
        notificationContainer.appendChild(notification);
        
        // Thêm sự kiện đóng thông báo
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', function() {
            notification.style.animation = 'nvkt-notification-fade-out 0.3s ease-out forwards';
            setTimeout(() => {
                notificationContainer.removeChild(notification);
            }, 300);
        });
        
        // Tự động đóng thông báo sau 5 giây
        setTimeout(() => {
            if (notification.parentNode === notificationContainer) {
                notification.style.animation = 'nvkt-notification-fade-out 0.3s ease-out forwards';
                setTimeout(() => {
                    if (notification.parentNode === notificationContainer) {
                        notificationContainer.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }
};

// Thêm CSS animation cho thông báo
const style = document.createElement('style');
style.textContent = `
    @keyframes nvkt-notification-fade-in {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes nvkt-notification-fade-out {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(50px); }
    }
`;
document.head.appendChild(style);
