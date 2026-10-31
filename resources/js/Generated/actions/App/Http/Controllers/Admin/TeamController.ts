import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TeamController::index
* @see app/Http/Controllers/Admin/TeamController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
*/
export const index = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/teams',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TeamController::index
* @see app/Http/Controllers/Admin/TeamController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
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
* @see \App\Http\Controllers\Admin\TeamController::index
* @see app/Http/Controllers/Admin/TeamController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
*/
index.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::index
* @see app/Http/Controllers/Admin/TeamController.php:19
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
*/
index.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::create
* @see app/Http/Controllers/Admin/TeamController.php:39
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/create'
*/
export const create = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/teams/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TeamController::create
* @see app/Http/Controllers/Admin/TeamController.php:39
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/create'
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
* @see \App\Http\Controllers\Admin\TeamController::create
* @see app/Http/Controllers/Admin/TeamController.php:39
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/create'
*/
create.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::create
* @see app/Http/Controllers/Admin/TeamController.php:39
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/create'
*/
create.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::store
* @see app/Http/Controllers/Admin/TeamController.php:46
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/manage/teams',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TeamController::store
* @see app/Http/Controllers/Admin/TeamController.php:46
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
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
* @see \App\Http\Controllers\Admin\TeamController::store
* @see app/Http/Controllers/Admin/TeamController.php:46
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::edit
* @see app/Http/Controllers/Admin/TeamController.php:60
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}/edit'
*/
export const edit = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/teams/{team}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TeamController::edit
* @see app/Http/Controllers/Admin/TeamController.php:60
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}/edit'
*/
edit.url = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            team: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        team: args.team,
    }

    return edit.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{team}', parsedArgs.team.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeamController::edit
* @see app/Http/Controllers/Admin/TeamController.php:60
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}/edit'
*/
edit.get = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::edit
* @see app/Http/Controllers/Admin/TeamController.php:60
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}/edit'
*/
edit.head = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TeamController::update
* @see app/Http/Controllers/Admin/TeamController.php:73
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}'
*/
export const update = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/manage/teams/{team}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\TeamController::update
* @see app/Http/Controllers/Admin/TeamController.php:73
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}'
*/
update.url = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            team: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        team: args.team,
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{team}', parsedArgs.team.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TeamController::update
* @see app/Http/Controllers/Admin/TeamController.php:73
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/teams/{team}'
*/
update.put = (args: { tenant?: string | number, team: string | number } | [tenant: string | number, team: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

const TeamController = { index, create, store, edit, update }

export default TeamController