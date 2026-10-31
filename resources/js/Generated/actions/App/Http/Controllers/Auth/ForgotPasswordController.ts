import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::create
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
export const create = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/forgot-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::create
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
create.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::create
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
create.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::create
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
create.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::store
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/forgot-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::store
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
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
* @see \App\Http\Controllers\Auth\ForgotPasswordController::store
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const ForgotPasswordController = { create, store }

export default ForgotPasswordController