<?php

namespace App\Filament\Admin\Resources\Modules;

use App\Filament\Admin\Resources\Modules\Pages\CreateModule;
use App\Filament\Admin\Resources\Modules\Pages\EditModule;
use App\Filament\Admin\Resources\Modules\Pages\ListModules;
use App\Filament\Admin\Resources\Modules\RelationManagers\LessonsRelationManager;
use App\Filament\Admin\Resources\Modules\Schemas\ModuleForm;
use App\Filament\Admin\Resources\Modules\Tables\ModulesTable;
use App\Models\Module;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = "Modulo";
    protected static string | \UnitEnum | null $navigationGroup = "Produto";
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModules::route('/'),
            'create' => CreateModule::route('/create'),
            'edit' => EditModule::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
