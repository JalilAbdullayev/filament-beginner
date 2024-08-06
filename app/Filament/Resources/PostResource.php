<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource {
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                //* ColumnSpanFull() bütün row-u tutur.
                //* Section card yaradır.
                //* Section içindəki input-lar standard olaraq columnSpanFull olur.
                //* make içindəki yazı card-ın başlığıdır.
                //*description alt başlıqdır.
                //* collapsible() card-ın açılıb bağlana bilməyini təmin edir.
                //* aside() card başlıqlarını sola input-ları isə card daxilində sağa aparır. aside-da collapsible() işləmir.
                Section::make('Create a Post')
                    ->description('Create posts over here.')
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('slug')->required(),
                        Select::make('category_id')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->label('Category')
                            ->required(),
                        ColorPicker::make('color')->required(),

                        MarkdownEditor::make('content')->required()->columnSpanFull(),
                    ])->columnSpan(2)->columns(2),
                Group::make()->schema([
                    Section::make('Image')->collapsible()->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                    ])->columnSpan(1),
                    Section::make('Meta')->schema([
                        TagsInput::make('tags')->required(),
                        Checkbox::make('published')->required()
                    ])
                ])
            ])->columns(3/* Responsive: [
                'default' => 1,
                'md' => 2,
                'lg' => 3,
                'xl' => 4
            ]*/);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumn::make('thumbnail'),
                ColorColumn::make('color'),
                TextColumn::make('title'),
                TextColumn::make('slug'),
                TextColumn::make('category.name'),
                TextColumn::make('tags'),
                CheckboxColumn::make('published')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
