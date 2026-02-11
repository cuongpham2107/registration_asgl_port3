<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành công - ASGL</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-200 py-1 px-1 sm:px-3 lg:px-4 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto my-auto">
        <div class="bg-[#fbfbfbf6] rounded-2xl shadow-2xl overflow-hidden px-8 py-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/ASG.png') }}" alt="ASG Logo" class="h-10">
                </div>
                <div class="mb-6 flex justify-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Đăng ký thành công!</h1>
                <p class="text-gray-600">Yêu cầu đăng ký của bạn đã được gửi đi và đang được xử lý.</p>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-800 text-center">
                        Cảm ơn bạn đã sử dụng hệ thống đăng ký xe khai thác của ASGL. 
                        Chúng tôi sẽ phản hồi sớm nhất có thể.
                    </p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('registration-vehicles-form') }}" 
                       class="block w-full text-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Quay lại trang đăng ký
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
                © {{ date('Y') }} ASGL - Hệ thống đăng ký xe khai thác
            </div>
        </div>
    </div>
</body>
</html>
