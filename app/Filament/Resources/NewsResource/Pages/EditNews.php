<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use App\Services\TranslationService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditNews extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

            Actions\Action::make('translateWithAI')
                ->label('Traducir con IA')
                ->icon('heroicon-o-language')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\CheckboxList::make('target_locales')
                        ->label('Traducir a:')
                        ->options(
                            collect(config('translatable.locale_names', []))
                                ->except('es')
                                ->toArray()
                        )
                        ->columns(2)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $record = $this->getRecord();
                    $fields = ['title', 'excerpt', 'body', 'meta_title', 'meta_description'];

                    foreach ($data['target_locales'] as $locale) {
                        try {
                            $sourceTexts = [];
                            foreach ($fields as $field) {
                                $sourceTexts[$field] = $record->getTranslation($field, 'es', false) ?? '';
                            }

                            $translations = TranslationService::translateFields($sourceTexts, 'es', $locale);

                            foreach ($translations as $field => $value) {
                                if (!empty($value)) {
                                    $record->setTranslation($field, $locale, $value);
                                }
                            }

                            $record->save();

                            Notification::make()
                                ->title("Traducido a " . config("translatable.locale_names.{$locale}", $locale))
                                ->success()
                                ->send();

                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title("Error al traducir a {$locale}")
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Traducir artículo con IA')
                ->modalDescription('Se traducirán título, extracto, contenido y meta tags del español a los idiomas seleccionados. Las traducciones existentes se sobrescribirán.')
                ->visible(fn () => config('services.openai.api_key')),

            Actions\DeleteAction::make(),
        ];
    }
}
