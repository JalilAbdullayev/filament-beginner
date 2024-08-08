<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager {
    protected static string $relationship = 'posts';

    public function form(Form $form): Form {
        return $form->schema([
            Section::make('Create a Post')
                ->description('Create posts over here.')
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    ColorPicker::make('color')->required(),

                    MarkdownEditor::make('content')->required()->columnSpanFull(),
                ])->columnSpan(2)->columns(2),
            Group::make()->schema([
                Section::make('Image')->collapsible()->schema([
                    FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                ])->columnSpan(1),
                Section::make('Meta')->schema([
                    TagsInput::make('tags')->required(),
                    Checkbox::make('published')
                ])
            ])
        ])->columns(3);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('thumbnail')->toggleable(),
                ColorColumn::make('color')->toggleable(),
                TextColumn::make('title')->sortable()->searchable()->toggleable(),
                TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                TextColumn::make('tags')->toggleable(),
                CheckboxColumn::make('published')->toggleable(),
                TextColumn::make('created_at')->label('Published on')->date()->sortable()->searchable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
