<?php

namespace App\Filament\Resources\Divisions\Tables;

use App\Filament\Resources\Divisions\Schemas\DivisionInfolist;
use App\Models\Division;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DivisionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('order', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('min_rating')
                    ->label('Min Rating')
                    ->sortable(),
                TextColumn::make('max_rating')
                    ->label('Max Rating')
                    ->placeholder('No ceiling')
                    ->sortable(),
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modal()
                        ->url(null)
                        ->modalCancelAction(false)
                        ->modalAlignment('center')
                        ->modalWidth(Width::Large)
                        ->schema(fn ($livewire, Division $record): array => DivisionInfolist::configure(
                            Schema::make($livewire)->model($record)->record($record),
                        )->getComponents(withActions: false)),
                    EditAction::make()
                        ->modalHeading('Edit Division')
                        ->modalSubmitActionLabel('Save')
                        ->modalWidth(Width::Large)
                        ->closeModalByClickingAway(false),
                    DeleteAction::make(),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->dropdown(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-rectangle-stack')
            ->emptyStateHeading('No Divisions')
            ->emptyStateDescription('Add a division to get started.')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Division')
                    ->modalHeading('Add Division')
                    ->modalSubmitActionLabel('Save')
                    ->modalWidth(Width::Large)
                    ->closeModalByClickingAway(false)
                    ->visible(fn () => ! Division::exists()),
            ]);
    }
}
