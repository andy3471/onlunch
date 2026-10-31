import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::create
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
export const create = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/reset-password/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::create
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
create.url = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            token: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        token: args.token,
    }

    return create.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::create
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
create.get = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::create
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
create.head = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::store
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/reset-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::store
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
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
* @see \App\Http\Controllers\Auth\ResetPasswordController::store
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const ResetPasswordController = { create, store }

export default ResetPasswordController