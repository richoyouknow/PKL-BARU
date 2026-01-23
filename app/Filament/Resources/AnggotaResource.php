<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Anggota;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\AnggotaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnggotaResource\RelationManagers;
use App\Models\User;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Anggota';

    protected static ?string $modelLabel = 'Anggota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Akun')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('user_id')
                                    ->label('User')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->unique()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->required()
                                            ->confirmed()
                                            ->minLength(8),
                                        Forms\Components\TextInput::make('password_confirmation')
                                            ->password()
                                            ->required()
                                            ->minLength(8),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        $user = User::create([
                                            'name' => $data['name'],
                                            'email' => $data['email'],
                                            'password' => bcrypt($data['password']),
                                        ]);
                                        return $user->id;
                                    }),

                                FileUpload::make('foto')
                                    ->label('Foto Anggota')
                                    ->image()
                                    ->avatar()
                                    ->directory('anggota-fotos')
                                    ->imageEditor()
                                    ->maxSize(2048),
                            ]),

                        Section::make('Data Registrasi')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('no_registrasi')
                                            ->label('No. Registrasi')
                                            ->content(function ($record) {
                                                return $record?->no_registrasi ?? 'Akan di-generate otomatis';
                                            })
                                            ->extraAttributes(['class' => 'text-lg font-bold']),

                                        Placeholder::make('no_anggota')
                                            ->label('No. Anggota')
                                            ->content(function ($record) {
                                                if ($record?->grup_wilayah === 'Anggota') {
                                                    return $record?->no_anggota ?? 'Akan di-generate otomatis';
                                                }
                                                return 'Hanya untuk status Anggota';
                                            })
                                            ->extraAttributes(['class' => 'text-lg font-bold']),

                                        Placeholder::make('tanggal_daftar')
                                            ->label('Tanggal Daftar')
                                            ->content(function ($record) {
                                                return $record?->tanggal_daftar?->format('d/m/Y') ?? now()->format('d/m/Y');
                                            })
                                            ->extraAttributes(['class' => 'text-lg']),

                                        Select::make('grup_wilayah')
                                            ->label('Grup Wilayah')
                                            ->options([
                                            'Karyawan Koperasi' => 'Karyawan Koperasi',
                                            'Karyawan PKWT' => 'Karyawan PKWT',
                                            'Karyawan Tetap' => 'Karyawan Tetap',
                                            'Non Karyawan' => 'Non Karyawan',
                                            'Outsourcing' => 'Outsourcing',
                                            'Pensiun' => 'Pensiun',
                                            'Petugas Gudang Pengolah' => 'Petugas Gudang Pengolah',
                                            ])
                                            ->default('Non Karyawan')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state !== 'Karyawan Koperasi') { // ✅ contoh kondisi valid
                                                    $set('no_anggota', null);
                                                }
                                            }),
                                    ]),
                            ]),
                    ]),

                Section::make('Data Pribadi')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(20)
                            ->nullable(),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Pria' => 'Pria',
                                'Wanita' => 'Wanita',
                            ])
                            ->nullable(),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->maxLength(50)
                            ->nullable(),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->nullable(),

                        Select::make('agama')
                            ->label('Agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu',
                            ])
                            ->nullable(),
                    ]),

                Section::make('Data Identitas')
                    ->columns(3)
                    ->schema([
                        Select::make('jenis_identitas')
                            ->label('Jenis Identitas')
                            ->options([
                                'KTP' => 'KTP',
                                'SIM' => 'SIM',
                                'Passport' => 'Passport',
                                'Kartu Pelajar' => 'Kartu Pelajar',
                            ])
                            ->nullable(),

                        Forms\Components\TextInput::make('no_identitas')
                            ->label('No. Identitas')
                            ->maxLength(30)
                            ->nullable(),

                        Forms\Components\DatePicker::make('berlaku_sampai')
                            ->label('Berlaku Sampai')
                            ->nullable(),
                    ]),

                Section::make('Data Pekerjaan & Keuangan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->maxLength(50)
                            ->nullable(),

                        Forms\Components\TextInput::make('nama_pasangan')
                            ->label('Nama Pasangan')
                            ->maxLength(100)
                            ->nullable(),

                        Forms\Components\TextInput::make('pendapatan')
                            ->label('Pendapatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->inputMode('decimal')
                            ->step(1000)
                            ->nullable(),

                        Forms\Components\Textarea::make('alamat_kantor')
                            ->label('Alamat Kantor')
                            ->rows(2)
                            ->maxLength(65535)
                            ->nullable(),
                    ]),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->maxLength(65535)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->size(70),


                TextColumn::make('no_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('grup_wilayah')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Calon Anggota',
                        'gray' => 'Nasabah Non Anggota',
                        'success' => 'Anggota',
                    ])
                    ->sortable(),

                TextColumn::make('no_telepon')
                    ->label('Telepon')
                    ->searchable(),

                TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grup_wilayah')
                    ->label('Grup Wilayah')
                    ->options([
                        'Karyawan Koperasi' => 'Karyawan Koperasi',
                        'Karyawan PKWT' => 'Karyawan PKWT',
                        'Karyawan Tetap' => 'Karyawan Tetap',
                        'Non Karyawan' => 'Non Karyawan',
                        'Outsourcing' => 'Outsourcing',
                        'Pensiun' => 'Pensiun',
                        'Petugas Gudang Pengolah' => 'Petugas Gudang Pengolah',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            // 'index' => Pages\ListAnggotas::route('/'),
            // 'create' => Pages\CreateAnggota::route('/create'),
            // 'view' => Pages\ViewAnggota::route('/{record}'),
            // 'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'view' => Pages\ViewAnggota::route('/{record}'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
