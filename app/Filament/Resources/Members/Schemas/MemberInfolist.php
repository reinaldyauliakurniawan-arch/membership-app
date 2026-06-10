<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Models\Member;
use App\Helpers\Helpers;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MemberInfolist
{
    /**
     * Configure the member "view" infolist schema.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->heading(function (Member $record): HtmlString {
                        $status = $record->status;

                        if ($status === null) {
                            return new HtmlString(e(__('app.ui.details')));
                        }

                        $html = Blade::render(
                            '<x-filament::badge class="inline-flex ml-2" :color="$color">
                                {{ $label }}
                            </x-filament::badge>',
                            [
                                'color' => $status->getColor(),
                                'label' => $status->getLabel(),
                            ]
                        );

                        return new HtmlString(e(__('app.ui.details')).' '.$html);
                    })
                    ->schema([
                        ImageEntry::make('photo')
                            ->hiddenLabel()
                            ->defaultImageUrl(fn (Member $record): string => 'https://ui-avatars.com/api/?background=000&color=fff&name='.$record->name)
                            ->size(180)
                            ->circular()
                            ->columnSpan(1),
                        Group::make()
                            ->schema([
                                TextEntry::make('code')
                                    ->label(__('app.fields.member_code')),
                                TextEntry::make('name')->label(__('app.fields.name')),
                                TextEntry::make('gender')->label(__('app.fields.gender')),
                                TextEntry::make('email')->label(__('app.fields.email')),
                                TextEntry::make('contact')->label(__('app.fields.contact')),
                                TextEntry::make('emergency_contact')->label(__('app.fields.emergency_contact'))->placeholder(__('app.placeholders.na')),
                                TextEntry::make('dob')
                                    ->label(__('app.fields.dob'))
                                    ->date('d-m-Y'),
                                TextEntry::make('source')
                                    ->label(__('app.fields.source'))
                                    ->placeholder(__('app.placeholders.na')),


                            ])->columnSpan(4)->columns(3),
                    ])->columns(5),
                Section::make(__('app.ui.location'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('address')->label(__('app.fields.address')),
                        Group::make()
                            ->schema([
                                TextEntry::make('country')->label(__('app.fields.country')),
                                TextEntry::make('state')
                                    ->label(__('app.fields.state'))
                                    ->placeholder(__('app.placeholders.na')),
                                TextEntry::make('city')
                                    ->label(__('app.fields.city'))
                                    ->placeholder(__('app.placeholders.na')),
                                TextEntry::make('pincode')->label(__('app.fields.pincode')),
                            ])
                            ->columnSpan(2)
                            ->columns(4),
                    ]),

            Section::make('INASA Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('isf_id')
                            ->label('ISF ID')
                            ->placeholder('—'),
                        TextEntry::make('current_rating')
                            ->label('Current Rating')
                            ->placeholder('—'),
                        TextEntry::make('division.name')
                            ->label('Division')
                            ->placeholder('—'),
                        TextEntry::make('nationality')
                            ->label('Nationality')
                            ->placeholder('—'),
                        TextEntry::make('skill_level')
                            ->label('Skill Level')
                            ->placeholder('—'),
                        IconEntry::make('is_coach')
                            ->label('Is Coach')
                            ->boolean(),
                    ]),
                Section::make('Coach Details')
                    ->columns(2)
                    ->visible(fn (Member $record): bool => (bool) $record->is_coach)
                    ->schema([
                        TextEntry::make('coachDetail.specialty')
                            ->label('Specialty')
                            ->placeholder('—'),
                        TextEntry::make('coachDetail.hourly_rate')
                            ->label('Hourly Rate')
                            ->money(Helpers::getCurrencyCode())
                            ->placeholder('—'),
                        TextEntry::make('coachDetail.bio')
                            ->label('Bio')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
