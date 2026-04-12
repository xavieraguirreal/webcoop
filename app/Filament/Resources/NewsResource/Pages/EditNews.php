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
                ->requiresConfirmation()
                ->modalHeading(fn () => 'Traducir artículo a ' . config("translatable.locale_names.{$this->activeLocale}", $this->activeLocale))
                ->modalDescription('Se traducirán título, extracto, contenido y meta tags del español. Las traducciones existentes se sobrescribirán.')
                ->action(function () {
                    $locale = $this->activeLocale;
                    $record = $this->getRecord();
                    $fields = ['title', 'excerpt', 'body', 'meta_title', 'meta_description'];

                    try {
                        $sourceTexts = [];
                        foreach ($fields as $field) {
                            $sourceTexts[$field] = $record->getTranslation($field, 'es', false) ?? '';
                        }

                        $translations = TranslationService::translateFields($sourceTexts, 'es', $locale);

                        $formData = $this->data;
                        foreach ($translations as $field => $value) {
                            if (!empty($value)) {
                                $formData[$field] = $value;
                            }
                        }
                        $this->data = $formData;

                        Notification::make()
                            ->title("Traducido a " . config("translatable.locale_names.{$locale}", $locale))
                            ->body('Revisá los campos y guardá cuando estés conforme.')
                            ->success()
                            ->send();

                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title("Error al traducir a {$locale}")
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => config('services.openai.api_key') && $this->activeLocale !== 'es'),

            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Actions\Action::make('saveAndClose')
                ->label('Guardar y cerrar')
                ->color('success')
                ->action(function () {
                    $this->save();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
            $this->getCancelFormAction(),
        ];
    }
}
