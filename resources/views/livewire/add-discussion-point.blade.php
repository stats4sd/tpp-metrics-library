<div
    x-data="{
        focused: false,
        previous: false,
        open: false
    }">

    <button
        class="filament-button filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2rem] px-3 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700"

        x-on:click.outside="previous = focused; focused = document.activeElement; console.log('hi')"
        x-on:click="open = true"
    >Add Discussion Point
    </button>

    <div
        x-show="open"
        style="display:none"
        x-on:keydown.escape.prevent.stop="open = false"
        role="dialog"
        aria-modal="true"
        x-id="['modal-title']"
        :aria-labelledby="$id('modal-title')"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
        <!-- Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Panel -->
        <div
            x-show="open" x-transition
            x-on:click="open = false"
            class="relative flex min-h-screen items-center justify-center p-4"
        >
            <div
                x-on:click.stop
                x-trap.noscroll.inert="open"
                class="relative w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-10 shadow-lg"
            >
                <!-- Title -->
                <h2 class="text-3xl font-bold" :id="$id('modal-title')">Add Discussion Point</h2>

                <!-- Content -->
                <p class="mt-2 text-gray-600">
                    Current highlight is: <span x-html="previous"></span>
                </p>

                <!-- Buttons -->
                <div class="mt-8 flex space-x-2">
                    <button type="button" x-on:click="open = false"
                            class="rounded-md border border-gray-200 bg-white px-5 py-2.5">
                        Confirm
                    </button>

                    <button type="button" x-on:click="open = false"
                            class="rounded-md border border-gray-200 bg-white px-5 py-2.5">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

