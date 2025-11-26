<?php

namespace App\Filament\Admin\Resources\Lessons;

use App\Filament\Admin\Resources\Lessons\Pages\CreateLesson;
use App\Filament\Admin\Resources\Lessons\Pages\EditLesson;
use App\Filament\Admin\Resources\Lessons\Pages\ListLessons;
use App\Filament\Admin\Resources\Lessons\RelationManagers\MaterialsRelationManager;
use App\Filament\Admin\Resources\Lessons\Schemas\LessonForm;
use App\Filament\Admin\Resources\Lessons\Tables\LessonsTable;
use App\Models\Lesson;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = "Aula";
    protected static string | \UnitEnum | null $navigationGroup = "Produto";
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return LessonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MaterialsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLessons::route('/'),
            'create' => CreateLesson::route('/create'),
            'edit' => EditLesson::route('/{record}/edit'),
        ];
    }
}
