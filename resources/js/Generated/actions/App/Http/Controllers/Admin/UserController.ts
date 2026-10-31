import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:33
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
export const index = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:33
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
index.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:33
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
index.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::index
* @see app/Http/Controllers/Admin/UserController.php:33
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
index.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:40
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/create'
*/
export const create = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/users/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:40
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/create'
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
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:40
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/create'
*/
create.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::create
* @see app/Http/Controllers/Admin/UserController.php:40
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/create'
*/
create.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/manage/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
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
* @see \App\Http\Controllers\Admin\UserController::store
* @see app/Http/Controllers/Admin/UserController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:55
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}/edit'
*/
export const edit = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/users/{user}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:55
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}/edit'
*/
edit.url = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            user: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        user: args.user,
    }

    return edit.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:55
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}/edit'
*/
edit.get = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\UserController::edit
* @see app/Http/Controllers/Admin/UserController.php:55
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}/edit'
*/
edit.head = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
export const update = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/manage/users/{user}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
update.url = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            user: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        user: args.user,
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::update
* @see app/Http/Controllers/Admin/UserController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
update.put = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:78
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
export const destroy = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '//{tenant?}.localhost/manage/users/{user}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:78
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
destroy.url = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            user: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        user: args.user,
    }

    return destroy.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\UserController::destroy
* @see app/Http/Controllers/Admin/UserController.php:78
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/users/{user}'
*/
destroy.delete = (args: { tenant?: string | number, user: string | number } | [tenant: string | number, user: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const UserController = { index, create, store, edit, update, destroy }

export default UserController