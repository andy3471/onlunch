import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Site\LunchBookingController::store
* @see app/Http/Controllers/Site/LunchBookingController.php:28
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/lunch-booking',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::store
* @see app/Http/Controllers/Site/LunchBookingController.php:28
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
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
* @see \App\Http\Controllers\Site\LunchBookingController::store
* @see app/Http/Controllers/Site/LunchBookingController.php:28
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking/{lunchBooking}'
*/
export const update = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '//{tenant?}.localhost/lunch-booking/{lunchBooking}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking/{lunchBooking}'
*/
update.url = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            tenant: args[0],
            lunchBooking: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "tenant",
    ])

    const parsedArgs = {
        tenant: args.tenant ?? '$site',
        lunchBooking: args.lunchBooking,
    }

    return update.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{lunchBooking}', parsedArgs.lunchBooking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:45
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking/{lunchBooking}'
*/
update.put = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
*/
export const destroy = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '//{tenant?}.localhost/lunch-booking',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
*/
destroy.url = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:70
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-booking'
*/
destroy.delete = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const lunchBooking = {
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default lunchBooking