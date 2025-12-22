<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\RegistrationVehicle;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Livewire\Component;
use App\Filament\Resources\RegistrationVehicles\Actions\ImportAction;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;
 use Filament\Schemas\Components\FusedGroup;

class RegistrationVehicleForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    // Which form is currently active: 'individual' or 'organization'
    public string $activeForm = 'individual';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        if ($this->activeForm === 'organization') {
            return $schema
                
                ->components([
                    // form hiển thị đơn vị
                    Section::make('Thông tin đơn vị')
                        ->description('Đơn vị đăng ký')
                        ->columnSpanFull()
                        ->columns(6)
                        ->schema([
                            Select::make('company_id')
                                ->label('Chọn đơn vị nếu có (để tự động điền thông tin)')
                                ->belowLabel([
                                    Icon::make(Heroicon::ShieldExclamation),
                                    new HtmlString('<span class="italic font-light text-blue-600">Chọn đơn vị đã có để tự động điền thông tin bên dưới, hoặc để trống để tạo đơn vị mới.</span>')
                                ])
                                ->options(Company::pluck('name', 'id'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $company = Company::find($state);
                                    $set('name', $company->name ?? null);
                                    $set('tax_number', $company->tax_number ?? null);
                                    $set('address', $company->address ?? null);
                                    $set('phone_number', $company->phone_number ?? null);
                                    $set('email', $company->email ?? null);
                                })
                                ->searchable()
                                ->columnSpanFull(),
                            TextInput::make('name')
                                ->label('Tên đơn vị')
                                ->required()
                                ->disabled(fn (callable $get) => !empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan(3),
                            TextInput::make('tax_number')
                                ->label('Mã số thuế')
                                ->disabled(fn (callable $get) => !empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->required()
                                ->columnSpan(3),
                            TextInput::make('address')
                                ->label('Địa chỉ')
                                ->disabled(fn (callable $get) => !empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan(2),
                            TextInput::make('phone_number')
                                ->label('Số điện thoại')
                                ->tel()
                                ->required()
                                ->disabled(fn (callable $get) => !empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan(2),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->disabled(fn (callable $get) => !empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan(2),
                        ]),
                    Repeater::make('registration_vehicles')
                        ->label('Danh sách xe đăng ký')
                        ->compact()
                        ->afterLabel([
                            ImportAction::make(),
                        ])
                        ->table([
                            TableColumn::make('Tên lái xe'),
                            TableColumn::make('CMND/CCCD'),
                            TableColumn::make('Biển số xe'),
                            TableColumn::make('Tải trọng')
                                ->width('50px'),
                            TableColumn::make('Cổng vào')
                                ->width('50px'),
                            TableColumn::make('Thời gian dự kiến vào'),
                            TableColumn::make('Ghi chú'),
                        ])
                        ->schema([
                            TextInput::make('driver_name')
                                ->required(),
                            TextInput::make('driver_id_card')
                                ->required(),
                            TextInput::make('license_plate')
                                ->required(),
                            TextInput::make('load_capacity')
                                ->required()
                                ->numeric(),
                            TextInput::make('entry_gate')
                                ->required()
                                ->numeric(),
                            DateTimePicker::make('expected_arrival_time')
                                ->format('d/m/Y H:i')
                                ->seconds(false)
                                ->required(),
                            Textarea::make('notes')
                                ->rows(1)
                                ->required(),
                        ])->columnSpanFull(),
                ])
                ->statePath('data');
        }

        // Individual form (default)
        return $schema
            ->components([
                Section::make('Thông tin đăng ký xe')
                    ->description('Cá nhân đăng ký')
                    ->columnSpanFull()
                    ->columns(6)
                    ->schema([
                        TextInput::make('driver_name')
                            ->label('Tên lái xe')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('driver_id_card')
                            ->label('CMND/CCCD')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('license_plate')
                            ->label('Biển số xe')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('load_capacity')
                            ->label('Tải trọng')
                            ->required()
                            ->numeric()
                            ->columnSpan(2),
                        TextInput::make('entry_gate')
                            ->label('Cổng vào')
                            ->required()
                            ->numeric()
                            ->columnSpan(2),
                        DateTimePicker::make('expected_arrival_time')
                            ->label('Thời gian dự kiến vào')
                            ->format('d/m/Y H:i')
                            ->seconds(false)
                            ->required()
                            ->columnSpanFull(),
                        Select::make('company_id')
                            ->label('Thuộc đơn vị')
                            ->options(\App\Models\Company::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(6)
            ->statePath('data');
    }

    public function setActiveForm(string $which): void
    {
        if (! in_array($which, ['individual', 'organization'])) {
            return;
        }

        $this->activeForm = $which;
        $this->form->fill(); // Reset form when switching
    }

    public function create(): void
    {
        // Handle individual form submission
        $data = $this->form->getState();
        dd($data);
        // TODO: validate and persist; for now just flash success and reset
        session()->flash('success', 'Đăng ký cá nhân đã được gửi.');
        $this->form->fill();
    }

    public function createOrganization(): void
    {
        // Handle organization form submission
        $data = $this->form->getState();
        // Basic validation rules for organization submission
        $rules = [
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required_without:company_id', 'string'],
            'tax_number' => ['required_without:company_id', 'string'],
            'address' => ['required_without:company_id', 'string'],
            'phone_number' => ['required_without:company_id', 'string'],
            'email' => ['required_without:company_id', 'email'],
            'registration_vehicles' => ['required', 'array', 'min:1'],
            'registration_vehicles.*.driver_name' => ['required', 'string'],
            'registration_vehicles.*.driver_id_card' => ['required', 'string'],
            'registration_vehicles.*.license_plate' => ['required', 'string'],
            'registration_vehicles.*.load_capacity' => ['required'],
            'registration_vehicles.*.entry_gate' => ['nullable'],
            'registration_vehicles.*.expected_arrival_time' => ['required'],
            'registration_vehicles.*.notes' => ['nullable'],
        ];

        $validator = Validator::make($data ?? [], $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                $this->addError($field, implode(' ', $messages));
            }
            return;
        }

        DB::beginTransaction();

        try {
            // Determine company: either selected or newly created
            if (empty($data['company_id'])) {
                $tax = $data['tax_number'] ?? null;

                // Check tax number uniqueness
                if ($tax && Company::where('tax_number', $tax)->exists()) {
                    $this->addError('tax_number', 'Mã số thuế đã tồn tại. Vui lòng kiểm tra lại.');
                    DB::rollBack();
                    return;
                }

                $company = Company::create([
                    'name' => $data['name'] ?? '',
                    'tax_number' => $data['tax_number'] ?? null,
                    'address' => $data['address'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'email' => $data['email'] ?? null,
                ]);

                $companyId = $company->id;
            } else {
                $company = Company::find($data['company_id']);

                if (! $company) {
                    $this->addError('company_id', 'Đơn vị được chọn không tồn tại.');
                    DB::rollBack();
                    return;
                }

                $companyId = $company->id;
            }

            // Create registration vehicles
            foreach ($data['registration_vehicles'] as $rv) {
                // expected_arrival_time may be a string in 'd/m/Y H:i' format or a Carbon instance
                $expected = $rv['expected_arrival_time'] ?? null;

                if (is_string($expected)) {
                    // Try parse d/m/Y H:i first
                    try {
                        $expectedCarbon = Carbon::createFromFormat('d/m/Y H:i', $expected);
                    } catch (\Throwable $e) {
                        $expectedCarbon = Carbon::parse($expected);
                    }
                } elseif ($expected instanceof Carbon) {
                    $expectedCarbon = $expected;
                } else {
                    $expectedCarbon = null;
                }

                RegistrationVehicle::create([
                    'driver_name' => $rv['driver_name'] ?? null,
                    'driver_id_card' => $rv['driver_id_card'] ?? null,
                    'license_plate' => $rv['license_plate'] ?? null,
                    'load_capacity' => $rv['load_capacity'] ?? null,
                    'entry_gate' => $rv['entry_gate'] ?? null,
                    'expected_arrival_time' => $expectedCarbon,
                    'notes' => $rv['notes'] ?? null,
                    'company_id' => $companyId,
                    
                    'status' => 'pending_approval',
                ]);
            }

            DB::commit();

            Notification::make()
                ->title('Đã gửi đăng ký')
                ->success()
                ->body('Đăng ký tổ chức đã được gửi.')
                ->send();

            $this->form->fill();

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            Notification::make()
                ->title('Lỗi')
                ->danger()
                ->body('Đã xảy ra lỗi khi lưu đăng ký. Vui lòng thử lại.')
                ->send();
        }
    }

    public function render(): View
    {
        return view('livewire.registration-vehicle-form');
    }
}
