<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lịch hẹn của tôi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('appointments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Đặt lịch mới
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Dịch vụ</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Ngày giờ</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Trạng thái</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4">{{ $appointment->service->name }}</td>
                                    <td class="px-6 py-4">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">{{ $appointment->status }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">Chi tiết</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>