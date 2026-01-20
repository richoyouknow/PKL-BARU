<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Slider Iklan';

    protected static ?string $navigationGroup = 'Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul (Opsional)')
                    ->maxLength(255),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Slider')
                    ->helperText('Ukuran rekomendasi: 1920x1080px (16:9) atau 1200x800px. Maksimal 2MB.')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('sliders')
                    ->visibility('public')
                    ->maxSize(2048) // 2MB
                    ->imageResizeMode('cover') // crop, contain, cover, force
                    ->imageCropAspectRatio('16:9') // Ratio yang diinginkan
                    ->imageResizeTargetWidth('1920') // Lebar target
                    ->imageResizeTargetHeight('1080') // Tinggi target
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null, // bebas
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->imageEditorMode(2) // 1=crop, 2=resize
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                // CARA 1: Menggunakan disk public
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Gambar')
                    ->getStateUsing(function ($record) {
                        return asset('storage/' . $record->image);
                    })
                    ->square()
                    ->size(70),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->placeholder('Tidak ada judul')
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
