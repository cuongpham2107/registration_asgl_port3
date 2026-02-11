<?php

namespace App\Livewire;

use App\Filament\Resources\RegistrationVehicles\Actions\ImportAction;
use App\Models\Company;
use App\Models\Gateway;
use App\Models\LoadCapacity;
use App\Models\RegistrationVehicle;
use Carbon\Carbon;
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
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Closure;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Model;

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
                        ->columns([
                            'default' => 1,
                            'md' => 6,
                        ])
                        ->schema([
                            Select::make('company_id')
                                ->label('Chọn đơn vị nếu có (để tự động điền thông tin)')
                                ->belowLabel([
                                    Icon::make(Heroicon::ShieldExclamation),
                                    new HtmlString('<span class="italic font-light text-blue-600">Chọn đơn vị đã có để tự động điền thông tin bên dưới, hoặc để trống để tạo đơn vị mới.</span>'),
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
                                ->disabled(fn (callable $get) => ! empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 3,
                                ]),
                            TextInput::make('tax_number')
                                ->label('Mã số thuế')
                                ->disabled(fn (callable $get) => ! empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->required()
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 3,
                                ]),
                            TextInput::make('address')
                                ->label('Địa chỉ')
                                ->disabled(fn (callable $get) => ! empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 2,
                                ]),
                            TextInput::make('phone_number')
                                ->label('Số điện thoại')
                                ->tel()
                                ->required()
                                ->disabled(fn (callable $get) => ! empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 2,
                                ]),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->disabled(fn (callable $get) => ! empty($get('company_id')))
                                ->dehydrated(fn (callable $get) => empty($get('company_id')))
                                ->columnSpan([
                                    'default' => 1,
                                    'md' => 2,
                                ]),
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
                            TableColumn::make('Tải trọng'),
                            TableColumn::make('Cổng vào')
                                ->width('150px'),
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
                            Select::make('id_load_capacity')
                                ->required()
                                ->options(LoadCapacity::pluck('name', 'id')),
                            Select::make('id_gateway')
                                ->placeholder('Chọn cổng vào')
                                ->required()
                                ->options(Gateway::pluck('name', 'id')),
                            DateTimePicker::make('expected_arrival_time')
                                ->displayFormat('d/m/Y H:i')
                                ->seconds(false)
                                ->native(false)
                                ->required()
                                ->rules([
                                    fn(Get $get, ?Model $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                        if (($record['status'] ?? null) != 'sent') {
                                            if (Carbon::parse($value, 'Asia/Ho_Chi_Minh')->lessThanOrEqualTo(Carbon::now('Asia/Ho_Chi_Minh'))) {
                                                $fail('Ngày, giờ phải lớn hơn ngày, giờ hiện tại.');
                                            }
                                        }

                                    }
                                ]),
                            Textarea::make('notes')
                                ->rows(1),
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
                    ->columns([
                        'default' => 2,
                        'md' => 4,
                    ])
                    
                    ->schema([
                        TextInput::make('driver_name')
                            ->label('Tên lái xe')
                            ->required()
                            ->autofocus()
                            ->columnSpan([
                                'default' => 'full',
                                'md' => 2,
                            ]),
                        TextInput::make('driver_id_card')
                            ->label('CMND/CCCD')
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ]),
                        TextInput::make('license_plate')
                            ->label('Biển số xe')
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ]),
                        Select::make(name: 'id_load_capacity')
                            ->label('Tải trọng')
                            ->required()
                            ->options(LoadCapacity::pluck('name', 'id'))
                            ->columnSpan([
                                'default' => 'full',
                                'md' => 2,
                            ]),
                        Select::make('id_gateway')
                            ->label('Cổng vào')
                            ->required()
                            ->options(Gateway::pluck('name', 'id'))
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ]),
                        DateTimePicker::make('expected_arrival_time')
                            ->label('Thời gian dự kiến vào')
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->native(false)
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'md' => '2',
                            ])
                            ->rules([
                                fn(Get $get, ?Model $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                    if (($record['status'] ?? null) != 'sent') {
                                        if (Carbon::parse($value, 'Asia/Ho_Chi_Minh')->lessThanOrEqualTo(Carbon::now('Asia/Ho_Chi_Minh'))) {
                                            $fail('Ngày, giờ phải lớn hơn ngày, giờ hiện tại.');
                                        }
                                    }

                                }
                            ]),
                        Select::make('company_id')
                            ->label('Thuộc đơn vị')
                            ->options(Company::pluck('name', 'id'))
                            ->searchable()
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ]),
            ])
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

    public function create()
    {
        // Handle individual form submission
        $data = $this->form->getState();
        $rules = [
            'driver_name' => ['required', 'string'],
            'driver_id_card' => ['required', 'string'],
            'license_plate' => ['required', 'string'],
            'expected_arrival_time' => ['required'],
            'notes' => ['nullable'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                session()->flash('error', $messages[0]);
            }
            return;
        }

        RegistrationVehicle::create($data);
        
        Notification::make()
            ->title('Đăng ký cá nhân đã được gửi.')
            ->success()
            ->send();
            
        return redirect()->to(route('registration-success'));
    }

    public function createOrganization()
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
            'registration_vehicles.*.expected_arrival_time' => ['required'],
            'registration_vehicles.*.notes' => ['nullable'],
        ];

        $validator = Validator::make($data ?? [], $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                session()->flash('error', $messages[0]);
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
                $expected = $rv['expected_arrival_time'] ?? null;
                $expectedCarbon = $expected ? Carbon::parse($expected) : null;

                RegistrationVehicle::create([
                    'driver_name' => $rv['driver_name'] ?? null,
                    'driver_id_card' => $rv['driver_id_card'] ?? null,
                    'license_plate' => $rv['license_plate'] ?? null,
                    'id_load_capacity' => $rv['id_load_capacity'] ?? null,
                    'id_gateway' => $rv['id_gateway'] ?? null,
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

            return redirect()->to(route('registration-success'));

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
