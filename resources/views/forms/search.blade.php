<form role="search" method="get" class="search-form flex w-full max-w-md flex-wrap gap-3" action="{{ home_url('/') }}">
    <label class="min-w-48 flex-1">
        <span class="sr-only"> {{ _x('Search for:', 'label', 'sage') }} </span>

        <input
            type="search"
            class="border-gray/40 placeholder:text-gray focus:border-black w-full rounded-md border px-4 py-3 text-sm outline-none transition duration-300"
            placeholder="{!! esc_attr_x('Search &hellip;', 'placeholder', 'sage') !!}"
            value="{!! get_search_query() !!}"
            name="s"
        />
    </label>

    <button type="submit" class="wp-element-button">{{ _x('Search', 'submit button', 'sage') }}</button>
</form>
