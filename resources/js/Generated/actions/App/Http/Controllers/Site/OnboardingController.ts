import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Site\OnboardingController::show
* @see app/Http/Controllers/Site/OnboardingController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
*/
export const show = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/onboarding',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Site\OnboardingController::show
* @see app/Http/Controllers/Site/OnboardingController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
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
* @see \App\Http\Controllers\Site\OnboardingController::show
* @see app/Http/Controllers/Site/OnboardingController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
*/
show.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Site\OnboardingController::show
* @see app/Http/Controllers/Site/OnboardingController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
*/
show.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Site\OnboardingController::store
* @see app/Http/Controllers/Site/OnboardingController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/onboarding',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Site\OnboardingController::store
* @see app/Http/Controllers/Site/OnboardingController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
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
* @see \App\Http\Controllers\Site\OnboardingController::store
* @see app/Http/Controllers/Site/OnboardingController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/onboarding'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const OnboardingController = { show, store }

export default OnboardingController