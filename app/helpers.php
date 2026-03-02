<?php

if (! function_exists('setting')) {
    /**
     * Retrieve a school/site setting by key.
     * Falls back to $default if the key has no stored value.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('school_name')) {
    /**
     * Returns the localised school name.
     */
    function school_name(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') {
            return setting('school_name_ar', setting('school_name', 'School'));
        }
        return setting('school_name', 'School');
    }
}
