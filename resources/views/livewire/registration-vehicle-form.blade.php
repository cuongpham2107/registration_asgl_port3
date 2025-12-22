<div class="min-h-screen bg-linear-to-br from-blue-500 to-blue-200 py-1 px-1 sm:px-3 lg:px-4 flex items-center justify-center">
    <div class="{{ $this->activeForm === 'organization' ? 'min-w-7xl' : 'max-w-7xl' }} mx-auto my-auto">
        <div class="bg-[#fbfbfbf6] rounded-2xl shadow-2xl overflow-hidden px-4 py-2">
            <!-- Header -->
            <div class=" px-2 py-3 text-center">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('images/ASG.png') }}" alt="ASG Logo" class="h-8 w-24">
                </div>
                <h1 class="text-xl font-bold">Đăng ký xe khai thác</h1>
            </div>

            <!-- Form Content -->
            <div class="px-3 py-2">
                @if (session()->has('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <x-filament::tabs label="Loại đăng ký" class="mb-4 justify-around">
                    <x-filament::tabs.item 
                        :active="$this->activeForm === 'individual'" 
                        wire:click="setActiveForm('individual')"
                        icon="heroicon-m-user"
                        class="w-full {{ $this->activeForm === 'individual' ? 'bg-green-100' : '' }}"
                    >
                        Cá nhân
                    </x-filament::tabs.item>
                    
                    <x-filament::tabs.item 
                        :active="$this->activeForm === 'organization'" 
                        wire:click="setActiveForm('organization')"
                        icon="heroicon-m-building-office"
                        class="w-full {{ $this->activeForm === 'organization' ? 'bg-green-100' : '' }}"
                    >
                        Tổ chức
                    </x-filament::tabs.item>
                </x-filament::tabs>

                @if ($this->activeForm === 'organization')
                    <form wire:submit.prevent="createOrganization">
                        {{ $this->form }}
                        <div class="flex gap-4 mt-4">
                            <button 
                                type="submit"
                                    class="flex-1 bg-linear-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                                style="background: linear-gradient(45deg, #10b981, #059669); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.2s; cursor: pointer;"
                                onmouseover="this.style.background='linear-gradient(45deg, #059669, #047857)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)'"
                                onmouseout="this.style.background='linear-gradient(45deg, #10b981, #059669)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>Tạo và gửi</span>
                                <span wire:loading>
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Đang xử lý...
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                @if ($this->activeForm === 'individual')
                    <form wire:submit.prevent="create">
                        {{ $this->form }}
                    <div class="flex gap-4" style="margin-top: 16px;">
                        <button 
                            type="submit"
                            class="flex-1 bg-linear-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            style="background: linear-gradient(45deg, #10b981, #059669); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.2s; cursor: pointer;"
                            onmouseover="this.style.background='linear-gradient(45deg, #059669, #047857)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)'"
                            onmouseout="this.style.background='linear-gradient(45deg, #10b981, #059669)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Tạo và gửi</span>
                            <span wire:loading>
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                </form>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    © {{ date('Y') }} ASGL - Hệ thống đăng ký xe khai thác
                </p>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />

    <script>
        
    </script>
</div>