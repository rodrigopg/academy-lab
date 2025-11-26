<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Filament\Admin\Resources\Courses\Schemas\CourseForm;
use App\Filament\Admin\Resources\Tracks\Schemas\TrackForm;
use App\Models\Course;
use App\Models\Track;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Produto')
                            ->schema([
                                FileUpload::make('cover')
                                    ->label('Capa do Produto')
                                    ->columnSpanFull(),
                                TextInput::make('eduzz_id')
                                    ->required()
                                    ->label('ID do produto na eduzz'),
                                TextInput::make('name')
                                    ->label('Nome do produto')
                                    ->placeholder('Nome do produto. Ex: Clã Beer And Code')
                                    ->lazy()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                                    ->required(),
                                TextInput::make('slug')
                                    ->placeholder('Link do produto')
                                    ->required(),
                                TextInput::make('redirect_url')
                                    ->label('Url de redirect')
                                    ->placeholder('Url para onde o usuario sera levado caso nao tenha o produto')
                                    ->required(),
                                Toggle::make('featured')
                                    ->label("Produto em destaque?"),
                                RichEditor::make('description')
                                    ->label('Descrição')
                                    ->columnSpanFull(),
                            ])->columns(3),
                        Tab::make('Configuração')
                            ->hiddenOn("create")
                            ->schema([
                                Repeater::make('productTracks')
                                    ->label('Trilhas')
                                    ->relationship('productTracks')
                                    ->itemLabel(fn ($state): string =>
                                        Track::query()->whereKey($state['track_id'])->value('name') ?? 'Selecione a trilha'
                                    )
                                    ->schema([
                                        Select::make('track_id')
                                            ->relationship('track', 'name')
                                            ->createOptionForm(fn ($schema) => TrackForm::configure($schema))
                                            ->live()
                                            ->required()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        Repeater::make('productTrackCourses')
                                            ->label('Cursos')
                                            ->relationship('productTrackCourses')
                                            ->itemLabel(fn ($state): string =>
                                                Course::query()->whereKey($state['course_id'])->value('name') ?? 'Selecione o curso'
                                            )
                                            ->table([
                                                Repeater\TableColumn::make('Nome'),
                                            ])
                                            ->schema([
                                                Select::make('course_id')
                                                    ->searchable()
                                                    ->preload()
                                                    ->relationship('course', 'name')
                                                    ->createOptionForm(fn ($schema) => CourseForm::configure($schema))
                                                    ->live()
                                                    ->required()
                                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                            ])
                                            ->compact()
                                            ->hidden(fn (Get $get) => blank($get('track_id')))
                                            ->orderColumn('position')
                                            ->addActionLabel('Adicionar novo Curso')
                                            ->extraItemActions([
                                                Action::make('configure_course')
                                                    ->icon(Heroicon::OutlinedLink)
                                                    ->action(function (array $arguments, Repeater $component) {
                                                        if (str_contains($arguments['item'], 'record')) {
                                                            $state = $component->getState();
                                                            $state = $state[$arguments['item']];
                                                            return redirect()->route('filament.admin.resources.courses.edit', $state['course_id']);
                                                        }
                                                    })
                                            ])
                                    ])
                                    ->reorderable('position')
                                    ->collapsible()
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->defaultItems(1)
                                    ->addActionLabel('Adicionar nova trilha')

                            ])->columns(3),
                    ])

            ])->columns(1);
    }
}
