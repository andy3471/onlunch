import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::index
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:27
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests'
*/
export const index = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '//{tenant?}.localhost/manage/time-off-requests',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::index
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:27
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests'
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
* @see \App\Http\Controllers\Admin\TimeOffRequestController::index
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:27
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests'
*/
index.get = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::index
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:27
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests'
*/
index.head = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::approve
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:53
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/approve'
*/
export const approve = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

approve.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::approve
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:53
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/approve'
*/
approve.url = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            timeOffRequest: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        timeOffRequest: args.timeOffRequest,
    }

    return approve.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{timeOffRequest}', parsedArgs.timeOffRequest.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::approve
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:53
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/approve'
*/
approve.post = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::reject
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:71
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/reject'
*/
export const reject = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

reject.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/reject',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::reject
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:71
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/reject'
*/
reject.url = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            timeOffRequest: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        timeOffRequest: args.timeOffRequest,
    }

    return reject.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{timeOffRequest}', parsedArgs.timeOffRequest.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\TimeOffRequestController::reject
* @see app/Http/Controllers/Admin/TimeOffRequestController.php:71
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/manage/time-off-requests/{timeOffRequest}/reject'
*/
reject.post = (args: { tenant?: string | number, timeOffRequest: string | number } | [tenant: string | number, timeOffRequest: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

const timeOffRequests = {
    index: Object.assign(index, index),
    approve: Object.assign(approve, approve),
    reject: Object.assign(reject, reject),
}

export default timeOffRequests