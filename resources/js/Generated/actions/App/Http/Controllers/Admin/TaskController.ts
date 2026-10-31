import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TaskController::index
* @see app/Http/Controllers/Admin/TaskController.php:20
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
*/
export const index = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/tasks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::index
* @see app/Http/Controllers/Admin/TaskController.php:20
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
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
* @see \App\Http\Controllers\Admin\TaskController::index
* @see app/Http/Controllers/Admin/TaskController.php:20
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
*/
index.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::index
* @see app/Http/Controllers/Admin/TaskController.php:20
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
*/
index.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::create
* @see app/Http/Controllers/Admin/TaskController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/create'
*/
export const create = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/tasks/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::create
* @see app/Http/Controllers/Admin/TaskController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/create'
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
* @see \App\Http\Controllers\Admin\TaskController::create
* @see app/Http/Controllers/Admin/TaskController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/create'
*/
create.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::create
* @see app/Http/Controllers/Admin/TaskController.php:42
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/create'
*/
create.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::store
* @see app/Http/Controllers/Admin/TaskController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/manage/tasks',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::store
* @see app/Http/Controllers/Admin/TaskController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
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
* @see \App\Http\Controllers\Admin\TaskController::store
* @see app/Http/Controllers/Admin/TaskController.php:49
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::edit
* @see app/Http/Controllers/Admin/TaskController.php:62
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}/edit'
*/
export const edit = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/tasks/{task}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::edit
* @see app/Http/Controllers/Admin/TaskController.php:62
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}/edit'
*/
edit.url = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            task: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        task: args.task,
    }

    return edit.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TaskController::edit
* @see app/Http/Controllers/Admin/TaskController.php:62
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}/edit'
*/
edit.get = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::edit
* @see app/Http/Controllers/Admin/TaskController.php:62
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}/edit'
*/
edit.head = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::update
* @see app/Http/Controllers/Admin/TaskController.php:75
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
export const update = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/manage/tasks/{task}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::update
* @see app/Http/Controllers/Admin/TaskController.php:75
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
update.url = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            task: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        task: args.task,
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TaskController::update
* @see app/Http/Controllers/Admin/TaskController.php:75
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
update.put = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\TaskController::destroy
* @see app/Http/Controllers/Admin/TaskController.php:83
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
export const destroy = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '//{tenant?}.localhost/manage/tasks/{task}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\TaskController::destroy
* @see app/Http/Controllers/Admin/TaskController.php:83
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
destroy.url = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            task: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        task: args.task,
    }

    return destroy.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TaskController::destroy
* @see app/Http/Controllers/Admin/TaskController.php:83
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/tasks/{task}'
*/
destroy.delete = (args: { tenant?: string | number, task: string | number } | [tenant: string | number, task: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const TaskController = { index, create, store, edit, update, destroy }

export default TaskController