<div x-data="{
    buttons() {
            return Array.from(this.$root.closest('form').querySelectorAll('[role=tab]'))
        },
        activeIndex() {
            const i = this.buttons().findIndex(
                (el) => el.getAttribute('aria-selected') === 'true' || el.classList.contains('fi-active')
            )
            return i === -1 ? 0 : i
        },
        go(step) {
            const buttons = this.buttons()
            const next = Math.min(Math.max(this.activeIndex() + step, 0), buttons.length - 1)
            buttons[next]?.click()
        },
}" class="flex items-center justify-between gap-3 pt-4">
    <x-filament::button type="button" color="gray" icon="heroicon-m-chevron-right" x-on:click="go(-1)">
        التبويب السابق
    </x-filament::button>

    <x-filament::button type="button" icon="heroicon-m-chevron-left" x-on:click="go(1)">
        التبويب التالي
    </x-filament::button>
</div>
