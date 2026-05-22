<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuManager
{
    private array $items = [];

    /**
     * Register menu items for a module.
     *
     * @param string $module  Module alias
     * @param array  $items   Array of menu item definitions
     */
    public function register(string $module, array $items): void
    {
        foreach ($items as $item) {
            $this->items[] = array_merge($item, ['module' => $module]);
        }
    }

    /**
     * Get all menu items the current user can see, sorted by order.
     */
    public function visible(): array
    {
        $user  = Auth::user();
        $items = array_filter($this->items, function (array $item) use ($user) {
            if (empty($item['permission'])) {
                return true;
            }

            if (! $user) {
                return false;
            }

            return $user->can($item['permission']);
        });

        usort($items, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return array_values($items);
    }

    /**
     * Get raw items for a specific module.
     */
    public function forModule(string $module): array
    {
        return array_values(array_filter(
            $this->items,
            fn($item) => $item['module'] === $module
        ));
    }
}
