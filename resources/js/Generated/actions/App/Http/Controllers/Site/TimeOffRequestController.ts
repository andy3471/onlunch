import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Site\TimeOffRequestController::store
* @see app/Http/Controllers/Site/TimeOffRequestController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/time-off-requests'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/time-off-requests',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Site\TimeOffRequestController::store
* @see app/Http/Controllers/Site/TimeOffRequestController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/time-off-requests'
*/
store.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\TimeOffRequestController::store
* @see app/Http/Controllers/Site/TimeOffRequestController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/time-off-requests'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const TimeOffRequestController = { store }

export default TimeOffRequestController