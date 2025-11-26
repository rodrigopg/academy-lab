<?php

namespace App\Filament\Admin\Resources\Tracks\Schemas;

use App\Filament\Admin\Resources\Courses\Schemas\CourseForm;
use App\Models\Course;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TrackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Trilha')
                            ->schema([
                                Section::make([
                                    TextInput::make('name')
                                        ->required(),
                                    RichEditor::make('description')
                                        ->columnSpanFull(),
                                ])
                            ])->columns(1),
                        Tab::make('Cursos')
                            ->schema([
                                Section::make([
                                    Repeater::make('trackCourses')
                                        ->label('Cursos')
                                        ->relationship('trackCourses')
                                        ->itemLabel(fn($state): string => Course::query()->whereKey($state['course_id'])->value('name') ?? 'Selecione o curso'
                                        )
                                        ->table([
                                            Repeater\TableColumn::make('Nome'),
                                        ])
                                        ->schema([
                                            Select::make('course_id')
                                                ->searchable()
                                                ->preload()
                                                ->relationship('course', 'name')
                                                ->createOptionForm(fn($schema) => CourseForm::configure($schema))
                                                ->live()
                                                ->required()
                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        ])
                                    ->addActionLabel('Adicionar novo Curso')
                                    ->collapsible()
                                ]),

                            ])->columns(1),
                    ])
            ])->columns(1);
    }
}
