<?php

namespace App\Filament\Resources\RegistrationVehicles\Tables;

use App\Filament\Resources\RegistrationVehicles\Actions\ApprovesAction;
use App\Filament\Resources\RegistrationVehicles\Filters\ListFilters;
use App\Models\RegistrationVehicle;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class RegistrationVehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->description(function () {
                $user = auth()->user();
                if (! $user || ! $user->hasRole('approve_vehicle')) {
                    return '';
                }

                $count = RegistrationVehicle::where('status', 'pending_approval')
                    ->count();

                if ($count > 0) {
                    // Tạo URL filter cho các bản ghi chưa duyệt (status=sent)
                    $baseUrl = route('filament.admin.resources.registration-vehicles.index');
                    $filterUrl = $baseUrl.'?tableFilters[vehicle_filter][status]=pending_approval';

                    return new \Illuminate\Support\HtmlString(
                        '<a href="'.$filterUrl.'" style="display: flex; align-items: center; gap: 6px; padding: 8px 10px; background: linear-gradient(135deg, #ff6b6b 0%, #ffa5a5 100%); color: white; border-radius: 8px; font-weight: 500; font-size: 0.875rem; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform=\'translateY(-1px)\'; this.style.boxShadow=\'0 4px 12px rgba(255, 107, 107, 0.4)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px; flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <span>Bạn có <strong style="font-size: 1em; padding: 0 2px;">'.$count.'</strong> yêu cầu đăng ký xe đang chờ phê duyệt - <u>Nhấn để xem</u></span>
                        </a>'
                    );
                }

                return '';
            })
            ->emptyStateHeading('Không có đăng ký xe kiểm hoá nào')
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateDescription('Hiện tại chưa có đăng ký xe kiểm hoá nào được tạo. Vui lòng nhấn nút "Thêm đăng ký xe kiểm hoá" để tạo mới.')
            ->columns([
                TextColumn::make('driver_name')
                    ->label('Tên lái xe')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => mb_convert_case($state, MB_CASE_TITLE, 'UTF-8')
                    ),
                TextColumn::make('driver_id_card')
                    ->label('CMND/CCCD')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('license_plate')
                    ->label('Biển số xe')
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string => strtoupper(str_replace(' ', '', $state)))
                    ->searchable(),
                TextColumn::make('loadCapacity.name')
                    ->label('Tải trọng')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('gateway.name')
                    ->label('Cổng vào')
                    ->alignCenter()
                    ->badge()
                    ->searchable(),
                TextColumn::make('expected_arrival_time')
                    ->label('Thời gian dự kiến vào')
                    ->dateTime('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Thuộc đơn vị')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending_approval' => 'Chờ duyệt',
                            'approved' => 'Đã duyệt',
                            'entered' => 'Đã vào',
                            'exited' => 'Đã ra',
                            'rejected' => 'Từ chối',
                            default => $state,
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'pending_approval' => 'warning',
                            'approved' => 'info',
                            'entered' => 'success',
                            'exited' => 'danger',
                            'rejected' => 'gray',
                            default => 'primary',
                        };
                    })
                    ->alignCenter()
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                ListFilters::make(),
            ], layout: FiltersLayout::AboveContent)
            // ->persistFiltersInSession()
            ->deferFilters(false)
            ->groups([
                Group::make('company.name')
                    ->label('Đơn vị')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->defaultGroup('company.name')
            ->recordActions([
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                ApprovesAction::make(),
                DeleteBulkAction::make(),
            ]);
    }
}
