<?php

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use FilamentTiptapEditor\Tests\Fixtures\Livewire as LivewireFixture;
use FilamentTiptapEditor\Tests\Models\Page;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\CreatePage;
use FilamentTiptapEditor\Tests\Resources\PageResource\Pages\EditPage;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Contracts\View\View;
use Livewire\Livewire;

it('has editor field', function () {
    Livewire::test(TestComponentWithForm::class)
        ->assertFormFieldExists('html_content')
        ->assertFormFieldExists('json_content')
        ->assertFormFieldExists('text_content');
});

it('has proper html', function () {
    $page = Page::factory()->make();

    Livewire::test(TestComponentWithForm::class)
        ->fillForm($page->toArray())
        ->assertFormSet([
            'html_content' => $page->html_content,
            'json_content' => $page->json_content,
            'text_content' => $page->text_content,
        ]);
});

it('creates proper data', function () {
    $page = Page::factory()->make();

    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => $page->title,
            'html_content' => $page->html_content,
            'json_content' => $page->json_content,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Page::class, [
        'title' => $page->title,
        'html_content' => $page->html_content,
        'json_content' => $page->json_content,
    ]);
});

it('updates proper html', function () {
    $page = Page::factory()->create();
    $newData = Page::factory()->make();

    Livewire::test(EditPage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'html_content' => $newData->html_content,
            'json_content' => $newData->json_content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh())
        ->html_content->toBe($newData->html_content)
        ->json_content->toBe($newData->json_content);
});

class TestComponentWithForm extends LivewireFixture
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title'),
                TiptapEditor::make('html_content'),
                TiptapEditor::make('json_content')
                    ->output(\FilamentTiptapEditor\Enums\TiptapOutput::Json),
                TiptapEditor::make('text_content')
                    ->output(\FilamentTiptapEditor\Enums\TiptapOutput::Text),
            ]);
    }

    public function render(): View
    {
        return view('fixtures.form');
    }
}
