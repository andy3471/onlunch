import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::store
* @see app/Http/Controllers/Site/TaskAssignmentController.php:29
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/task-assignments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::store
* @see app/Http/Controllers/Site/TaskAssignmentController.php:29
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments'
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
* @see \App\Http\Controllers\Site\TaskAssignmentController::store
* @see app/Http/Controllers/Site/TaskAssignmentController.php:29
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::update
* @see app/Http/Controllers/Site/TaskAssignmentController.php:58
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
export const update = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/task-assignments/{taskAssignment}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::update
* @see app/Http/Controllers/Site/TaskAssignmentController.php:58
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
update.url = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            taskAssignment: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        taskAssignment: args.taskAssignment,
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{taskAssignment}', parsedArgs.taskAssignment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::update
* @see app/Http/Controllers/Site/TaskAssignmentController.php:58
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
update.put = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::destroy
* @see app/Http/Controllers/Site/TaskAssignmentController.php:90
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
export const destroy = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '//{tenant?}.localhost/task-assignments/{taskAssignment}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::destroy
* @see app/Http/Controllers/Site/TaskAssignmentController.php:90
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
destroy.url = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            taskAssignment: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        taskAssignment: args.taskAssignment,
    }

    return destroy.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{taskAssignment}', parsedArgs.taskAssignment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\TaskAssignmentController::destroy
* @see app/Http/Controllers/Site/TaskAssignmentController.php:90
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/task-assignments/{taskAssignment}'
*/
destroy.delete = (args: { tenant?: string | number, taskAssignment: string | number } | [tenant: string | number, taskAssignment: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const taskAssignments = {
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default taskAssignments