<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'User & Anggota';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'User & Anggota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'anggota' => 'Anggota',
                            ])
                            ->required()
                            ->disabled(fn ($record) => $record && $record->anggota),
                        Forms\Components\Select::make('status')
                            ->options([
                                'verify' => 'Menunggu Verifikasi',
                                'active' => 'Aktif',
                                'banned' => 'Dibanned',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Kontak')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat User')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'anggota' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'anggota' => 'Anggota',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verify' => 'warning',
                        'active' => 'success',
                        'banned' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'verify' => 'Menunggu',
                        'active' => 'Aktif',
                        'banned' => 'Banned',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('anggota.no_registrasi')
                    ->label('No. Registrasi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('anggota.no_telepon')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->label('Tanggal Daftar')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'anggota' => 'Anggota',
                    ])
                    ->label('Role'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'verify' => 'Menunggu Verifikasi',
                        'active' => 'Aktif',
                        'banned' => 'Dibanned',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('has_anggota')
                    ->label('Hanya Anggota')
                    ->query(fn (Builder $query): Builder => $query->has('anggota')),
            ])
            ->actions([
                // Action untuk verifikasi
                Tables\Actions\Action::make('verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi User')
                    ->modalDescription('Apakah Anda yakin ingin memverifikasi user ini?')
                    ->modalSubmitActionLabel('Ya, Verifikasi')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                        // Notification::send($record, new AccountVerifiedNotification());
                    })
                    ->visible(fn (User $record): bool => $record->status === 'verify'),

                // Action untuk ban
                Tables\Actions\Action::make('ban')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan User')
                    ->modalDescription('Apakah Anda yakin ingin menonaktifkan user ini?')
                    ->action(function (User $record) {
                        $record->update(['status' => 'banned']);
                    })
                    ->visible(fn (User $record): bool => $record->status === 'active'),

                // Action untuk unban
                Tables\Actions\Action::make('unban')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Kembali User')
                    ->modalDescription('Apakah Anda yakin ingin mengaktifkan kembali user ini?')
                    ->action(function (User $record) {
                        $record->update(['status' => 'active']);
                    })
                    ->visible(fn (User $record): bool => $record->status === 'banned'),

                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('verifySelected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => 'active']);
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('banSelected')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => 'banned']);
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // Di dalam class UserResource, tambahkan method ini
            public static function viewForm(Form $form): Form
            {
                return $form
                    ->schema([
                        Forms\Components\Tabs::make('User Details')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Informasi Akun')
                                    ->icon('heroicon-o-user')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('name')
                                                        ->label('Nama Lengkap')
                                                        ->content(fn ($record): string => $record->name),
                                                    Forms\Components\Placeholder::make('email')
                                                        ->label('Email')
                                                        ->content(fn ($record): string => $record->email),
                                                    Forms\Components\Placeholder::make('role')
                                                        ->label('Role')
                                                        ->content(fn ($record): string => match ($record->role) {
                                                            'admin' => 'Admin',
                                                            'anggota' => 'Anggota',
                                                            default => $record->role,
                                                        }),
                                                ]),

                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('status')
                                                        ->label('Status')
                                                        ->content(fn ($record): string => match ($record->status) {
                                                            'verify' => 'Menunggu Verifikasi',
                                                            'active' => 'Aktif',
                                                            'banned' => 'Dibanned',
                                                            default => $record->status,
                                                        }),
                                                    Forms\Components\Placeholder::make('created_at')
                                                        ->label('Tanggal Daftar')
                                                        ->content(fn ($record): string => $record->created_at->format('d/m/Y H:i')),
                                                    Forms\Components\Placeholder::make('email_verified_at')
                                                        ->label('Email Verified At')
                                                        ->content(fn ($record): ?string => $record->email_verified_at?->format('d/m/Y H:i')),
                                                ]),
                                            ]),
                                    ]),

                                Forms\Components\Tabs\Tab::make('Data Anggota')
                                    ->icon('heroicon-o-identification')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('no_registrasi')
                                                        ->label('No. Registrasi')
                                                        ->content(fn ($get): ?string => $get('no_registrasi')),
                                                    Forms\Components\Placeholder::make('no_anggota')
                                                        ->label('No. Anggota')
                                                        ->content(fn ($get): ?string => $get('no_anggota') ?? '-'),
                                                    Forms\Components\Placeholder::make('nama_anggota')
                                                        ->label('Nama')
                                                        ->content(fn ($get): ?string => $get('nama_anggota')),
                                                    Forms\Components\Placeholder::make('jenis_kelamin')
                                                        ->label('Jenis Kelamin')
                                                        ->content(fn ($get): ?string => $get('jenis_kelamin')),
                                                ]),

                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('tempat_lahir')
                                                        ->label('Tempat Lahir')
                                                        ->content(fn ($get): ?string => $get('tempat_lahir')),
                                                    Forms\Components\Placeholder::make('tanggal_lahir')
                                                        ->label('Tanggal Lahir')
                                                        ->content(fn ($get): ?string => $get('tanggal_lahir')),
                                                    Forms\Components\Placeholder::make('agama')
                                                        ->label('Agama')
                                                        ->content(fn ($get): ?string => $get('agama')),
                                                    Forms\Components\Placeholder::make('nama_pasangan')
                                                        ->label('Nama Pasangan')
                                                        ->content(fn ($get): ?string => $get('nama_pasangan') ?? '-'),
                                                ]),
                                            ]),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('jenis_identitas')
                                                        ->label('Jenis Identitas')
                                                        ->content(fn ($get): ?string => $get('jenis_identitas')),
                                                    Forms\Components\Placeholder::make('no_identitas')
                                                        ->label('No. Identitas')
                                                        ->content(fn ($get): ?string => $get('no_identitas')),
                                                    Forms\Components\Placeholder::make('no_telepon_anggota')
                                                        ->label('No. Telepon')
                                                        ->content(fn ($get): ?string => $get('no_telepon_anggota')),
                                                ]),

                                                Forms\Components\Group::make([
                                                    Forms\Components\Placeholder::make('grup_wilayah')
                                                        ->label('Grup/Wilayah')
                                                        ->content(fn ($get): ?string => $get('grup_wilayah')),
                                                    Forms\Components\Placeholder::make('pekerjaan')
                                                        ->label('Pekerjaan')
                                                        ->content(fn ($get): ?string => $get('pekerjaan')),
                                                    Forms\Components\Placeholder::make('pendapatan')
                                                        ->label('Pendapatan')
                                                        ->content(fn ($get): ?string => $get('pendapatan') ? 'Rp ' . number_format($get('pendapatan'), 0, ',', '.') : '-'),
                                                ]),
                                            ]),

                                        Forms\Components\Placeholder::make('alamat_anggota')
                                            ->label('Alamat Lengkap')
                                            ->content(fn ($get): ?string => $get('alamat_anggota'))
                                            ->columnSpanFull(),

                                        Forms\Components\Placeholder::make('alamat_kantor')
                                            ->label('Alamat Kantor')
                                            ->content(fn ($get): ?string => $get('alamat_kantor') ?? '-')
                                            ->columnSpanFull(),

                                        Forms\Components\Placeholder::make('keterangan')
                                            ->label('Keterangan')
                                            ->content(fn ($get): ?string => $get('keterangan') ?? '-')
                                            ->columnSpanFull(),

                                        // Tampilkan foto jika ada
                                        Forms\Components\Fieldset::make('Foto Profil')
                                            ->schema([
                                                Forms\Components\Placeholder::make('foto')
                                                    ->label('Foto')
                                                    ->content(function ($get) {
                                                        $fotoUrl = $get('foto_url');
                                                        if (!$fotoUrl) {
                                                            return 'Tidak ada foto';
                                                        }

                                                        return view('filament.components.foto-preview', [
                                                            'url' => $fotoUrl
                                                        ])->render();
                                                    })
                                                    ->columnSpanFull(),
                                            ])
                                            ->visible(fn ($get): bool => !empty($get('foto_url'))),
                                    ])
                                    ->visible(fn ($record): bool => $record->anggota !== null),

                                Forms\Components\Tabs\Tab::make('Alamat User')
                                    ->icon('heroicon-o-home')
                                    ->schema([
                                        Forms\Components\Placeholder::make('alamat')
                                            ->label('Alamat')
                                            ->content(fn ($record): ?string => $record->alamat ?? '-')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]);
            }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relation manager untuk anggota
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('anggota')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'verify')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
