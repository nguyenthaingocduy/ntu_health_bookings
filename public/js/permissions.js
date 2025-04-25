// Hàm chọn tất cả các checkbox
function selectAllCheckboxes() {
    console.log('Select all function called from external JS');
    var checkboxes = document.querySelectorAll('.permission-checkbox');
    console.log('Found ' + checkboxes.length + ' checkboxes');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
    }
    return false; // Ngăn chặn hành vi mặc định của nút
}

// Hàm bỏ chọn tất cả các checkbox
function deselectAllCheckboxes() {
    console.log('Deselect all function called from external JS');
    var checkboxes = document.querySelectorAll('.permission-checkbox');
    console.log('Found ' + checkboxes.length + ' checkboxes');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
    return false; // Ngăn chặn hành vi mặc định của nút
}

// Thêm sự kiện cho nút chọn tất cả và bỏ chọn tất cả
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded in external JS');
    
    // Kiểm tra số lượng checkbox
    var checkboxes = document.querySelectorAll('.permission-checkbox');
    console.log('Total checkboxes:', checkboxes.length);

    // Kiểm tra form
    var form = document.getElementById('permissionsForm');
    console.log('Form:', form);

    // Thêm sự kiện cho nút chọn tất cả
    var selectAllBtn = document.getElementById('selectAllBtn');
    console.log('Select all button:', selectAllBtn);
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            selectAllCheckboxes();
        });
    }

    // Thêm sự kiện cho nút bỏ chọn tất cả
    var deselectAllBtn = document.getElementById('deselectAllBtn');
    console.log('Deselect all button:', deselectAllBtn);
    
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            deselectAllCheckboxes();
        });
    }

    // Thêm sự kiện submit cho form
    if (form) {
        form.addEventListener('submit', function() {
            console.log('Form submitted');
        });
    }
});

// Thêm một sự kiện window.onload để đảm bảo mã được chạy
window.addEventListener('load', function() {
    console.log('Window loaded in external JS');
    
    // Thêm sự kiện trực tiếp cho các nút
    var selectAllBtn = document.getElementById('selectAllBtn');
    if (selectAllBtn) {
        selectAllBtn.onclick = function(e) {
            e.preventDefault();
            selectAllCheckboxes();
            return false;
        };
    }
    
    var deselectAllBtn = document.getElementById('deselectAllBtn');
    if (deselectAllBtn) {
        deselectAllBtn.onclick = function(e) {
            e.preventDefault();
            deselectAllCheckboxes();
            return false;
        };
    }
});
