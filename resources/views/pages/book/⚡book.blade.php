<?php

use App\Models\Book;
use App\Models\BookBorrower;
use App\Models\Category;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $search = '';

    public string $filterByCategory = '';

    public string $searchCategory = '';

    #[Computed]
    public function books()
    {
        return Book::query()
            ->select(['id', 'title', 'author', 'isbn', 'total_copies', 'available_copies'])
            ->when(
                filled($this->filterByCategory),
                function ($query) {
                    $query->where('category_id', $this->filterByCategory);
                })
            ->when(
                filled($this->search),
                function ($query) {
                    $search = "%{$this->search}%";
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', $search)
                            ->orWhere('author', 'like', $search)
                            ->orWhere('isbn', 'like', $search);
                    });
                }
            )
            ->orderBy('title')
            ->get();
    }

    #[Computed]
    public function borrowedBookIds(): array
    {
        return Auth::user()->bookBorrows()->pluck('book_id')->all();
    }

    #[Computed]
    public function categories()
    {
        return Category::query()->when(
            filled($this->searchCategory),
            function ($query) {
                $query->where('name', 'like', "%{$this->searchCategory}%");
            }
        )->orderBy('name')->get();
    }

    #[Computed]
    public function selectedCategory(): ?Category
    {
        return filled($this->filterByCategory)
            ? Category::find($this->filterByCategory)
            : null;
    }

    public function selectCategory(string $categoryId): void
    {
        $this->filterByCategory = $categoryId;
        $this->searchCategory = '';

        unset($this->categories, $this->selectedCategory, $this->books);
    }

    public function clearSearch(): void
    {
        $this->search = '';

        unset($this->books);
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterByCategory = '';
        $this->searchCategory = '';

        unset($this->books, $this->categories, $this->selectedCategory);
    }

    public function bookBorrow(Book $book): void
    {
        Gate::authorize('create', BookBorrower::class);

        $user = Auth::user();

        // already borrowed check
        if (in_array($book->id, $this->borrowedBookIds, true)) {
            Flux::toast(variant: 'warning', text: __('You have already borrowed this book.'));

            return;
        }

        // availability check (fresh value)
        $book->refresh();
        if ((int) $book->available_copies < 1) {
            Flux::toast(variant: 'danger', text: __('This book is currently unavailable.'));

            return;
        }

        try {
            $user->bookBorrows()->create([
                'book_id' => $book->id,
                'borrow_at' => now()->startOfDay(),
            ]);

            $book->decrement('available_copies');

            Flux::toast(variant: 'success', text: __('Book borrowed successfully.'));
        } catch (\Illuminate\Database\QueryException $e) {
            // unique constraint violation (already borrowed)
            if (str_contains($e->getMessage(), 'Duplicate') || $e->getCode() === '23000') {
                Flux::toast(variant: 'warning', text: __('You have already borrowed this book.'));
            } else {
                Flux::toast(variant: 'danger', text: __('Failed to borrow book. Please try again.'));
            }
        }

        // bust computed cache so UI updates immediately
        unset($this->books, $this->borrowedBookIds);
        $this->dispatch('book-borrowed');
    }
};
?>

