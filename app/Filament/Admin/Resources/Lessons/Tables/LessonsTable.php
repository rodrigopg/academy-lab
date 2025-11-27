<?php

namespace App\Filament\Admin\Resources\Lessons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('module.name')
                ->label('Módulo')
                ->collapsible()
            ])
            ->defaultGroup('module.name')
            ->columns([
                TextColumn::make('module.name')
                    ->searchable()
                ->hidden(),
//                TextColumn::make('panda_id')
//                    ->searchable(),
//                TextColumn::make('panda_player_url')
//                    ->searchable(),
//                TextColumn::make('panda_thumbnail_url')
//                    ->searchable(),
//                TextColumn::make('transcription_url')
//                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
//                TextColumn::make('slug')
//                    ->searchable(),
                TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('position')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
