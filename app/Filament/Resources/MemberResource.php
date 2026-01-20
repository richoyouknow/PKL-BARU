<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Anggota DEN';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Anggota DEN';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Anggota')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('position')
                            ->label('Posisi/Jabatan')
                            ->default('Anggota')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('members')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:5',
                            ])
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Semakin kecil angka, semakin di depan urutannya')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media Sosial')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/username'),

                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/username'),

                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/username'),

                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://linkedin.com/in/username'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                ->label('Gambar')
                ->getStateUsing(fn ($record) =>
                    $record->photo
                        ? asset('storage/' . $record->photo)
                        : null
                )
                ->square()
                ->size(70),


                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Posisi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
