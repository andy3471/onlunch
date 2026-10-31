import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::show
* @see app/Http/Controllers/Admin/SiteSettingsController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
export const show = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/settings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::show
* @see app/Http/Controllers/Admin/SiteSettingsController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
show.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args?.tenant ?? '$site',
    }

    return show.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::show
* @see app/Http/Controllers/Admin/SiteSettingsController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
show.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::show
* @see app/Http/Controllers/Admin/SiteSettingsController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
show.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::update
* @see app/Http/Controllers/Admin/SiteSettingsController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
export const update = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/manage/settings',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::update
* @see app/Http/Controllers/Admin/SiteSettingsController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
update.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tenant: args }
    }

    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args?.tenant ?? '$site',
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\SiteSettingsController::update
* @see app/Http/Controllers/Admin/SiteSettingsController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/settings'
*/
update.put = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

const SiteSettingsController = { show, update }

export default SiteSettingsController