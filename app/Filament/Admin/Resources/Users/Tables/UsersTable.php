<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Notifications\WelcomeNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
//use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('role.name')
                    ->label('Perfil')
                    ->collapsible()
            ])
            ->defaultGroup('role.name')
            ->columns([
                TextColumn::make('role.name')
                    ->label('Papel')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('document')
                    ->label('CPF')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('send_welcome_email')
                    ->label('Enviar E-mail de Boas-Vindas')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar E-mail de Boas-Vindas')
                    ->modalDescription('Tem certeza que deseja enviar o e-mail de boas-vindas para este usuário? Ele receberá um link para criar sua senha.')
                    ->modalSubmitActionLabel('Sim, enviar e-mail')
                    ->action(function ($record) {
                        try {
                            $record->notify(new WelcomeNotification($record));

                            Notification::make()
                                ->success()
                                ->title('E-mail enviado!')
                                ->body("E-mail de boas-vindas enviado com sucesso para {$record->email}")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Erro ao enviar e-mail')
                                ->body('Ocorreu um erro ao enviar o e-mail: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
