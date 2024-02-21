@if (filled($brand = config('filament.brand')))
    <div @class([
        'filament-brand text-xl font-bold tracking-tight',
        'dark:text-white' => config('filament.dark_mode'),
    ])>
        {{ $brand }}
    </div>
@endif
<style>
    header.filament-main-topbar, .filament-sidebar-header {
        background-color: #d5bb9f!important;
    }
</style>
