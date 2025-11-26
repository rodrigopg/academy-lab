<?php

namespace App\Filament\Admin\Resources\Comments\Tables;

use App\Models\Comment;
use App\Services\ChatGptService;
use App\Services\CommentService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('lesson.name')
                        ->weight(FontWeight::Bold)
                        ->searchable(),
                    TextColumn::make('content')
                        ->html()
                        ->searchable(),
                    TextColumn::make('user.name')
                        ->searchable(),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->since(),
                ]),
            ])
            ->filters([
                Filter::make('read_at')
                    ->label('Não lidos')
                    ->default()
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('read_at'))
            ])
            ->recordUrl('')
            ->recordActions([
                Action::make('reply')
                    ->label('Responder')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make([
                                    TextEntry::make('user.name')
                                        ->label('Aluno')
                                        ->columnSpan(2),
                                    TextEntry::make('user.products.name')
                                        ->label('Produtos')
                                        ->columnSpan(1),
                                ])->columns(3)->columnSpanFull(),
                                Section::make([
                                    TextEntry::make('lesson.module.course.name')
                                        ->label('Course'),
                                    TextEntry::make('lesson.module.name')
                                        ->label('Modulo'),
                                    TextEntry::make('lesson.name')
                                        ->label('Aula'),
                                ])->columns(3)->columnSpanFull(),

                            ]),

                        Hidden::make('id')
                            ->default(fn(Comment $record) => $record->id),
                        Hidden::make('lesson_id')
                            ->default(fn(Comment $record) => $record->lesson_id),

                        Section::make('Comentario pai')
                            ->hidden(fn($record) => is_null($record->parent))
                            ->schema([
                                TextEntry::make('parent.content')
                                    ->hiddenLabel()
                                    ->html(),
                            ]),

                        Section::make('Comentario')
                            ->schema([
                                TextEntry::make('content')
                                    ->hiddenLabel()
                                    ->html()
                                    ->label('Comentário'),
                            ]),


                        Grid::make(1)
                            ->schema([
                                RichEditor::make('reply_content')
                                    ->label('Sua Resposta')
                                    ->required()
                                    ->maxLength(65535),
                            ]),
                    ])
                    ->action(function (array $data, Comment $record) {

                        $record->update([
                            'read_at' => now(),
                            'status' => 'approved',
                        ]);

                        $record->replies()->create([
                            'user_id' => auth()->id(),
                            'lesson_id' => $data['lesson_id'],
                            'content' => $data['reply_content'],
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
