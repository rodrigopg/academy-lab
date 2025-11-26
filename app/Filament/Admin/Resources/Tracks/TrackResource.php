<?php

namespace App\Filament\Admin\Resources\Tracks;

use App\Filament\Admin\Resources\Tracks\Pages\CreateTrack;
use App\Filament\Admin\Resources\Tracks\Pages\EditTrack;
use App\Filament\Admin\Resources\Tracks\Pages\ListTracks;
use App\Filament\Admin\Resources\Tracks\Schemas\TrackForm;
use App\Filament\Admin\Resources\Tracks\Tables\TracksTable;
use App\Models\Track;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = "Track";
    protected static string | \UnitEnum | null $navigationGroup = "Produto";
    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return TrackForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TracksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTracks::route('/'),
            'create' => CreateTrack::route('/create'),
            'edit' => EditTrack::route('/{record}/edit'),
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
