<?php

namespace App\Filament\Resources\CoachingSessions\Tables;

use App\Filament\Resources\CoachingSessions\Schemas\CoachingSessionInfolist;
use App\Models\CoachingSession;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CoachingSessionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('session_date', 'desc')
            ->columns([
                TextColumn::make('coach.name')
                    ->label('Coach')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('member.name')
                    ->label('Member')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('session_date')
                    ->label('Session Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Duration (min)')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('coach_id')
                    ->label('Coach')
                    ->relationship('coach', 'name'),
                SelectFilter::make('member_id')
                    ->label('Member')
                    ->relationship('member', 'name'),
                Filter::make('session_date')
                    ->label('Date Range')
                    ->schema([
                        DatePicker::make('date_from')->label('From'),
                        DatePicker::make('date_to')->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('session_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('session_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modal()
                        ->url(null)
                        ->modalCancelAction(false)
                        ->modalAlignment('center')
                        ->modalWidth(Width::Large)
                        ->schema(fn ($livewire, CoachingSession $record): array => CoachingSessionInfolist::configure(
                            Schema::make($livewire)->model($record)->record($record),
                        )->getComponents(withActions: false)),
                    EditAction::make()
                        ->modalHeading('Edit Coaching Session')
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
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->emptyStateHeading('No Coaching Sessions')
            ->emptyStateDescription('Add a coaching session to get started.')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Session')
                    ->modalHeading('Add Coaching Session')
                    ->modalSubmitActionLabel('Save')
                    ->modalWidth(Width::Large)
                    ->closeModalByClickingAway(false)
                    ->visible(fn () => ! CoachingSession::exists()),
            ]);
    }
}
