<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    FileUpload::make('cover')
                        ->columnSpanFull()
                        ->required(),
                    TextInput::make('name')
                        ->lazy()
                        ->afterStateUpdated(function( $state, callable $set){
                            $set('slug', Str::slug($state));
                        })
                        ->required(),
                    TextInput::make('slug')
                        ->required(),
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2)
            ])->columns(1);
    }
}