<div>
    <div class="mx-auto w-full max-w-6xl space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Books') }}</flux:heading>
                <flux:text class="mt-1">{{ __('Browse and borrow available books.') }}</flux:text>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <div class="w-full sm:w-56">
                    <div
                        wire:key="category-filter"
                        class="relative"
                        x-data="{ open: false }"
                        @keydown.escape.window="open = false"
                        @click.outside="open = false"
                    >
                        <button
                            type="button"
                            class="flex h-10 w-full items-center justify-between gap-2 rounded-lg border border-zinc-200 border-b-zinc-300/80 bg-white px-3 text-sm leading-[1.375rem] text-zinc-700 shadow-xs dark:border-white/10 dark:bg-white/10 dark:text-zinc-300"
                            @click="open = ! open; if (open) $nextTick(() => $refs.search?.focus())"
                            :aria-expanded="open"
                        >
                            <span class="truncate">{{ $this->selectedCategory?->name ?? __('All categories') }}</span>
                            <flux:icon.chevron-up-down micro class="text-zinc-400" />
                        </button>

                        <div
                            x-show="open"
                            style="display: none"
                            class="absolute z-20 mt-1 w-full min-w-56 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-lg dark:border-white/10 dark:bg-zinc-900"
                        >
                            <div class="border-b border-zinc-200 p-2 dark:border-white/10">
                                <flux:input
                                    x-ref="search"
                                    wire:model.live.debounce.300ms="searchCategory"
                                    placeholder="{{ __('Search categories...') }}"
                                    icon="magnifying-glass"
                                    clearable
                                />
                            </div>

                            <div class="max-h-60 overflow-y-auto p-1">
                                <button
                                    type="button"
                                    wire:click="selectCategory('')"
                                    @click="open = false"
                                    class="flex w-full cursor-pointer items-center justify-between gap-2 rounded-md px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800"
                                >
                                    <span>{{ __('All categories') }}</span>
                                    <flux:icon.check micro class="{{ filled($filterByCategory) == '' ? '' : 'hidden' }}" />
                                </button>

                                @foreach ($this->categories as $category)
                                    <button
                                        type="button"
                                        wire:key="category-option-{{ $category->id }}"
                                        wire:click="selectCategory('{{ $category->id }}')"
                                        @click="open = false"
                                        class="flex w-full cursor-pointer items-center justify-between gap-2 rounded-md px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800"
                                    >
                                        <span class="truncate">{{ $category->name }}</span>
                                        <flux:icon.check
                                            micro
                                            class="{{ (string) $filterByCategory === (string) $category->id ? '' : 'hidden' }}"
                                        />
                                    </button>
                                @endforeach

                                @if ($this->categories->isEmpty())
                                    <p wire:loading.remove class="px-3 py-6 text-center text-sm text-zinc-500" wire:target="searchCategory">
                                        {{ __('No categories found') }}
                                    </p>
                                    <p wire:loading class="px-3 py-6 text-center text-sm text-zinc-500" wire:target="searchCategory">
                                        {{ __('Loading...') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-80">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ __('Search by title, author or ISBN...') }}"
                        icon="magnifying-glass"
                        clearable
                    />
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
            <span>{{ __('Showing :count books', ['count' => $this->books->count()]) }}</span>
            @if (filled($search))
                <span>·</span>
                <flux:link
                    wire:click="clearSearch"
                    class="cursor-pointer text-sm"
                >{{ __('Clear search') }}</flux:link>
            @endif
            @if (filled($filterByCategory))
                <span>·</span>
                <flux:link
                    wire:click="selectCategory('')"
                    class="cursor-pointer text-sm"
                >{{ __('Clear filter') }}</flux:link>
            @endif
            <span class="ms-auto hidden items-center gap-1.5 sm:inline-flex">
                <span class="size-2 rounded-full bg-emerald-500"></span> {{ __('Available') }}
                <span class="ms-2 size-2 rounded-full bg-zinc-300 dark:bg-zinc-600"></span> {{ __('Unavailable') }}
            </span>
        </div>

        {{-- Books Grid --}}
        @if ($this->books->isEmpty())
            <div class="rounded-xl border border-dashed border-zinc-200 p-12 text-center dark:border-zinc-700">
                <flux:icon.book-open class="mx-auto size-8 text-zinc-300 dark:text-zinc-600" />
                <flux:heading class="mt-3">{{ __('No books found') }}</flux:heading>
                <flux:text class="mt-1">{{ (filled($search) || filled($filterByCategory)) ? __('Try adjusting your search.') : __('No books are available at the moment.') }}</flux:text>
                @if (filled($search) || filled($filterByCategory))
                    <flux:button
                        wire:click="clearFilters"
                        variant="ghost"
                        size="sm"
                        class="mt-4"
                    >{{ __('Clear filters') }}</flux:button>
                @endif
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->books as $book)
                    @php
                        $isBorrowed = in_array($book->id, $this->borrowedBookIds, true);
                        $isAvailable = (int) $book->available_copies > 0;
                        $canBorrow = $isAvailable && ! $isBorrowed;
                    @endphp
                    <div
                        class="flex flex-col rounded-xl border bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
                        wire:key="book-{{ $book->id }}"
                    >
                        <div class="flex-1 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <h3
                                    class="line-clamp-2 text-[15px] leading-tight font-semibold text-zinc-900 dark:text-white"
                                    title="{{ $book->title }}"
                                >
                                    {{ $book->title }}
                                </h3>
                                @if ($isBorrowed)
                                    <flux:badge
                                        color="green"
                                        size="sm"
                                        inset="top bottom"
                                    >{{ __('Borrowed') }}</flux:badge>
                                @elseif (! $isAvailable)
                                    <flux:badge
                                        color="zinc"
                                        size="sm"
                                        inset="top bottom"
                                    >{{ __('Out of stock') }}</flux:badge>
                                @else
                                    <flux:badge
                                        color="emerald"
                                        size="sm"
                                        inset="top bottom"
                                    >{{ __('Available') }}</flux:badge>
                                @endif
                            </div>

                            <div class="space-y-1 text-sm">
                                <p class="text-zinc-600 dark:text-zinc-400">
                                    <span class="text-zinc-400 dark:text-zinc-500">{{ __('Author:') }}</span>
                                    <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $book->author }}</span>
                                </p>
                                <p class="font-mono text-xs text-zinc-500 dark:text-zinc-500">
                                    ISBN: {{ $book->isbn }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 pt-1 text-xs">
                                <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-1 font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $book->available_copies }} / {{ $book->total_copies }} {{ __('copies') }}
                                </span>
                                @if ($isAvailable)
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ __('In stock') }}</span>
                                @else
                                    <span class="text-zinc-400">{{ __('No copies left') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 border-t border-zinc-100 pt-4 dark:border-zinc-800">
                            @if ($isBorrowed)
                                <flux:button
                                    variant="ghost"
                                    disabled
                                    icon="check"
                                    class="w-full"
                                >{{ __('Borrowed') }}</flux:button>
                            @elseif (! $isAvailable)
                                <flux:button
                                    variant="ghost"
                                    disabled
                                    icon="x-mark"
                                    class="w-full"
                                >{{ __('Unavailable') }}</flux:button>
                            @else
                                <flux:button
                                    wire:click="bookBorrow({{ $book->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="bookBorrow({{ $book->id }})"
                                    variant="primary"
                                    icon="book-open"
                                    class="w-full cursor-pointer"
                                >
                                    <span
                                        wire:loading.remove
                                        wire:target="bookBorrow({{ $book->id }})"
                                    >{{ __('Borrow') }}</span>
                                    <span
                                        wire:loading
                                        wire:target="bookBorrow({{ $book->id }})"
                                    >{{ __('Borrowing...') }}</span>
                                </flux:button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
