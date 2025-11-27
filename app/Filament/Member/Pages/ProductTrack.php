<?php

namespace App\Filament\Member\Pages;

use App\Models\Product;
use Filament\Pages\Page;

class ProductTrack extends Page
{
    public Product $product;

    protected string $view = 'filament.member.pages.product-track';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'produto/{product}';
    protected static ?string $title = '';

    public static function canAccess(): bool
    {
        $product = request()->route('product');

        return auth()->user()->can('view', $product);
    }

    public function mount(Product $product): void
    {
        $this->product = $product->load([
            'productTracks' => fn ($q) => $q->orderBy('position'),
            'productTracks.track',
            'productTracks.trackCourses',
            'productTracks.trackCourses.course',
            'productCourses' => fn ($q) => $q->orderBy('position'),
            'productCourses.course'
        ]);
    }



}
