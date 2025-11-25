<?php

namespace App\Filament\Admin\Resources\Modules\Schemas;

use App\Filament\Admin\Resources\Modules\Pages\CreateModule;
use App\Filament\Admin\Resources\Modules\Pages\EditModule;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('course_id')
                        ->relationship('course', 'name')
                        ->default(fn (EditModule|CreateModule|RelationManager $livewire) => $livewire instanceof RelationManager
                            ? $livewire->getOwnerRecord()->getKey()
                            : null)
                        ->disabled(fn (EditModule|CreateModule|RelationManager $livewire) => $livewire instanceof RelationManager)
                        ->required(),
                    TextInput::make('name')
                        ->required(),
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('position')
                        ->required()
                        ->numeric(),
                    TextInput::make('duration')
                        ->numeric(),
                ])->columns(2)
            ])->columns(1);
    }
}
