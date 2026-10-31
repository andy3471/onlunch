import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Site\LunchBookingController::store
* @see app/Http/Controllers/Site/LunchBookingController.php:26
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings'
*/
export const store = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '//{tenant?}.localhost/lunch-bookings',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::store
* @see app/Http/Controllers/Site/LunchBookingController.php:26
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings'
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
* @see app/Http/Controllers/Site/LunchBookingController.php:26
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings'
*/
store.post = (args?: { tenant?: string | number } | [tenant: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:43
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
export const update = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '//{tenant?}.localhost/lunch-bookings/{lunchBooking}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:43
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
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
* @see app/Http/Controllers/Site/LunchBookingController.php:43
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
update.put = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Site\LunchBookingController::update
* @see app/Http/Controllers/Site/LunchBookingController.php:43
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
update.patch = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:65
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
export const destroy = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '//{tenant?}.localhost/lunch-bookings/{lunchBooking}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:65
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
destroy.url = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{tenant?}', parsedArgs.tenant?.toString() ?? '')
            .replace('{lunchBooking}', parsedArgs.lunchBooking.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Site\LunchBookingController::destroy
* @see app/Http/Controllers/Site/LunchBookingController.php:65
* @param tenant - Default: '$site'
* @route '//{tenant?}.localhost/lunch-bookings/{lunchBooking}'
*/
destroy.delete = (args: { tenant?: string | number, lunchBooking: string | number } | [tenant: string | number, lunchBooking: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const lunchBookings = {
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default lunchBookings