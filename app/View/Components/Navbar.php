<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Navbar extends Component
{
    public string $pageTitle;

    public function __construct(Request $request)
    {
        $this->pageTitle = match(true) {
            $request->is('orders*') => 'Orders',
            $request->is('book-order*') => 'Book Order',
            $request->is('reports*') => 'Report',
            $request->is('categories*') => 'Category',
            $request->is('products*') => 'Product',
            $request->is('report-auth*') => 'Report',
            $request->is('account*') => 'Account',
            default => 'Dashboard'
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
