@extends('layouts.admin')

@section('title', 'Phân công nhân viên kỹ thuật')

@push('styles')
<style>
    .technician-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .technician-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(78, 115, 223, 0.15);
    }

    .technician-card.selected {
        box-shadow: 0 15px 30px rgba(78, 115, 223, 0.3);
    }

    .technician-card.selected::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #4e73df, #36b9cc);
        z-index: 10;
    }

    .technician-card.selected .card-header {
        background: linear-gradient(to right, rgba(78, 115, 223, 0.1), rgba(54, 185, 204, 0.1));
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .technician-avatar {
        width: 90px;
        height: 90px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .technician-card:hover .technician-avatar {
        transform: scale(1.05);
    }

    .technician-card.selected .badge-success {
        background-color: #4e73df;
    }

    .btn-confirm {
        background: linear-gradient(to right, #4e73df, #224abe);
        color: white;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-confirm:hover {
        background: linear-gradient(to right, #224abe, #1a3a8e);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(78, 115, 223, 0.4);
    }

    .btn-confirm::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .btn-confirm:hover::after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }

    .online-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 18px;
        height: 18px;
        background-color: #1cc88a;
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(28, 200, 138, 0.4);
        animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite,
                   pulse-dot 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(28, 200, 138, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(28, 200, 138, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(28, 200, 138, 0);
        }
    }

    @keyframes pulse-dot {
        0% {
            transform: scale(0.95);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(0.95);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-user-cog text-primary mr-2"></i>Phân công nhân viên kỹ thuật
            </h1>
            <p class="mt-2 text-gray-600">Chọn nhân viên kỹ thuật phù hợp để phục vụ lịch hẹn này</p>
        </div>
        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm rounded-pill px-4 py-2 border-0">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <!-- Page Heading Animation -->
    <style>
        @keyframes slideInFromLeft {
            0% {
                transform: translateX(-10%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .heading-animation {
            animation: 0.5s ease-out 0s 1 slideInFromLeft;
        }
    </style>

    <div class="heading-animation">

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle mr-2"></i>Thông tin lịch hẹn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-lightbulb fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-info">Lưu ý khi phân công</h6>
                                <p class="mb-0">Vui lòng chọn nhân viên kỹ thuật có kinh nghiệm phù hợp với dịch vụ được yêu cầu.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4 border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mã lịch hẹn</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointment->appointment_code ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Khách hàng</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointment->customer->first_name ?? '' }} {{ $appointment->customer->last_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dịch vụ</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointment->service->name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-spa fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4 border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ngày hẹn</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointment->date_appointments->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Giờ hẹn</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @if($appointment->timeSlot)
                                                    {{ $appointment->timeSlot->start_time->format('H:i') }} - {{ $appointment->timeSlot->end_time->format('H:i') }}
                                                @elseif($appointment->timeAppointment)
                                                    {{ $appointment->timeAppointment->started_time }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Trạng thái</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @if($appointment->status == 'pending')
                                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge badge-primary">Đã xác nhận</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge badge-success">Đã hoàn thành</span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="badge badge-danger">Đã hủy</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $appointment->status }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tag fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-success">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-plus mr-2"></i>Chọn nhân viên kỹ thuật
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($availableTechnicians) > 0)
                        <form action="{{ route('admin.appointments.confirm', $appointment->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                @foreach($availableTechnicians as $technician)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 technician-card shadow-sm border-0 rounded-lg overflow-hidden" data-technician-id="{{ $technician->id }}">
                                            <div class="card-header bg-gradient-light py-3 text-center border-bottom-0">
                                                <div class="position-relative d-inline-block">
                                                    <img class="rounded-circle technician-avatar border-4 border-white shadow" src="{{ $technician->avatar_url ? secure_asset('storage/' . $technician->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode($technician->first_name) . '&background=4e73df&color=ffffff' }}" alt="{{ $technician->first_name }}">
                                                    <div class="online-indicator"></div>
                                                </div>
                                            </div>
                                            <div class="card-body text-center pt-2">
                                                <h5 class="card-title mb-1 font-weight-bold text-primary">{{ $technician->first_name }} {{ $technician->last_name }}</h5>
                                                <div class="d-flex align-items-center justify-content-center mb-3">
                                                    <i class="fas fa-envelope text-gray-400 mr-1 small"></i>
                                                    <p class="card-text text-muted small mb-0">{{ $technician->email }}</p>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center text-xs text-muted mb-3">
                                                    <span><i class="fas fa-calendar-check mr-1"></i> 24 lịch hẹn</span>
                                                    <span><i class="fas fa-star text-warning mr-1"></i> 4.8/5</span>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="technician-{{ $technician->id }}" name="employee_id" value="{{ $technician->id }}" class="custom-control-input">
                                                    <label class="custom-control-label font-weight-medium" for="technician-{{ $technician->id }}">Chọn nhân viên này</label>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                                                <span class="badge badge-success px-3 py-2 rounded-pill">Khả dụng</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('employee_id')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror

                            <div class="mt-4 text-right">
                                <button type="submit" class="btn btn-confirm px-5 py-3 rounded-pill shadow-lg">
                                    <i class="fas fa-check fa-sm mr-2"></i>Xác nhận và phân công
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto mr-3">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-bold text-warning text-uppercase mb-1">Không có nhân viên khả dụng</div>
                                        <div class="text-gray-800">Không có nhân viên kỹ thuật nào khả dụng vào thời gian này. Vui lòng thử lại sau hoặc chọn thời gian khác.</div>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add animation when page loads
        setTimeout(function() {
            $('.technician-card').each(function(index) {
                $(this).css({
                    'animation': 'fadeInUp 0.5s ease forwards',
                    'animation-delay': (index * 0.1) + 's',
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                });
            });
        }, 300);

        // Add animation keyframes
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);

        // Handle card selection
        $('.technician-card').click(function() {
            const technicianId = $(this).data('technician-id');
            const radioInput = $(`#technician-${technicianId}`);

            // Uncheck all other radio buttons
            $('input[name="employee_id"]').prop('checked', false);

            // Check the clicked radio button
            radioInput.prop('checked', true);

            // Remove selected class from all cards
            $('.technician-card').removeClass('selected');

            // Add selected class to clicked card with a slight delay for better visual effect
            setTimeout(() => {
                $(this).addClass('selected');
            }, 50);

            // Scroll to submit button
            $('html, body').animate({
                scrollTop: $('.btn-confirm').offset().top - 200
            }, 500);
        });

        // Add ripple effect to submit button
        $('.btn-confirm').on('click', function(e) {
            const button = $(this);

            // Create ripple element
            const ripple = $('<span class="ripple-effect"></span>');
            button.append(ripple);

            // Set ripple position
            const buttonPos = button.offset();
            const xPos = e.pageX - buttonPos.left;
            const yPos = e.pageY - buttonPos.top;

            ripple.css({
                top: yPos + 'px',
                left: xPos + 'px'
            });

            // Remove ripple after animation completes
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
</script>

<style>
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        width: 100px;
        height: 100px;
        margin-top: -50px;
        margin-left: -50px;
        animation: ripple-animation 0.6s;
        opacity: 0;
    }

    @keyframes ripple-animation {
        from {
            transform: scale(0);
            opacity: 1;
        }
        to {
            transform: scale(3);
            opacity: 0;
        }
    }
</style>
@endsection
