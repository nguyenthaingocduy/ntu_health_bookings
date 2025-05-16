@extends('layouts.admin')

@section('title', 'Quản lý hóa đơn')

@section('header', 'Quản lý hóa đơn')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Danh sách hóa đơn</h2>
        <p class="mt-1 text-sm text-gray-600">Quản lý tất cả hóa đơn trong hệ thống</p>
    </div>
    <div>
        <a href="{{ route('admin.invoices.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Tạo hóa đơn mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form action="{{ route('admin.invoices.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm kiếm theo mã hóa đơn, tên khách hàng..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>
        <div class="w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <option value="">-- Trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
        </div>
        <div class="w-48">
            <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <option value="">-- Phương thức thanh toán --</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
            </select>
        </div>
        <div>
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>
                Lọc
            </button>
        </div>
    </form>
</div>

<!-- Invoices List -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mã hóa đơn
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Khách hàng
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ngày tạo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tổng tiền
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Trạng thái
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Phương thức
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $invoice->user->full_name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->user->email ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($invoice->total, 0, ',', '.') }} VNĐ</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {!! $invoice->status_badge !!}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($invoice->payment_method == 'cash')
                                <span class="text-green-600"><i class="fas fa-money-bill-wave mr-1"></i> Tiền mặt</span>
                            @elseif($invoice->payment_method == 'bank_transfer')
                                <span class="text-blue-600"><i class="fas fa-university mr-1"></i> Chuyển khoản</span>
                            @elseif($invoice->payment_method == 'credit_card')
                                <span class="text-purple-600"><i class="fas fa-credit-card mr-1"></i> Thẻ tín dụng</span>
                            @else
                                {{ $invoice->payment_method }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($invoice->payment_status == 'pending')
                        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.invoices.pdf', $invoice->id) }}" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        Không có hóa đơn nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $invoices->links() }}
    </div>
</div>
@endsection
