<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed()]
    public function myBorrowedBooks()
    {
        return Auth::user()->bookBorrows()->with('book:id,title,author')->get();
    }

    #[On('book-borrowed')]
    public function resetMyborrowedBooks()
    {
        unset($this->myBorrowedBooks);
    }
};
?>

<div>
     <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-700">
                    <flux:heading
                        >{{ __('My Borrowed Books') }}
                        <span class="font-normal text-zinc-500">({{ $this->myBorrowedBooks->count() }})</span></flux:heading>
                    <flux:text size="sm" class="mt-1">{{ __('Books you have currently borrowed.') }}</flux:text>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->myBorrowedBooks as $borrow)
                        <div class="flex items-center justify-between gap-4 px-5 py-3 text-sm">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-zinc-900 dark:text-white">
                                    {{ $borrow->book->title ?? __('Unknown book') }}
                                </p>
                                <p class="truncate text-xs text-zinc-500">
                                    {{ $borrow->book->author ?? '' }}
                                    @if ($borrow->borrow_at) ·{{ __('Borrowed on :date', ['date' => $borrow->borrow_at->format('M d, Y')]) }} @endif
                                </p>
                            </div>
                            <flux:badge
                                size="sm"
                                color="zinc"
                            >{{ $borrow->return_at ? __('Returned') : __('Active') }}</flux:badge>
                        </div>
                    @endforeach
                </div>
            </div>
</div>