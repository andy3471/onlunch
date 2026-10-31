import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::request
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
export const request = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: request.url(args, options),
    method: 'get',
})

request.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/forgot-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::request
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
request.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return request.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::request
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
request.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: request.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::request
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:16
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
request.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: request.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::email
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
export const email = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: email.url(args, options),
    method: 'post',
})

email.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/forgot-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::email
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
email.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return email.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::email
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/forgot-password'
*/
email.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: email.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
export const reset = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reset.url(args, options),
    method: 'get',
})

reset.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/reset-password/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
reset.url = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions) => {
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

    return reset.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
reset.get = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reset.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:23
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password/{token}'
*/
reset.head = (args: { tenant?: string | number, token: string | number } | [tenant: string | number, token: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reset.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::update
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
*/
export const update = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/reset-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::update
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
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
* @see \App\Http\Controllers\Auth\ResetPasswordController::update
* @see app/Http/Controllers/Auth/ResetPasswordController.php:31
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/reset-password'
*/
update.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\User\PasswordController::change
* @see app/Http/Controllers/User/PasswordController.php:21
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/change-password'
*/
export const change = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: change.url(args, options),
    method: 'get',
})

change.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/change-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\User\PasswordController::change
* @see app/Http/Controllers/User/PasswordController.php:21
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/change-password'
*/
change.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return change.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\User\PasswordController::change
* @see app/Http/Controllers/User/PasswordController.php:21
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/change-password'
*/
change.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: change.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\User\PasswordController::change
* @see app/Http/Controllers/User/PasswordController.php:21
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/change-password'
*/
change.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: change.url(args, options),
    method: 'head',
})

const password = {
    request: Object.assign(request, request),
    email: Object.assign(email, email),
    reset: Object.assign(reset, reset),
    update: Object.assign(update, update),
    change: Object.assign(change, change),
}

export default password